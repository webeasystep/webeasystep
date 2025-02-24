<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <div class="form-group row">
                <label for="title" class="col-sm-3 col-form-label"><?= lang("Plans.title") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="title" value="<?= set_value('title', $plan->title ?? "") ?>" id="title" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="price" class="col-sm-3 col-form-label"><?= lang("Plans.price") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="price" value="<?= set_value('price', $plan->price ?? "") ?>" id="price" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="duration_days" class="col-sm-3 col-form-label"><?= lang("Plans.duration_days") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="duration_days" value="<?= set_value('duration_days', $plan->duration_days ?? "") ?>" id="duration_days" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'plans' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<?php $this->endSection(); ?>
