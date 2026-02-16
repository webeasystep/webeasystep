<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <a href="<?= ADMIN_URL . 'users/add' ?>" class="btn btn-primary mb-1 add">
                <i class="fas fa-plus"></i><?= lang("Admin.add_data") ?>
            </a>
        </div>

    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <div class="table-responsive">
                <div class="input-group " style=" width: 202px;  margin: 0px 15px;max-width: 100%">
                    <select name="bulkActions" id="bulkActions" aria-controls="bulkActions" class="custom-select">
                        <option value="0">--اختر--</option>
                        <option value="activate">تفعيل</option>
                        <option value="deactivate">الغاء التفعيل</option>
                        <option value="delete">حذف</option>
                        </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary dt_action" type="button">تنفيذ</button>
                        </div>
                    </div>
                <table class="table table-bordered table-striped" id="jq-table" width="100%">
                    <thead></thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Include the Show View here -->
    <?= $this->include('Modules\Users\Views\Admin\show'); ?>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->include('admin_layout/index_js'); ?>
