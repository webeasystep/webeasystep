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
                               data-ar="<?= $article->title ??  "" ?>" data-en="<?= $article->title_en ?? "" ?>"
                               id="title" class="form-control">
                    </div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-sm-3 col-form-label">الرابط</label>
                <div class="col-sm-9">
                    <input disabled  type="text" name="title"
                           value="<?= set_value('slug', $group->slug ?? "") ?>"
                           id="slug" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="desc" class="col-sm-3 col-form-label">الوصف</label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                        <textarea name="desc" class="form-control" id="desc" data-i18n="ar,en"
                                  data-ar="<?= $article->description ??  ""?>"
                                  data-en="<?= $article->description ??  ""?>">
                        </textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>

            <div class="form-group row ">
                <label for="content" class="col-sm-3 col-form-label">المحتوى</label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                <textarea name="content"  class="form-control ckeditor " id="content" data-i18n="ar,en"
                          data-ar="<?= $article->content ??  ""?>"
                          data-en="<?= $article->content ??  ""?>" ></textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="images" class="col-sm-3 col-form-label"><?= lang("articles.images") ?></label>
                <div class="col-sm-9">
                    <div class="fireupload" id="dropzone1"  ></div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("articles.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $article->active ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>
            <!-- Switch for 'status' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("articles.show_home") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="show_home" name="show_home" <?= set_value('show_home', $article->show_home ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="show_home"></label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("articles.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $article->sort ?? 1) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Additional fields for edit form -->
            <a  class="btn btn-secondary" href="<?= ADMIN_URL . 'articles' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->
<style>
    #map {
        padding: 0;
        margin: 0;
        height: 300px;
    }
    .lang_selector {
        width: 100px !important;
    }
    select {
        max-width: 250px !important;
    }

</style>

<?= $this->endSection(); ?>
<!-- Script -->
<!-- .javascript section -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function () {
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "image[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "gif"],
            files: <?= json_encode($files ?? '[]') ?>
        });
    });
</script>
<?php $this->endSection(); ?>


