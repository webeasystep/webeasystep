<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <a href="<?= ADMIN_URL . 'permissions/add' ?>" class="btn btn-primary mb-1 add">
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
    <?= $this->include('Modules\Permissions\Views\Admin\show'); ?>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->include('admin_layout/index_js'); ?>
