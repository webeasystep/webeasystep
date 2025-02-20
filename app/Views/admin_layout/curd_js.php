
    <script src="https://unpkg.com/sortablejs@1.13.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('admin/plugins/fireuploader/fireupload.css') ?>">
    <script  type="text/javascript"  src="<?= base_url('admin/plugins/fireuploader/fireupload.js') ?>"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/translations/ar.js"></script>
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

