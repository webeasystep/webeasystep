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
            <label for="job_title" class="col-sm-3 col-form-label"><?= lang('Pages.title') ?></label>
            <div class="col-sm-9">
                <input type="text" name="title" id="title" class="form-control" value="<?= set_value('title', $page->title ?? '') ?>">
                <small class="invalid-feedback"></small>
            </div>
        </div>

            <div class="form-group row">
                <label for="desc" class="col-sm-3 col-form-label"><?= lang('Pages.description') ?></label>
                <div class="col-sm-9">
                    <textarea name="desc" class="form-control" id="desc"><?= set_value('desc', $page->desc ?? '') ?></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row ">
                <label for="content" class="col-sm-3 col-form-label"><?= lang('Pages.content') ?></label>
                <div class="col-sm-9">
                    <textarea name="content" class="form-control" id="content"><?= set_value('content', $page->content ?? '') ?></textarea>
                    <small class="invalid-feedback"></small>
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
            allowedExtensions: ["jpg", "jpeg", "png", "webp", "gif"],
            files: <?= json_encode($files ?? '[]') ?>
        });
    });
</script>
<?php $this->endSection(); ?>

