<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Sections.parent_id") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('parent_id', $sections, $section->parent_id ?? 0,
                        ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="section_link" class="col-sm-3 col-form-label"><?= lang("Sections.section_link") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="section_link" name="section_link" value="<?= set_value('section_link', $section->section_link ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-3 col-form-label"><?= lang("Sections.title") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $section->title ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="icon" class="col-sm-3 col-form-label"><?= lang("Sections.icon") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="icon" name="icon" value="<?= set_value('icon', !empty($section->icon) ? $section->icon :  "far fa-circle nav-icon") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("Sections.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort', $section->sort ?? 0) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>


            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Sections.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $section->active ?? 0) ? 'checked' : '' ?> >
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>


            <!-- Datepicker for 'updated_at' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Sections.updated_at") ?></label>
                <div class="col-sm-9">
                    <div class="input-group date" id="updated_at" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#updated_at" name="updated_at" value="<?= $section->updated_at  ?? "" ?>"/>
                        <div class="input-group-append" data-target="#updated_at" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Additional fields for edit form -->
            <a  class="btn btn-secondary" href="<?= ADMIN_URL . 'sections' ?>" ><?= lang("Admin.cancel") ?></a>
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
        });
    </script>
<?php $this->endSection(); ?>
