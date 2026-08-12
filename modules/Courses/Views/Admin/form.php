<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart('', ['id' => 'course-form']); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <!-- الحقول الأساسية للدورة -->
            <div class="form-group row">
                <label for="course_title" class="col-sm-3 col-form-label"><?= lang("Courses.course_title") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="course_title"
                           value="<?= set_value('course_title', $course->course_title ?? "") ?>"
                           id="course_title" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="course_desc" class="col-sm-3 col-form-label"><?= lang("Courses.course_desc") ?></label>
                <div class="col-sm-9">
                    <textarea name="course_desc" class="form-control"
                              id="course_desc"><?= set_value('course_desc', $course->course_desc ?? "") ?></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="short_desc" class="col-sm-3 col-form-label"><?= lang("Courses.short_desc") ?></label>
                <div class="col-sm-9">
                    <textarea name="short_desc" class="form-control" rows="3"
                              id="short_desc" placeholder="<?= lang("Courses.short_desc_placeholder") ?>"><?= set_value('short_desc', $course->short_desc ?? "") ?></textarea>
                    <small class="form-text text-muted"><?= lang("Courses.short_desc_help") ?></small>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-sm-3 col-form-label"><?= lang("Courses.slug") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="slug"
                           value="<?= set_value('slug', $course->slug ?? "") ?>"
                           id="slug" class="form-control"
                           placeholder="<?= lang("Courses.slug_placeholder") ?>">
                    <small class="form-text text-muted"><?= lang("Courses.slug_help") ?></small>
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
                    <input type="text" class="form-control" id="sort" name="sort"
                           value="<?= set_value('sort', $course->sort ?? 1) ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="course_price" class="col-sm-3 col-form-label"><?= lang("Courses.course_price") ?></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" name="course_price" step="0.01" min="0"
                               value="<?= set_value('course_price', $course->course_price ?? "0") ?>"
                               id="course_price" class="form-control">
                        <span class="input-group-text">$</span>
                    </div>
                    <small class="form-text text-muted">اتركه 0 إذا كانت الدورة مجانية</small>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="instructor_id" class="col-sm-3 col-form-label">المحاضر</label>
                <div class="col-sm-9">
                    <?= form_dropdown('instructor_id', $instructors ?? ['' => 'بدون تعيين'], set_value('instructor_id', $course->instructor_id ?? ''), ['class' => 'form-control', 'id' => 'instructor_id']) ?>
                    <small class="form-text text-muted">اربط الدورة بمحاضر حتى تظهر له داخل لوحة التحكم الخاصة به.</small>
                </div>
            </div>


            <div class="form-group row">
                <label for="intro_video_id" class="col-sm-3 col-form-label"><?= lang("Courses.intro_video_id") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="intro_video_id"
                           value="<?= set_value('intro_video_id', $course->intro_video_id ?? "") ?>"
                           id="intro_video_id" class="form-control"
                           placeholder="<?= lang("Courses.intro_video_id_placeholder") ?>">
                    <small class="form-text text-muted"><?= lang("Courses.intro_video_id_help") ?></small>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="telegram_link" class="col-sm-3 col-form-label">رابط تيليجرام (دعم الطلبة)</label>
                <div class="col-sm-9">
                    <input type="url" name="telegram_link"
                           value="<?= set_value('telegram_link', $course->telegram_link ?? "") ?>"
                           id="telegram_link" class="form-control"
                           placeholder="https://t.me/...">
                    <small class="form-text text-muted">رابط مجموعة أو قناة تيليجرام الخاصة بدعم طلبة هذه المادة، سيظهر في تفاصيل الكورس.</small>
                    <small class="invalid-feedback"></small>
                </div>
            </div>


            <!-- Switch for 'is_free' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Courses.is_free") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input"
                               id="is_free" name="is_free"
                            <?= set_value('is_free', $course->is_free ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is_free"></label>
                    </div>
                </div>
            </div>

            <!-- Switch for 'waiting_list' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Courses.waiting_list") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input"
                               id="waiting_list" name="waiting_list"
                            <?= set_value('waiting_list', $course->waiting_list ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="waiting_list"></label>
                    </div>
                    <small class="form-text text-muted"><?= lang("Courses.waiting_list_help") ?></small>
                </div>
            </div>

            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Courses.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input"
                               id="active" name="active"
                            <?= set_value('active', $course->active ?? 1) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"></label>
                    </div>
                    <small class="form-text text-muted"><?= lang("Courses.active_help") ?></small>
                </div>
            </div>

            <!-- Switch for 'is_open' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">مفتوح للحجز</label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input"
                               id="is_open" name="is_open"
                            <?= set_value('is_open', $course->is_open ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is_open"></label>
                    </div>
                    <small class="form-text text-muted">تفعيل هذا الخيار سيظهر علامة "مفتوح للحجز" في الموقع، بينما إيقافه سيظهر "مغلق الحجز".</small>
                </div>
            </div>



            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'courses' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="submit-btn" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>




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


