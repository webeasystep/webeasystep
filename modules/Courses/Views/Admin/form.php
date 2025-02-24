<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <div class="form-group row">
                <label for="course_name" class="col-sm-3 col-form-label"><?= lang("Courses.course_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="course_name" value="<?= set_value('course_name', $course->course_name ?? "") ?>" id="course_name" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="course_desc" class="col-sm-3 col-form-label"><?= lang("Courses.course_desc") ?></label>
                <div class="col-sm-9">
                    <textarea name="course_desc" class="form-control" id="course_desc"><?= set_value('course_desc', $course->course_desc ?? "") ?></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="image" class="col-sm-3 col-form-label"><?= lang("Courses.image") ?></label>
                <div class="col-sm-9">
                    <div class="fireupload" id="dropzone1"></div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("Courses.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $course->sort ?? 1) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="price" class="col-sm-3 col-form-label"><?= lang("Courses.price") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="price" value="<?= set_value('price', $course->price ?? "") ?>" id="price" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Switch for 'is_free' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Courses.is_free") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_free" name="is_free" <?= set_value('is_free', $course->is_free ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is_free"></label>
                    </div>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'courses' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function () {
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "image[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "webp"],
            files: <?= json_encode($files ?? '[]') ?>
        });
    });
</script>
<?php $this->endSection(); ?>
