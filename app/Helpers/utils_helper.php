<?php

use Config\Database;
use Config\Services;
use Mpdf\Mpdf;

/**
 * Truncates the given string at the specified length.
 *
 * @param string $str The input string.
 * @param int $width The number of chars at which the string will be truncated.
 * @return string
 */

function truncate($str, $width): string
{
    return strtok(wordwrap($str, $width, "...\n"), "\n");
}


function is_image($path): bool
{
    $a = @getimagesize($path);
    $image_type = $a[2];
    if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_JPEG))) {
        return true;
    }
    return false;
}


if (!function_exists('thumb')) {
    function thumb($imagesData, $width, $height)
    {
        // Decode the JSON data
        $images = json_decode($imagesData, true);

        // Check if the 'files' array is not empty
        if (!empty($images['files'])) {
            // Get the first file in the array
            $file = $images['files'][0];

            // Extract image information
            $rawName = $file['encoded_name'];
            $extension = $file['extension'];
            $fullPath = $file['full_path'];

            $encodedFileNameWithoutExtension = pathinfo($rawName, PATHINFO_FILENAME);

            // Generate the thumbnail name
            $thumbnailName = "{$encodedFileNameWithoutExtension}_{$width}_{$height}.{$extension}";
            $thumbnailPath = dirname($fullPath) . "/{$thumbnailName}";

            // Check if the thumbnail image already exists with the given dimensions
            if (!file_exists($thumbnailPath)) {
                // Thumbnail doesn't exist, generate it from the original image
                if (file_exists($fullPath)) {
                    // Create a new instance of the image library (Imagick)
                    $image = \Config\Services::image()
                        ->withFile($fullPath)
                        ->resize($width, $height, true, 'height')
                        ->save($thumbnailPath);
                } else {
                    // Original image not found, return a not found image
                    return base_url('site/imgs/testim.png');
                }
            }

            // Return the path to the thumbnail
            return $thumbnailPath;
        }

        // If no image data or files are found, return a not found image
        return base_url('site/imgs/testim.png');
    }
}


if (!function_exists('localized_field')) {
    function localized_field($field, $record)
    {
        $langKey = lang('Site.lang');
        // Check if $record is an array or an object
        if (is_array($record)) {
            return $record[$field . '_' . $langKey];
        } elseif (is_object($record)) {
            // Assuming your object properties are public
            return $record->{$field . '_' . $langKey};
        }
        return null;
    }
}


if (!function_exists('set_log')) {
    function set_log($notification_name, $response)
    {
        $db = \Config\Database::connect();
        $data = [
            'cron_name' => $notification_name,
            'cron_result' => $response,
        ];
        $db->table('tbcron_log')->insert($data);
    }
}


// Generate form token

//--------------------------------------------------------------------
if (!function_exists('send_driver_push')) {
    /**
     * Create New Notification
     *
     * Creates adjacency list based on item (id or slug) and shows leafs related only to current item
     *
     * @param array $notification_data
     */

    function send_driver_push(array $notification_data)
    {
        $admin_assets = base_url('site/default/img/');
        $content = array(
            "en" => $notification_data["title_en"]
        );

        if (is_array($notification_data["recipient_id"])) {
            $users = array_column($notification_data["recipient_id"], 'user_id');
        } else {
            $users = array($notification_data["recipient_id"]);
        }

        $fields = array(
            'app_id' => "cb87ff71-5247-4ee8-8354-5f2cca0985b9",
            "include_aliases" => array(
                "external_id" => $users
            ),
            "target_channel" => "push",
            'small_icon' => "ic_stat_onesignal_default.png",
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ZWUyZmRkNTctOWE2NS00ODM5LWE5M2UtYTc5Njk5MGQ1M2Ji'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        set_log($notification_data["title_en"], $response);
        curl_close($ch);
    }
}


if (!function_exists('send_push_notification')) {
    /**
     * Create New Notification
     *
     * Creates adjacency list based on item (id or slug) and shows leafs related only to current item
     *
     * @param array $notification_data
     * @return string $response
     */

    function send_push_notification(array $notification_data)
    {
        $admin_assets = base_url('site/default/img/');
        $content = array(
            "en" => $notification_data["title_en"]
        );

        if (is_array($notification_data["recipient_id"])) {
            $users = array_column($notification_data["recipient_id"], 'user_id');
        } else {
            $users = array($notification_data["recipient_id"]);
        }

        $fields = array(
            'app_id' => "b3d49cfc-86b1-448f-bec3-6f9d9d08c47e",
            "include_aliases" => array(
                "external_id" => $users
            ),
            "target_channel" => "push",
            'small_icon' => "ic_stat_onesignal_default.png",
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic Njk1YTRhN2QtNzVkNC00NGY4LWE4NjMtYTU4OGJhZDQ4Yjk5'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        set_log($notification_data["title_en"], $response);
        curl_close($ch);
    }
}




function json_output($response)
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, OPTIONS');
    header("Access-Control-Allow-Headers: X-Requested-With");
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION );
    exit;
}



