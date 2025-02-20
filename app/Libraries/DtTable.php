<?php

namespace App\Libraries;


class DtTable
{
    public static $path  ;
    private static $query;
    private static $db;
    private static string $tableName = '';
    private static array $hiddenColumns = ['id'];
    private static array $orderableColumns = [];
    private static array $searchableColumns = [];
    private static array $actions = ['edit', 'show', 'delete'];
    private static array $columns = [];
    private static array $columnCallbacks = [];
    private static array $hiddenActions = [];
    private static array $actionUrls = [];
    private static array $rowSpecificHiddenActions = [];
    public static array $columnModelCallbacks = [];
    private static string $showColumns = ''; // Change the property to a string

    private static array $customFilters = [];

    public static function setCustomFilter($filterName, $callback)
    {
        self::$customFilters[$filterName] = $callback;
    }

    private static function applyCustomFilters()
    {
        foreach (self::$customFilters as $filterName => $callback) {
            $filterValue = request()->getPost($filterName);
            if ($filterValue !== null) {
                // Add a debug statement here
                log_message('debug', "Applying filter: $filterName with value: $filterValue");
                $callback(self::$query, $filterValue);
            }
        }
    }


    public static function setColumnDataList($modelName, $columnName, $callbackFunction)
    {
        self::$columnModelCallbacks[$columnName] = [
            'model' => $modelName,
            'method' => $callbackFunction
        ];
    }

    public static function setShowColumns($columns)
    {
        self::$showColumns = $columns; // Store the text value directly
    }

    public static function hideActions(array $actions, array $conditions = [])
    {
        if (empty($conditions)) {
            self::$actions = array_diff(self::$actions, $actions);
        } else {
            $conditionKey = json_encode($conditions);
            if (!isset(self::$rowSpecificHiddenActions[$conditionKey])) {
                self::$rowSpecificHiddenActions[$conditionKey] = [];
            }
            self::$rowSpecificHiddenActions[$conditionKey] = array_merge(self::$rowSpecificHiddenActions[$conditionKey], $actions);
        }
    }

