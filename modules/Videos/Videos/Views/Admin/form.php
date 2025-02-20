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
                <label for="job_title" class="col-sm-3 col-form-label">العنوان</label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                        <input type="text" name="title" data-i18n="ar,en"
                               data-ar="<?= set_value('title_ar', $video['title_ar'] ?? "")  ?>"
                               data-en="<?= set_value('title_en', $video['title_en'] ?? "")  ?>"
                               id="title" class="form-control">
                    </div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="desc" class="col-sm-3 col-form-label">الوصف</label>
                <div class="col-sm-9">
                    <div class="i18n-input">
            <textarea name="desc" rows="3" class="form-control summernote" id="desc" data-i18n="ar,en"
                      data-ar="<?= set_value('desc_ar', $video['desc_ar'] ?? "") ?>"
                      data-en="<?= set_value('desc_en', $video['desc_en'] ?? "") ?>"></textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label for="video_url" class="col-sm-3 col-form-label">رابط اليوتيوب</label>
                <div class="col-sm-9">
                    <input type="text" name="video_url"
                           value="<?= set_value('video_url', $video['video_url'] ?? "") ?>"
                           id="video_url" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Videos.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $video['active'] ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>
            <!--  add rows  of Videos features here !-->

            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("Videos.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $video['sort'] ?? 1) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>


            <!-- Additional fields for edit form -->
            <a  class="btn btn-secondary" href="<?= ADMIN_URL . 'videos' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>
<!-- Script -->
<!-- .javascript section -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function () {

        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "images[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "webp"],
            files: <?= json_encode($files ?? '[]') ?>
        });
    });
</script>
<?php $this->endSection(); ?>


