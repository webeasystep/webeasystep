<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <div class="form-group row">
                <label for="title" class="col-sm-3 col-form-label"><?= lang('Articles.title') ?></label>
                <div class="col-sm-9">
                    <input type="text" name="title" id="title" class="form-control"
                           value="<?= set_value('title', $article->title ?? '') ?>" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-sm-3 col-form-label"><?= lang('Articles.slug') ?></label>
                <div class="col-sm-9">
                    <input type="text" name="slug" id="slug" class="form-control" dir="ltr"
                           value="<?= set_value('slug', $article->slug ?? '') ?>" readonly>
                    <small class="text-muted">يتم إنشاؤه تلقائياً من العنوان</small>
                </div>
            </div>

            <div class="form-group row">
                <label for="meta_description" class="col-sm-3 col-form-label"><?= lang('Articles.meta_description') ?></label>
                <div class="col-sm-9">
                    <textarea name="meta_description" class="form-control" id="meta_description" rows="2"
                              maxlength="160" placeholder="وصف مختصر للمقال (160 حرف كحد أقصى)"><?= set_value('meta_description', $article->meta_description ?? '') ?></textarea>
                    <small class="text-muted">وصف SEO للمقال - يظهر في نتائج محركات البحث</small>
                </div>
            </div>

            <div class="form-group row">
                <label for="meta_tags" class="col-sm-3 col-form-label"><?= lang('Articles.meta_tags') ?></label>
                <div class="col-sm-9">
                    <input type="text" name="meta_tags" class="form-control" id="meta_tags"
                           value="<?= set_value('meta_tags', $article->meta_tags ?? '') ?>"
                           placeholder="كلمات مفتاحية مفصولة بفواصل">
                    <small class="text-muted">مثال: برمجة, تعليم, كورسات</small>
                </div>
            </div>

            <div class="form-group row">
                <label for="content" class="col-sm-3 col-form-label"><?= lang('Articles.content') ?></label>
                <div class="col-sm-9">
                    <div class="i18n-input">
                <textarea name="content"  class="form-control ckeditor " id="content" data-i18n="ar"
                          data-ar="<?= $article->content ??  ""?>"
                          data-en="<?= $article->content ??  ""?>" ></textarea>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="images" class="col-sm-3 col-form-label"><?= lang("Articles.images") ?></label>
                <div class="col-sm-9">
                    <div class="fireupload" id="dropzone1"></div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Articles.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $article->active ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("Articles.sort") ?></label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $article->sort ?? 1) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'articles' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" class="btn btn-primary" id="submitBtn"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function () {
        // FireUploader initialization
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "image[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "gif", "webp"],
            files: <?= json_encode($files ?? []) ?>
        });

        // Update CKEditor content before form submission
        $('form').on('submit', function(e) {
            // For CKEditor 4
            if (typeof CKEDITOR !== 'undefined') {
                for (var instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }
            // For CKEditor 5
            if (typeof ClassicEditor !== 'undefined' && window.editorInstances) {
                window.editorInstances.forEach(function(editor) {
                    var textarea = editor.sourceElement;
                    if (textarea) {
                        textarea.value = editor.getData();
                    }
                });
            }
        });
    });
</script>
<?php $this->endSection(); ?>

