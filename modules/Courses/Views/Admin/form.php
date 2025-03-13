<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart('', ['id' => 'course-form']); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>
            <!-- حقل مخفي سيحتوي على الـ JSON النهائي -->
            <input type="hidden" name="course_structure" id="course_structure" value="">

            <!-- الحقول الأساسية للدورة -->
            <div class="form-group row">
                <label for="course_name" class="col-sm-3 col-form-label"><?= lang("Courses.course_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="course_name"
                           value="<?= set_value('course_name', $course->course_name ?? "") ?>"
                           id="course_name" class="form-control">
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
                <label for="price" class="col-sm-3 col-form-label"><?= lang("Courses.price") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="price"
                           value="<?= set_value('price', $course->price ?? "") ?>"
                           id="price" class="form-control">
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

            <!-- أقسام الدورة (Course Sections) -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Courses.course_structure") ?></label>
                <div class="col-sm-9">
                    <div id="course-structure-container">
                        <table class="table table-bordered" id="structure-table">
                            <thead>
                            <tr>
                                <th colspan="8">
                                    Sections
                                    <button type="button" class="btn btn-sm btn-primary float-right" onclick="addSection()">
                                        <i class="fas fa-plus"></i> Add Section
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="sections-tbody">
                            <!-- سيُنشأ الصف ديناميكياً -->
                            </tbody>
                        </table>
                    </div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'courses' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="submit-btn" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- قسم Section Template (مخفي) -->
<table style="display: none;">
    <tbody>
    <tr class="section-template" style="display: none;">
        <td colspan="8">
            <div class="section-wrapper">
                <div class="card mb-3">
                    <div class="card-header">
                        Section
                        <button type="button" class="btn btn-sm btn-danger float-right" onclick="removeRow(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- حقل مخفي لـ section_id -->
                            <input type="hidden" class="section-id" value="0">
                            <!-- section_title -->
                            <div class="col-md-3">
                                <input type="text" class="form-control section-title" placeholder="Section Title">
                            </div>
                            <!-- sort -->
                            <div class="col-md-2">
                                <input type="number" class="form-control section-sort" placeholder="Sort" value="1">
                            </div>
                            <!-- active -->
                            <div class="col-md-2">
                                <select class="form-control section-active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <button type="button" class="btn btn-sm btn-success" onclick="addVideo(this)">
                                    <i class="fas fa-plus"></i> Add Video
                                </button>
                            </div>
                        </div>
                        <div class="videos-container mt-3">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Video Title</th>
                                    <th>Description</th>
                                    <th>Video ID</th>
                                    <th>Duration</th>
                                    <th>Preview</th>
                                    <th>Sort</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- سيُنشأ الفيديو ديناميكياً -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<!-- فيديو Video Template (مخفي) -->
<table style="display: none;">
    <tbody>
    <tr class="video-template" style="display: none;">
        <!-- حقل مخفي لـ id (auto increment) -->
        <input type="hidden" class="video-auto-id" value="0">

        <td><input type="text" class="form-control video-title" placeholder="Video Title"></td>
        <td><input type="text" class="form-control video-desc" placeholder="Description"></td>
        <td><input type="text" class="form-control video-id" placeholder="Video ID"></td>
        <td><input type="text" class="form-control video-duration" placeholder="Duration (e.g. 10:45)"></td>
        <!-- is_preview -->
        <td>
            <select class="form-control video-is-preview">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </td>
        <!-- sort -->
        <td><input type="number" class="form-control video-sort" placeholder="Sort" value="1"></td>
        <!-- active -->
        <td>
            <select class="form-control video-active">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>

<?= $this->endSection(); ?>

<style>
    /* لإظهار الصفوف بشكل صحيح بعد النسخ من الـ template */
    #sections-tbody tr:not(.section-template) {
        display: table-row !important;
    }
    .videos-container tr:not(.video-template) {
        display: table-row !important;
    }
    .section-template td,
    .video-template td {
        border: none !important;
        padding: 0 !important;
    }
