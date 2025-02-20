<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container light-style flex-grow-1 container-p-y">

    <h4 class="font-weight-bold py-3 mb-4"> الإعدادات</h4>

    <div class="card overflow-hidden">
        <?= $this->include('admin_layout/admin_msg'); ?>
        <div class="row no-gutters row-bordered row-border-light">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body p-1">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item active">
                                <a href="#activations_panel" class="nav-link active" data-toggle="list">
                                    <i class="fas fa-cogs"></i> التفعيلات <!-- Replaced with a settings icon -->
                                    <span class="badge bg-primary float-right">6</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#change_numbers_panel" class="nav-link" data-toggle="list">
                                    <i class="fas fa-phone"></i> اعدادات الأرقام <!-- Replaced with a phone icon -->
                                    <span class="badge bg-primary float-right">11</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#social_links_panel" class="nav-link" data-toggle="list">
                                    <i class="fas fa-address-book"></i> بيانات التواصل <!-- Replaced with an address book icon -->
                                    <span class="badge bg-primary float-right">4</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>

            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="activations_panel">
                        <div class="card-body pb-2">
                            <?= $this->include('admin_layout/admin_msg'); ?>
                            <?= form_open_multipart(ADMIN_URL.'settings/index'); ?>
                            <!-- Company Name -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="active_merchant_assign" value="0">
                                    <input type="checkbox" class="custom-control-input" id="active_merchant_assign" name="active_merchant_assign"
                                        <?= setting('App.active_merchant_assign') ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="active_merchant_assign"><?= lang("Settings.active_merchant_assign"); ?></label>
                                </div>
                            </div>

                            <!-- Site Description -->
                            <div class="form-group">
                                <label for="site_desc"  class="form-label"><?= lang("Settings.orders_off_message"); ?></label>
                                <textarea name="orders_off_message"  class="form-control ckeditor " id="orders_off_message"><?= setting('App.orders_off_message')  ?></textarea>
                                    <small class="invalid-feedback"></small>
                            </div>

                            <!-- Submit Button -->
                            <a type="button" class="btn btn-secondary"
                                    href="<?= ADMIN_URL . 'dashboard' ?>"><?= lang("Admin.cancel") ?></a>
                            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
                            <?= form_close(); ?>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="change_numbers_panel">
                        <div class="card-body pb-2">
                            <?= $this->include('admin_layout/admin_msg'); ?>
                            <?= form_open_multipart(ADMIN_URL.'settings/index'); ?>
                            <!-- Company Name -->

                            <div class="form-group">
                                <label for="title" class="form-label"><?= lang("Settings.accept_waiting_duration"); ?>
                                  <span class="text-danger">(<?= lang("Settings.minute") ?>)</span>:</label>
                                <input type="text" class="form-control" name="accept_waiting_duration" id="accept_waiting_duration"
                                       value="<?= setting('App.accept_waiting_duration') ?>">
                            </div>



                            <a type="button" class="btn btn-secondary"
                               href="<?= ADMIN_URL . 'dashboard' ?>"><?= lang("Admin.cancel") ?></a>
                            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
                            <?= form_close(); ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="social_links_panel">
                          <div class="card-body pb-2">
                              <?= $this->include('admin_layout/admin_msg'); ?>
                            <?= form_open_multipart(ADMIN_URL.'settings/index'); ?>

                            <div class="form-group">
                                <label for="facebook" class="form-label">فيس بوك</label>
                                <input name="facebook" type="text" class="form-control"
                                       value="<?= setting('App.facebook')  ?>">
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control" name="phone" id="phone"
                                       value="<?= setting('App.phone')  ?>">
                            </div>
                            <div class="form-group">
                                <label for="mobile" class="form-label">رقم الجوال</label>
                                <input id="mobile" type="text" class="form-control" name="mobile"
                                       value="<?= setting('App.mobile')  ?>">
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea name="address"  class="form-control" id="address" data-i18n="ar,en"
                                          data-ar="<?= setting('App.address') ?>"
                                          data-en="<?= setting('App.address_en')  ?>"  >
                                 </textarea>
                            </div>
                              <a type="button" class="btn btn-secondary"
                                 href="<?= ADMIN_URL . 'dashboard' ?>"><?= lang("Admin.cancel") ?></a>
                              <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
                </div>
         </div>
    </div>

</div>

<style>
    .ui-w-80 {
        width: 80px !important;
        height: auto;
    }

    .account-settings-fileinput {
        position: absolute;
        visibility: hidden;
        width: 1px;
        height: 1px;
        opacity: 0;
    }

</style>

<?= $this->endSection(); ?>

<!-- .javascript section -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
    <script>
        $(document).ready(function () {
        });
    </script>
<?php $this->endSection(); ?>
