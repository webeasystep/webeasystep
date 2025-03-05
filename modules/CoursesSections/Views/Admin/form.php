<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <div class="form-group row">
                <label for="section_name" class="col-sm-3 col-form-label"><?= lang("CoursesSections.section_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="section_name" value="<?= set_value('section_name', $section->section_name ?? "") ?>" id="section_name" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="section_desc" class="col-sm-3 col-form-label"><?= lang("CoursesSections.section_desc") ?></label>
                <div class="col-sm-9">
                    <textarea name="section_desc" class="form-control" id="section_desc"><?= set_value('section_desc', $section->section_desc ?? "") ?></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("CoursesSections.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $section->sort ?? 0) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'courses_sections' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<?php $this->endSection(); ?>
