<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>

            <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label"><?= lang("Permissions.permission_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="permission_name" name="permission_name"
                           value="<?= set_value('permission_name',$permission->permission_name ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label"><?= lang("Permissions.title") ?></label>
                <div class="col-sm-9">
                    <input type="text" id="title"  class="form-control"  name="title"
                           value="<?= set_value('title',$permission->title ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Additional fields for edit form -->
            <a type="button" class="btn btn-secondary"
                    href="<?= ADMIN_URL . 'permissions' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function () {
    });
</script>
<?php $this->endSection(); ?>

