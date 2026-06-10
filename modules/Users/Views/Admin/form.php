<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>


            <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label"><?= lang("Users.username") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="username" name="username" value="<?= $user->username ?>" readonly>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label"><?= lang("Users.full_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $user->full_name ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Gender Radio Buttons -->
   <!--         <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?/*= lang("users.gender") */?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-radio custom-control-inline">
                        <?/*= form_radio('gender', 'male', $user->gender == 'male', ['class' => 'custom-control-input', 'id' => 'male']) */?>
                        <label class="custom-control-label" for="male">Male</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <?/*= form_radio('gender', 'female', $user->gender == 'female', ['class' => 'custom-control-input', 'id' => 'female']) */?>
                        <label class="custom-control-label" for="female">Female</label>
                    </div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>-->


   <!--         <div class="form-group row">
                <label for="phone" class="col-sm-3 col-form-label"><?/*= lang("Users.mobile") */?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?/*= $user->mobile */?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>-->

            <div class="form-group row ">
                <label for="address" class="col-sm-3 col-form-label"><?= lang('Users.address') ?></label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                <textarea name="address"  class="form-control ckeditor " id="address" data-i18n="ar,en"
                          data-ar="عنوان عربي"
                          data-en="ENGLISH ADDRESS" ></textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label for="address2" class="col-sm-3 col-form-label"><?= lang('Users.address') ?></label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                        <textarea name="address2" class="form-control" id="address2" data-i18n="ar,en" data-ar="عنوان عربي" data-en="ENGLISH ADDRESS">
                        </textarea>
                        <small class="invalid-feedback"></small>
                    </div>
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