</style>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(document).ready(function() {
        // مثال لتهيئة FireUploader
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "image[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "webp"],
            files: <?= json_encode($files ?? []) ?>
        });

        // لو كان لدينا بيانات سابقة (في حالة التعديل)
        <?php if (isset($course->course_structure) && !empty($course->course_structure)): ?>
        const existingData = <?= json_encode($course->course_structure, JSON_UNESCAPED_UNICODE) ?>;
        populateStructure(existingData);
        <?php endif; ?>

        // زر الحفظ
        $('#submit-btn').click(function(e) {
            e.preventDefault();
            const structureData = prepareData(); // نجهز الـ JSON المطلوب
            $('#course_structure').val(JSON.stringify(structureData)); // نخزن الـ JSON في الحقل المخفي
            $('#course-form').submit(); // نرسل النموذج
        });
    });

    /**
     * إضافة قسم جديد
     */
    function addSection() {
        const $newSection = $('.section-template').clone();
        $newSection.removeClass('section-template').show();
        $newSection.find('.videos-container tbody').empty();
        $('#sections-tbody').append($newSection);
    }

    /**
     * إضافة فيديو داخل قسم
     */
    function addVideo(btn) {
        const $videoRow = $('.video-template').clone();
        $videoRow.removeClass('video-template').show();
        $(btn).closest('.card-body').find('.videos-container tbody').append($videoRow);
    }

    /**
     * حذف صف (قسم أو فيديو)
     */
    function removeRow(element) {
        $(element).closest('tr').remove();
    }

    /**
     * جمع البيانات وتحويلها إلى المصفوفة المطلوبة
     * الشكل النهائي:
     * [
     *   {
     *     "section_id": 1,
     *     "section_title": "...",
     *     "sort": 1,
     *     "active": 1,
     *     "videos": [
     *       {
     *         "id": 1,
     *         "video_id": "...",
     *         "is_preview": 0,
     *         "video_desc": "...",
     *         "video_title": "...",
     *         "video_duration": "...",
     *         "sort": 1,
     *         "active": 1
     *       },
     *       ...
     *     ]
     *   },
     *   ...
     * ]
     */
    function prepareData() {
        let finalArray = [];

        $('#sections-tbody > tr').each(function(sectionIndex) {
            const section = {
                "section_id": sectionIndex + 1, // أو اقرأ من الحقل hidden إن أردت الحفاظ على نفس القيم
                "section_title": $(this).find('.section-title').val() || `Section ${sectionIndex+1}`,
                "sort": parseInt($(this).find('.section-sort').val()) || 1,
                "active": parseInt($(this).find('.section-active').val()) || 1,
                "videos": []
            };

            // في حال كنت تريد استخدام القيمة المخفية في الواجهة بدلاً من sectionIndex+1:
            // let userSectionId = parseInt($(this).find('.section-id').val()) || (sectionIndex + 1);
            // section.section_id = userSectionId;

            // نجمع الفيديوهات
            $(this).find('.videos-container tbody > tr').each(function(videoIndex) {
                section.videos.push({
                    "id": videoIndex + 1, // أو خذ من الحقل hidden video-auto-id
                    "video_id": $(this).find('.video-id').val() || '',
                    "is_preview": parseInt($(this).find('.video-is-preview').val()) || 0,
                    "video_desc": $(this).find('.video-desc').val() || '',
                    "video_title": $(this).find('.video-title').val() || '',
                    "video_duration": $(this).find('.video-duration').val() || '00:00',
                    "sort": parseInt($(this).find('.video-sort').val()) || 1,
                    "active": parseInt($(this).find('.video-active').val()) || 1
                });
            });

            finalArray.push(section);
        });

        return finalArray;
    }

    /**
     * دالة تعبئة النموذج من بيانات موجودة (للتحرير)
     * data: عبارة عن مصفوفة أقسام
     */
    function populateStructure(data) {
        $('#sections-tbody').empty();

        if (!Array.isArray(data) || !data.length) {
            return;
        }

        data.forEach((section, sIndex) => {
            const $sectionRow = $('.section-template').clone();
            $sectionRow.removeClass('section-template').show();

            // تعبئة بيانات القسم
            // لو أردت استخدام section.section_id فعلياً:
            // $sectionRow.find('.section-id').val(section.section_id ?? (sIndex+1));
            // أما هنا نستعيض بالـ index:
            $sectionRow.find('.section-id').val(section.section_id ?? (sIndex+1));
            $sectionRow.find('.section-title').val(section.section_title ?? `Section ${sIndex+1}`);
            $sectionRow.find('.section-sort').val(section.sort ?? (sIndex+1));
            $sectionRow.find('.section-active').val((section.active ?? 1).toString());

            // تعبئة الفيديوهات
            const $videoContainer = $sectionRow.find('.videos-container tbody');
            if (Array.isArray(section.videos)) {
                section.videos.forEach((vid, vIndex) => {
                    const $videoRow = $('.video-template').clone();
                    $videoRow.removeClass('video-template').show();
                    // لو أردت الحفاظ على vid.id:
                    // $videoRow.find('.video-auto-id').val(vid.id ?? (vIndex+1));

                    $videoRow.find('.video-id').val(vid.video_id ?? '');
                    $videoRow.find('.video-is-preview').val((vid.is_preview ?? 0).toString());
                    $videoRow.find('.video-desc').val(vid.video_desc ?? '');
                    $videoRow.find('.video-title').val(vid.video_title ?? `Video ${vIndex+1}`);
                    $videoRow.find('.video-duration').val(vid.video_duration ?? '00:00');
                    $videoRow.find('.video-sort').val(vid.sort ?? (vIndex+1));
                    $videoRow.find('.video-active').val((vid.active ?? 1).toString());

                    $videoContainer.append($videoRow);
                });
            }

            $('#sections-tbody').append($sectionRow);
        });
    }
</script>
<?= $this->endSection(); ?>
