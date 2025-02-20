<?= $this->section('js'); ?>

<!-- DataTables Select extension: CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('admin/plugins/datatables-select/css/select.bootstrap4.min.css') ?>">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<!-- DataTables Select extension: JS -->
<script  type="text/javascript"  src="<?= base_url('admin/plugins/datatables-select/js/dataTables.select.js') ?>"></script>
<script  type="text/javascript"  src="<?= base_url('admin/plugins/datatables-select/js/select.bootstrap4.js') ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<!--<script type="text/javascript" src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/jspdf-autotable"></script>-->

<?php
$stateSaveValue = 'false' ;
$stateDurationValue = 60;
$ajaxUrl =  current_url() ;

?>
<script>
    let dt_table;
    let checkedRows = []; // Declare checkedRows as a global variable
    document.addEventListener('swup:contentReplaced', () => {
        initialize_datatable();
        console.log("contentReplaced 2")
    });
    document.addEventListener('DOMContentLoaded', function() {
        initialize_datatable();
        console.log("DOMContentLoaded 2")

    });


    // Functions for bulk actions
    function activateRows(table,whereName,columnName,rowId,value) {
        console.log(table)
        console.log(whereName)
        console.log(columnName)
        console.log(rowId)
        console.log(value)
        $.ajax({
            url: '<?= $ajaxUrl.'/switchToggle' ?>',
            type: 'POST', // Ensure it's a POST request
            data: {table,whereName,columnName,rowId,value},
            success: function(msg) {
                // Handle success response
                if (msg.status === 200) {
                    dt_table.ajax.reload();
                    toastr.success(msg.html, 'تم بنجاح', {allowHtml: true});
                } else {
                    console.log(msg.html)
                    toastr.error(msg.html, 'خطأ');
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.log('Error occurred while updating column value:', error);
            }
        });
    }

    function deleteRows(table,rows) {
        console.log(table)
        // Check if the rows are an array
        if (Array.isArray(rows)) {
            // Convert the rows array to a string
            rows = rows.join(',');
        }
        $.ajax({
            url: '<?= $ajaxUrl.'/delete' ?>',
            type: 'POST', // Ensure it's a POST request
            data: {table,rows},
            success: function(response) {
                // Handle success response
                dt_table.ajax.reload();
                toastr.success(response.message, 'Success');
            },
            error: function(xhr, status, error) {
                // Handle error response
                toastr.error(response.message, 'Failed');

                console.log('Error occurred while updating column value:', error);
            }
        });
        console.log("Delete: " + rows);
    }

    function showRow(url,module,table,columns) {
        console.log("showRow");
        $.ajax({
            url: url,
            type: 'POST',
            data: {module,table,columns},
            success: function (response) {
                if (response) {
                    const userData = response.data;

                    // Get the modal body element
                    const modalBody = $('#ShowModal').find('.modal-body');

                    // Clear the existing modal content
                    modalBody.empty();

                    // Loop through the user data properties
                    for (const prop in userData) {
                        if (userData.hasOwnProperty(prop)) {
                            // Create a new row element
                            const rowElement = $('<div>').addClass('row');

                            // Create a new label element
                            const labelElement = $('<div>').addClass('col-sm-3').text(prop + ':');

                            // Create a new value element
                            const valueElement = $('<div>').addClass('col-sm-9').text(userData[prop]);

                            // Append the label and value elements to the row element
                            rowElement.append(labelElement).append(valueElement);

                            // Append the row element to the modal body
                            modalBody.append(rowElement);
                        }
                    }

                    // Show the modal
                    $('#ShowModal').modal('toggle');
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.log('Error occurred while fetching data:', error);
            }
        });
    }

    function pdfPrint(rows) {
        console.log("pdfPrint: " + rows.join(', '));
    }

    // Handle Bulk Actions

    $(document).on('change', '.switch-toggle', function() {
        const isChecked = this.checked;
        const rowId = $(this).data('id');
        const columnName = $(this).data('column');
        const whereName = $(this).data('where');
        const table = $(this).data('table');
        const value = isChecked ? 1 : 0;
        // Send AJAX request to update the column value
        console.log("act")
        console.log(columnName)
        console.log(whereName)
        activateRows(table,whereName,columnName,rowId,value)
    });

    $(document).on('click', '.dt_action', function(e) {
        e.preventDefault();
        var $button = $(this);
        var action = $button.data('action') ??  $('#bulkActions').val();
        var id = $button.data('id');
        var table ;

        switch (action) {
            case 'edit':
                window.location.href = '<?= $ajaxUrl.'/edit/' ?>' + id;
                break;
            case 'show':
                table = $('.show').data('table');
                const url = '<?= $ajaxUrl.'/show/' ?>' + id;
                const columns = $(this).data('columns');
                const module = $(this).data('module');
                showRow(url,module,table,columns)
                break;
            case 'delete':
                // Confirmation before deletion
                Swal.fire({
                    title: 'هل أنت متأكد أنك تريد حذف هذه البيانات؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'تأكيد!',
                    cancelButtonText: 'الغاء'
                }).then(result => {
                    if (result.isConfirmed) {
                       table = $('.delete').data('table');
                        const rows = (id === undefined) ? checkedRows : id;
                        deleteRows(table,rows)
                    }
                });
                break;
            case 'activate':
                // Call your function to activate rows

                activateRows(table,'id',checkedRows,'active',1);
                break;
            case 'deactivate':
                // Call your function to activate rows
                activateRows(table,'id',checkedRows,'active',0);
                break;
            default:
                console.log('Action: ' + action + ', ID: ' + id);
                break;
        }
    });

/////////////////////// datatable + inner events ///////////////////////

    function initialize_datatable(){
        // First AJAX call to get column definitions
        $.ajax({
            "url": "<?= $ajaxUrl ?>",
            "type": "POST",
            "dataType": "json",
            "success": function(data) {
                console.log(data)
                var thead = $('#jq-table thead');
                var tr = $('<tr>');
                // Add checkbox column
                tr.append('<th><input type="checkbox" id="select-all"></th>');
                $.each(data.columns, function(i, column) {
                    var title = column.title ? column.title : column.data;
                    tr.append('<th>'+ title +'</th>');
                });
                thead.append(tr);
                initDataTable(data.columns);
            },
            "error": function(e) {
                console.log(e.responseText);
            }
        });
    }

    function initDataTable(columns) {
        dt_table = $('#jq-table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            stateSave:true,
            lengthMenu: [ [10, 25, 50, 100, 200], [10, 25, 50, 100, 200] ],
            language : {
                "infoFiltered": "" // Set to empty to hide the filtered message part
            },
            stateDuration: <?= $stateSaveValue ?>, // Set the state duration to 60 seconds (1 minute)
            ajax: {
                "url": "<?= $ajaxUrl ?>",
                "type": "POST",
                "data": function(d) {
                    // Append custom filters to the data object
                    $('.custom-filter').each(function() {
                        var filterName = $(this).attr('name');
                        var filterValue = $(this).val();
                        d[filterName] = filterValue;
                        console.log(d)
                        console.log(filterName)

                    });
                },
            },
            dom: '<"row"<"col-sm-6 d-flex"l><"col-sm-6"Bf>>rtip', // Modify the toolbar layout
            buttons: [
                /*           {
                          title: ' ',
                          text:'<i class="far fa-eye-slash"></i>',
                          extend: 'colvis',
                          collectionLayout: 'fixed columns dark-mode',
                          collectionTitle: 'اخفاء الأعمدة'
                      },
                 {
                          extend: 'excelHtml5',
                          text:  '<i style="" class="fas fa-file-excel"></i> ',
                          title: ' ',
                          exportOptions: {
                              columns: ':visible:not(:first-child, :last-child)', // Exclude the first and last columns from printing
                              modifier: {
                                  selected: true,
                                  search: 'none'
                              }
                          },
                          customize: function(xlsx) {
                              var sheet = xlsx.xl.worksheets['sheet1.xml'];
                              var headerRow = $('row', sheet).eq(1); // Get the second row (header row)

                              // Loop over the cells in the header row
                              $('c', headerRow).each(function() {
                                  var cell = $(this);
                                  // Set the background color and text color for the cell
                                  cell.attr('s', '52').attr('s','10');
                              });
                          },
                          customizeData: function(data) {
                              // Reverse the columns in each row of the data array
                              for (var i = 0; i < data.body.length; i++) {
                                  data.body[i].reverse();
                              }
                              // Reverse the header row
                              data.header.reverse();
                          }

                      },
                      {
                          extend: 'copyHtml5',
                          text:'<i class="far fa-copy"></i>',
                          exportOptions: {
                              columns: ':visible:not(:first-child, :last-child)', // Exclude the first and last columns from printing
                              modifier: {
                                  selected: true,
                                  search: 'none'
                              }
                          }
                      },*/
                {
                    extend: 'print',
                    title: ' ',
                    text:'<i class="fa fa-print"></i> ',
                    customize: function (win) {
                        // Get the table element
                        var dt_table = $(win.document.body).find('table').eq(0);
                        // Add custom styles to the table
                        dt_table.find('thead th').css({
                            'background-color': '#34495e',
                            'color': '#fff'
                        });
                        console.log(dt_table)
                    },
                    exportOptions: {
                        columns: ':visible:not(:first-child, :last-child)', // Exclude the first and last columns from printing
                        modifier: {
                            selected: true,
                            search: 'none'
                        }
                    }
                },
                {
                    text: 'تحديث',
                    action: function ( e, dt, node, config ) {
                        dt_table.ajax.reload();
                    }
                }
            ],
            columns: [{ // Checkbox column
                "data": null,
                "defaultContent": '',
                "className":'select-checkbox',
                "orderable": false,
                "searchable": false,
                "targets": 0
            }].concat(columns),
            ordering: true,
            order: [],
            select: {
                "style": 'multi',
                "selector": 'td:first-child'
            },
            initComplete: function() {
                // Add the bulk_actions dropdown beside "Show entries" with some spacing
                // Adjust the layout of the search input and bulk_actions dropdown
                $('.dataTables_filter input').addClass('form-control');
            },
        });

        setupEventHandlers(checkedRows);
    }

    function setupEventHandlers(checkedRows) {
        // Handle click on "Select all" control
        $('#select-all').on('click', function(){
            if (dt_table) {
                if (this.checked) {
                    dt_table.rows().select();
                } else {
                    dt_table.rows().deselect();
                }
            }
        });

        // Handle select event
        dt_table.on('select', function(e, dt, type, indexes) {
            if (type === 'row') {
                var data = dt_table.rows(indexes).data().pluck('id');
                $.each(data, function(index, value){
                    checkedRows.push(value);
                });
                console.log(checkedRows);
            }
        });

        // Handle deselect event
        dt_table.on('deselect', function(e, dt, type, indexes) {
            if (type === 'row') {
                var data = dt_table.rows(indexes).data().pluck('id');
                $.each(data, function(index, value){
                    var rowIndex = checkedRows.indexOf(value);
                    if (rowIndex !== -1) {
                        checkedRows.splice(rowIndex, 1);
                    }
                });
                console.log(checkedRows);
            }
        });

    }

    $(document).on('change', '.custom-filter', function() {
        dt_table.ajax.reload();
    });
</script>
<style>
    #jq-table_filter{
        display: flex;
        align-items: center;
    }

</style>
<?php $this->endSection(); ?>
