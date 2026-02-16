<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>


     <!--       <div class="form-group row">
                <label for="contact_name" class="col-sm-3 col-form-label"><?/*= lang("ContactUs.contact_name") */?></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?/*= set_value('contact_name',$info->contact_name ?? "") */?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>
-->
            <div class="form-group row">
                <label for="contact_mobile" class="col-sm-3 col-form-label"><?= lang("ContactUs.contact_mobile") ?></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" value="<?= set_value('contact_mobile',$info->contact_mobile ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label"><?= lang("ContactUs.contact_subject") ?></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="district_name" name="district_name"
                           value="<?= set_value('contact_subject',$info->contact_subject ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>



            <div class="form-group row">
                <label for="contact_subject" class="col-sm-3 col-form-label"><?= lang("ContactUs.contact_message") ?></label>
                <div class="col-sm-4">
                    <textarea name="contact_message" id="contact_message" class="form-control"><?= set_value('contact_message',$info->contact_message ?? "") ?></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <a type="button" class="btn btn-secondary"
               href="<?= ADMIN_URL . 'contact_us' ?>"><?= lang("Admin.cancel") ?></a>
<!--            <button type="submit" id="" class="btn btn-primary"><?/*= lang("admin.save") */?></button>-->
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>

</script>
<?= $this->endSection(); ?>
