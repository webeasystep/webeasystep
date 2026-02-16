
    <script src="https://unpkg.com/sortablejs@1.13.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('admin/plugins/fireuploader/fireupload.css') ?>">
    <script  type="text/javascript"  src="<?= base_url('admin/plugins/fireuploader/fireupload.js') ?>"></script>
    
    <!-- CKEditor 5 Super Build -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/super-build/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/super-build/translations/ar.js"></script>

    <!-- Syntax Highlighting Style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

    <!-- Shim to make Super Build compatible with existing code expecting ClassicEditor -->
    <script>
        // Map CKEDITOR.ClassicEditor to window.ClassicEditor so i18n.js works
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.ClassicEditor) {
            window.ClassicEditor = CKEDITOR.ClassicEditor;
        }
    </script>

    <script  type="text/javascript"  src="<?= base_url('admin/plugins/jquery-multilingual-input/i18n.js') ?>"></script>
    <script>
        $(document).ready(function () {
            console.log("Crud JS loaded")
            // Initialize CKEditor on the first CKEditor input
            $('[data-i18n]').i18n();
            $(".select2").select2({
                allowClear: true,
            });
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            $('.datetimepicker').datetimepicker({ format: 'YYYY-MM-DD'  });
        });
    </script>

