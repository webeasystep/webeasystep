<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <div class="form-group row">
                <label for="user" class="col-lg-4 col-form-label"><?= lang("Groups.title") ?></label>
                <div class="col-lg-6">
                    <input type="text" id="title"  class="form-control"
                           name="title" value="<?= set_value('title', $group->title ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="user" class="col-lg-4 col-form-label"><?= lang("Groups.group_name") ?></label>
                <div class="col-lg-6">
                    <input type="text" id="group_name"  class="form-control"  name="group_name"
                           value="<?= set_value('group_name', $group->group_name ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-lg-4 col-form-label"><?= lang("Groups.description") ?></label>
                <div class="col-lg-6">
                         <textarea name="description"  class="form-control" id="description"  >
                             <?= set_value('description',$group->description ?? "") ?>></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>


            <!-- for multi select -->

            <div class="form-group row">
                <label class="col-lg-4 col-form-label"><?= lang("Groups.permissions") ?></label>
                <div class="col-lg-6">
                    <?= form_multiselect('permissions[]', array_column($permissions,
                        'permission_name', 'permission'), $selectedPermissions ?? [],
                        ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                </div>
            </div>

            <!-- Additional fields for edit form -->
            <a type="button" class="btn btn-secondary"
                    href="<?= ADMIN_URL . 'groups' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
    <script type="text/javascript">
    </script>
<?php $this->endSection(); ?>
