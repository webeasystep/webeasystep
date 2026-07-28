<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>


            <div class="form-group row">
                <label for="username" class="col-sm-3 col-form-label"><?= lang("Users.username") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="username" name="username" value="<?= esc($user->username ?? '') ?>" <?= !empty($user->id) ? 'readonly' : '' ?> required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="full_name" class="col-sm-3 col-form-label"><?= lang("Users.full_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= esc($user->full_name ?? '') ?>" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label"><?= lang("Users.email") ?></label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email" value="<?= esc(!empty($user->id) ? $user->email : '') ?>" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="mobile" class="col-sm-3 col-form-label"><?= lang("Users.mobile") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?= esc(!empty($user->id) ? $user->mobile : '') ?>" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="user_type" class="col-sm-3 col-form-label"><?= lang("Users.user_type") ?></label>
                <div class="col-sm-9">
                    <select class="form-control" id="user_type" name="user_type" required>
                        <option value="1" <?= (string) set_value('user_type', $user->user_type ?? 1) === '1' ? 'selected' : '' ?>>طالب</option>
                        <option value="2" <?= (string) set_value('user_type', $user->user_type ?? 1) === '2' ? 'selected' : '' ?>>محاضر</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label"><?= lang("Merchants.password") ?></label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" id="password" name="password" value="<?= set_value('password') ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Users.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $user->active ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>

            <!-- Switch for 'status' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Users.status") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="status" name="status" <?= set_value('status', $user->status ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="status"></label>
                    </div>
                </div>
            </div>



            <!-- Datepicker for 'updated_at' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Users.updated_at") ?></label>
                <div class="col-sm-9">
                    <div class="input-group date" id="updated_at" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#updated_at" name="updated_at" value="<?= $user->updated_at ?>"/>
                        <div class="input-group-append" data-target="#updated_at" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Additional fields for edit form -->
            <button type="button" class="btn btn-secondary"
                    href="<?= ADMIN_URL . 'users' ?>"><?= lang("Admin.cancel") ?></button>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

 <!-- .javascript section -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<?php $this->endSection(); ?>