    public static function tableRender($query = null, $returnQuery = false)
    {
        $draw = intval(request()->getPost("draw"));
        $start = intval(request()->getPost("start") ?? 0);
        $length = intval(request()->getPost("length") ?? 10);
        $order = request()->getPost("order");
        $search = request()->getPost("search");
        $searchValue = '';
        if ($search !== null && !empty(trim($search['value']))) {
            $searchValue = trim($search['value']);
            $searchValue = str_replace(" ", "%", $searchValue);
        }
        $ajaxUrl = self::$path = current_url() ;

        if ($query !== null) {
            self::$query = $query;
            self::$db = $query->db();
        }

        // Clone query for total records count (without any filters)
        //   $totalRecordsQuery = clone self::$query;

        // Total Records Count (unfiltered)
        //    $total_records = $totalRecordsQuery->countAllResults(false);
       // $total_records = 2000;

        // Apply custom filters
        self::applyCustomFilters();

        // Clone query for filtered records count (after applying custom filters)
        $filteredRecordsQuery = clone self::$query;

        // Apply search on searchable columns
        if (!empty($searchValue) && !empty(self::$searchableColumns)) {
            $counter = 0;
            foreach (self::$searchableColumns as $column) {
                if ($column != 'id') {
                    if ($counter == 0) {
                        self::$query->like($column, $searchValue);
                        $filteredRecordsQuery->groupStart()->like($column, $searchValue);
                    } else {
                        self::$query->orLike($column, $searchValue);
                        $filteredRecordsQuery->orLike($column, $searchValue);
                    }
                    $counter++;
                }
            }
            $filteredRecordsQuery->groupEnd();
        }

        // Filtered Records Count (after applying filters and search)
       $filtered_records = $filteredRecordsQuery->countAllResults(false);

        // Apply ordering
        if (!empty($order)) {
            $visibleColumns = array_diff(self::$columns, self::$hiddenColumns, ['checkbox']);
            $visibleColumns = array_values($visibleColumns);
            foreach ($order as $ord) {
                $adjustedIndex = $ord['column'] - 2;
                if ($adjustedIndex >= 0 && $adjustedIndex < count($visibleColumns)) {
                    $column = $visibleColumns[$adjustedIndex];
                    if (in_array($column, self::$orderableColumns)) {
                        self::$query->orderBy($column, $ord['dir']);
                    }
                }
            }
        }

        self::$query->limit($length, $start);

        // Execute the query and get the result
        $result = self::$query->get();

        // Get the table name from the query object
        self::$tableName = self::$query->getTable();

        // Get the column names from the query result
        $columnNames = $result->getFieldNames();

        // Convert the result to an array
        $data = $result->getResultArray();

        // As there might be multiple queries executed, let's get the last one.
        if (!empty($returnQuery)) {
            echo self::$db->getLastQuery();
            die;
        }

        if (!empty($columnNames)) {
            // Set the columns and searchable columns
            self::$columns = $columnNames;
        }

        $data = self::applyColumnCallbacks($data, $start);

        $columns = self::getColumnsConfig();

        $output = array(
            "draw" => $draw,
           // "recordsTotal" => $total_records,
            "recordsFiltered" => $filtered_records,
            "data" => $data,
            "columns" => $columns,
            "ajax" => [
                "url" => $ajaxUrl,
                "type" => "POST"
            ]
        );

        return json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public static function hideColumns(array $columns)
    {
        self::$hiddenColumns = array_merge(self::$hiddenColumns, $columns);
    }

    public static function setColumnSwitch($columnName, $tableName = NULL, $whereName = 'id', $className = '')
    {
        self::$columnCallbacks[$columnName] = function ($data, $row) use ($columnName, $tableName, $whereName, $className) {
            $checked = $data == 1 ? 'checked' : '';
            $table = $tableName ?? self::$tableName; // Use the static property for the table name
            $switch = "<div class='custom-control custom-switch $className'>";
            $switch .= "<input type='checkbox' class='custom-control-input switch-toggle' id='switch_{$row['id']}_$columnName' 
     data-id='{$row['id']}' data-table='{$table}' data-column='$columnName' data-where='$whereName' $checked>";
            $switch .= "<label class='custom-control-label' for='switch_{$row['id']}_$columnName'></label>";
            $switch .= "</div>";
            return $switch;
        };
    }


    public static function setColumnImage($columnName)
    {
        self::$columnCallbacks[$columnName] = function ($data, $row) use ($columnName) {
            $images = json_decode($data);
            $image = null;

            if (is_array($images->files) && count($images->files) > 0) {
                // Find the image with the lowest order and is_image = 1
                $filteredImages = array_filter($images->files, function ($image) {
                    return isset($image->is_image) && $image->is_image == 1 && isset($image->order);
                });

                if (count($filteredImages) > 0) {
                    usort($filteredImages, function ($a, $b) {
                        return $a->order <=> $b->order;
                    });
                    $image = reset($filteredImages);
                } else {
                    // If no image with is_image = 1, fallback to any image
                    $image = reset($images->files);
                }
            }

            if ($image) {
                // Adjust this line based on the image path property name
                return "<img src='" . base_url($image->full_path) . "' alt='Image' width='70' height='70'>";
            } else {
                // return the icon of no image using font awesome
                return '<i class="fas fa-image"></i>';
            }
        };
    }


    public static function orderableColumns(array $columns)
    {
        self::$orderableColumns = $columns;
    }

    public static function searchableColumns(array $columns)
    {
        self::$searchableColumns = $columns;
    }

    public static function notSearchableColumns(array $columns)
    {
        self::$searchableColumns = array_diff(self::$columns, $columns);
    }

    public static function setColumnLink($columnName, $urlTemplate)
    {
        self::$columnCallbacks[$columnName] = function ($data, $row) use ($columnName, $urlTemplate) {

            $url = str_replace(['id', '{data}'], [$row['id'], $data], $urlTemplate);
            return "<a href=\"{$url}\">{$data}</a>";
        };
    }
    public static function setColumnModal($columnName,$modalHeaderCallback, $modalContentCallback )
    {
        self::$columnCallbacks[$columnName] = function ($data, $row) use ($columnName, $modalContentCallback, $modalHeaderCallback) {
            $modalId = "modal-{$columnName}-{$row['id']}";

            // Generate the button that triggers the modal
            $button = "<button type='button' class='btn btn-info' data-toggle='modal' data-target='#{$modalId}'>{$data}</button>";

            // Generate the modal content and header using the provided callbacks
            $modalContent = $modalContentCallback($row);
            $modalHeader = $modalHeaderCallback($row);

            // Create the modal structure
            $modal = "
            <div class='modal fade' id='{$modalId}' tabindex='-1' role='dialog'>
                <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title'>{$modalHeader}</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            {$modalContent}
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        ";

            return $button . $modal;
        };
    }

    public static function changeColumn($columnName, callable $callback)
    {
        self::$columnCallbacks[$columnName] = $callback;
    }

    public static function setAction($action, $icon, $url)
    {
        if (!in_array($action, self::$hiddenActions)) {
            self::$actions[] = $action;
            self::$actionUrls[$action] = ['url' => $url, 'icon' => $icon];

            self::$columnCallbacks['actions'] = function ($data, $row) {
                return self::generateActionLinks($row);
            };
        }
    }


    private static function generateActionLinks($row): string
    {
        $linksHtml = '';

        foreach (self::$actions as $action) {
            if (isset(self::$actionUrls[$action]) && !in_array($action, self::$hiddenActions)) {
                $url = self::$actionUrls[$action];
                $linksHtml .= self::generateActionButton($action, $url, $row);
            }
        }

        return $linksHtml;
    }

    private static function generateActionButton($action, $actionData, $row): string
    {
        foreach (self::$rowSpecificHiddenActions as $conditionJson => $hiddenActions) {
            $conditions = json_decode($conditionJson, true);
            $match = true;
            foreach ($conditions as $column => $value) {
                if (!isset($row[$column]) || $row[$column] != $value) {
                    $match = false;
                    break;
                }
            }
            if ($match && in_array($action, $hiddenActions)) {
                return '';  // Don't generate the action button for restricted actions
            }
        }

        $url = $actionData['url'] ?? '';
        $icon = $actionData['icon'] ?? '';
        $colorClass = ''; // Add logic for color class if needed
        $tableName = self::$tableName;
        $columnsToShow = self::$showColumns; // Use the show columns here
        $moduleName = currentModule();

        switch ($action) {
            case 'edit':
                $icon = 'edit';
                $colorClass = 'btn-primary';
                break;
            case 'delete':
                $icon = 'trash';
                $colorClass = 'btn-danger';
                break;
            case 'show':
                $icon = 'eye';
                $colorClass = 'btn-info';
                break;
            default:
                break;
        }

        // Generate the HTML for the action button
        if(!empty($url)){
         return "<a href='{$url}{$row['id']}' onclick='location.href=this.href;'
                class='btn btn-sm  btn-info btn-sm dt_action $colorClass $action'
                    data-id='{$row['id']}' data-action='$action'  data-module='$moduleName'>
                 <i class='fas $icon'></i> 
             </a>";
        }
        return "<a href='javascript:void(0)' class='btn btn-sm dt_action $colorClass $action'
                data-id='{$row['id']}' data-action='$action'
                data-table='{$tableName}' data-columns='{$columnsToShow}' data-module='$moduleName'>
                <i class='fas fa-$icon'></i> 
        </a>";
    }



    public static function applyColumnCallbacks($data, $start): array
    {
        foreach ($data as $rowIndex => $row) {

            foreach (self::$columnCallbacks as $columnName => $callback) {
                if (isset($row[$columnName])) {
                    $data[$rowIndex][$columnName] = $callback($row[$columnName], $row);
                }
            }
            foreach (self::$columnModelCallbacks as $columnName => $callbackInfo) {
                if (isset($row[$columnName])) {
                    $model = new $callbackInfo['model'];
                    if (method_exists($model, $callbackInfo['method'])) {
                        $data[$rowIndex][$columnName] = call_user_func([$model, $callbackInfo['method']], $row[$columnName]);
                    }
                }
            }

            if (!isset($data[$rowIndex]['actions'])) {
                $data[$rowIndex]['actions'] = self::getActions($row, self::$actions);
            }

            $data[$rowIndex]['#'] = $start + $rowIndex + 1;
        }
        return $data;
    }

    public static function getActions($row, $actions): string
    {
        $actionsHtml = '';

        foreach ($actions as $action) {
            if (isset(self::$actionUrls[$action])) {
                $url = self::$actionUrls[$action];
                $actionsHtml .= self::generateActionButton($action, $url, $row);
            } elseif (in_array($action, self::$actions)) {
                $actionsHtml .= self::generateActionButton($action, '', $row);
            }
        }

        return $actionsHtml;
    }

    private static function getColumnsConfig(): array
    {
        $moduleName = currentModule();

        $columns = [];
        // First column: No className added
        $columns[] = array('data' => '#', 'name' => '#', 'orderable' => false, 'searchable' => false);
        $columnIndex = 0;
        log_message('debug', "Module Name: $moduleName");

        foreach (self::$columns as $field) {
            log_message('debug', "Field: $field, Language Line: " . lang($moduleName.'.'.$field));

            if ($field !== 'id' && !in_array($field, self::$hiddenColumns)) {
                $column = array();
                $column['title'] = lang($moduleName.'.'.$field);
                $column['data'] = $field;
                $column['name'] = $field;

                // Add className based on the field name, except for the first column
                if ($columnIndex > 0) {
                    $column['className'] = $field;
                }

                if (in_array($field, self::$orderableColumns)) {
                    $column['orderable'] = true;
                } else {
                    $column['orderable'] = false;
                }

                if (in_array($field, self::$searchableColumns)) {
                    $column['searchable'] = true;
                } else {
                    $column['searchable'] = false;
                }

                $columns[] = $column;
                $columnIndex++;
            }
        }

        $columns[] = array(
            'title' => lang('Admin.actions'),
            'data' => 'actions',
            'name' => 'actions',
            'orderable' => false,
            'searchable' => false
        );

        return $columns;
    }

}
