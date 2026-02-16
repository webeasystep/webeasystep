<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <a href="<?= ADMIN_URL . 'articles/add' ?>" class="btn btn-primary mb-1 add">
                <i class="fas fa-plus"></i><?= lang("Admin.add_data") ?>
            </a>

        </div>

    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="jq-table" width="100%">
                    <thead></thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Include the Show View here -->
    <?= $this->include('Modules\Articles\Views\Admin\show'); ?>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/index_js'); ?>
<script type="text/javascript">
    $(document).ready(function() {

        $('.off_mode').on("click", function (e) {
            var off_mode = $(this).val();
            console.log(off_mode)

            $.ajax({
                type: "POST",
                url: "<?php echo site_url(ADMIN_URL . '/articles/bulk_merchant_off_mode');?>",
                data: {off_mode},
                dataType: "json",
                success: function (msg) {
                    if (msg.status === 200) {
                        dt_table.ajax.reload();
                        toastr.success(msg.html, 'تم بنجاح', {allowHtml: true});
                    } else {
                        console.log(msg.html)
                        toastr.error(msg.html, 'خطأ');
                    }
                }
            });


        });
    });
</script>
<?php $this->endSection(); ?>


