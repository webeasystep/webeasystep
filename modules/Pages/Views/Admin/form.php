<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Pages.parent_id") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('parent_id', $pages, $page->parent_id ?? 0,
                        ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="page_link" class="col-sm-3 col-form-label"><?= lang("Pages.page_link") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="page_link" name="page_link" value="<?= set_value('page_link', $page->page_link ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
            <label for="job_title" class="col-sm-3 col-form-label">العنوان</label>
            <div class="col-sm-9">
                <div class="i18n-input">
                    <input type="text" name="title" data-i18n="ar,en"
                           data-ar="<?= $page->title_ar ??  "" ?>" data-en="<?= $page->title_en ?? "" ?>"
                           id="title" class="form-control">
                </div>
                <small class="invalid-feedback"></small>
            </div>
        </div>

            <div class="form-group row">
                <label for="desc" class="col-sm-3 col-form-label">الوصف</label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                        <textarea name="desc" class="form-control" id="desc" data-i18n="ar,en"
                                  data-ar="<?= $page->desc_ar ??  ""?>"
                                  data-en="<?= $page->desc_en ??  ""?>">
                        </textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>

            <div class="form-group row ">
                <label for="content" class="col-sm-3 col-form-label">العنوان</label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                <textarea name="content"  class="form-control ckeditor " id="content" data-i18n="ar,en"
                          data-ar="<?= $page->content_ar ??  ""?>"
                          data-en="<?= $page->content_en ??  ""?>" ></textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="images" class="col-sm-3 col-form-label"><?= lang("Pages.images") ?></label>
                <div class="col-sm-9">
                    <div class="fireupload" id="dropzone1"  ></div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Pages.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $page->active ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>
            <!-- Switch for 'status' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Pages.show_home") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="show_home" name="show_home" <?= set_value('show_home', $page->show_home ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="show_home"></label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("Pages.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $page->sort ?? 1) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>


            <!-- Additional fields for edit form -->
            <a type="button" class="btn btn-secondary"
                    href="<?= ADMIN_URL . 'pages' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>

    </div>

</div><!-- /.container-fluid -->

<?= $this->endSection(); ?>

<!-- .javascript section -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function () {
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "images[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "gif"],
            files: <?= json_encode($files ?? '[]') ?>
        });
    });
</script>
<?php $this->endSection(); ?>