function date_range_handle($date_time_range): array
{
    $date_range_array = explode('/', trim($date_time_range));
    return array("first_date" => trim($date_range_array[0]), 'second_date' => trim($date_range_array[1]));
}


/**
 * @throws \Mpdf\MpdfException
 */
function download_report($html, $path = 'writable/orders/')
{
    ini_set('max_execution_time', '300');
    ini_set("pcre.backtrack_limit", "5000000");

    $display_mode = "F"; // Change display mode to save as file

    $dir = $path . date("Y_m_d_H_i_s");

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    } else {
        echo "عفوا،البحث مكرر،يمكنك حذف البحث المسجل لنفس اليوم أولاً ثم إعادة المحاولة";
        return;
    }

    $mpdf = new Mpdf([
        'allow_charset_conversion' => true,
        'charset_in' => 'UTF-8',
        'curlAllowUnsafeSslRequests' => true,
        'debug' => true,
        'direction' => 'rtl',
        'autoLangToFont' => true,
    ]);

    $output = $dir . '/' . time() . ".pdf";

    // Render the view into HTML
    $mpdf->WriteHTML($html);

    // Output the PDF as a file
    $mpdf->Output($output, $display_mode);

    // Download the file
    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($output) . "\"");
    readfile($output);
    exit;
}

if (!function_exists('currentModule')) {
    function currentModule()
    {
        $namespace = \Config\Services::router()->getMatchedRouteOptions()['namespace'] ?? '';
        $module = explode('\\', $namespace)[1] ?? 'DefaultModule';
        $formattedModuleName = ucwords(str_replace('_', ' ', $module));
        $moduleNamespace = "Modules\\" . str_replace(' ', '', $formattedModuleName);
        if (ENVIRONMENT === 'development' && !moduleExists($moduleNamespace)) {
            echo "Module '{$moduleNamespace}' not found in PSR-4 namespaces.";
        }
        return $formattedModuleName;
    }

    function moduleExists($moduleName): bool
    {
        return array_key_exists($moduleName, \Config\Services::autoloader()->getNamespace());
    }
}

// Create a function to recursively generate the sidebar links
if (!function_exists('generateSidebarLinks')) {
    function generateSidebarLinks($parent_id = 0) {
        $db = Database::connect();
        $cache = Services::cache();
        // Key for caching the sections
        $cacheKey = 'cached_sections';
        // Check if the sections are already cached
        $sections = $cache->get($cacheKey);
        if (!$sections) {
            // If not cached, fetch the data from the database
            $sections = $db->table('sections')
                ->where('active', 1)
                ->orderBy('sort', 'ASC')
                ->get()
                ->getResultArray();

            // Cache the sections for a specific duration (e.g., 1 hour)
            $cache->save($cacheKey, $sections, 3600);
        }
        // Fetch all active sections ordered by sort
        $sections = $db->table('sections')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();
        foreach ($sections as $section) {
            if ($section['parent_id'] == $parent_id) {
                echo '<li class="nav-item">';
                echo '<a href="' . base_url('dt_admin/' . $section['section_link']) . '" class="nav-link">';
                echo '<i class="nav-icon ' . $section['icon'] . '"></i>';
                // Modify this line to add inline CSS for bold text
                echo '<p style="font-weight: bold;">' . $section['title'] . '</p>';
                if (hasChildSections($sections, $section['id'])) {
                    echo '<i class="right fas fa-angle-left"></i>';
                }
                echo '</a>';
                if (hasChildSections($sections, $section['id'])) {
                    echo '<ul class="nav nav-treeview">';
                    generateSidebarLinks($section['id']);
                    echo '</ul>';
                }
                echo '</li>';
            }
        }
    }

    function hasChildSections($sections, $parent_id): bool {
        foreach ($sections as $section) {
            if ($section['parent_id'] == $parent_id) {
                return true;
            }
        }
        return false;
    }
}

function isCurrentPage($path) {
    // Get the current URL path without the base URL part
    $currentPath = uri_string();

    // Return true if the current path is the same as the path provided
    return $currentPath === $path;
}

