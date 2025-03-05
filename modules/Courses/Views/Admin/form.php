<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart('', ['id' => 'course-form']); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>
            <input type="hidden" name="course_structure" id="course_structure" value="">

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
            <!-- Course Structure Section -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Courses.course_structure") ?></label>
                <div class="col-sm-9">
                    <div id="course-structure-container">
                        <table class="table table-bordered" id="structure-table">
                            <thead>
                            <tr>
                                <th colspan="5">
                                    Sections
                                    <button type="button" class="btn btn-sm btn-primary float-right" onclick="addSection()">
                                        <i class="fas fa-plus"></i> Add Section
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="sections-tbody">
                            <!-- Sections will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'courses' ?>"><?= lang("Admin.cancel") ?></a>
            <!-- Updated submit button with id -->
            <button type="submit" id="submit-btn" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Section Template (Hidden) -->
<table style="display: none;">
    <tbody>
    <tr class="section-template" style="display: none;">
        <td colspan="5">
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
                        <div class="col-md-4">
                            <input type="text" class="form-control section-title" placeholder="Section Title">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control section-sort" placeholder="Sort Order" value="1">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control section-active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-sm btn-success" onclick="addVideo(this)">
                                <i class="fas fa-plus"></i> Add Video
                            </button>
                        </div>
                    </div>
                    <div class="videos-container mt-3">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Video Name</th>
                                <th>Description</th>
                                <th>Link</th>
                                <th>Sort</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Videos will be added here -->
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

<!-- Video Template (Hidden) -->
<table style="display: none;">
    <tbody>
    <tr class="video-template" style="display: none;">
        <td><input type="text" class="form-control video-name" placeholder="Video Name"></td>
        <td><input type="text" class="form-control video-desc" placeholder="Description"></td>
        <td><input type="url" class="form-control video-link" placeholder="Video URL"></td>
        <td><input type="number" class="form-control video-sort" placeholder="Sort" value="1"></td>
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
    /* Force template visibility */
    #sections-tbody tr:not(.section-template) {
        display: table-row !important;
    }

    /* Video row styling */
    .videos-container tr:not(.video-template) {
        display: table-row !important;
    }

    /* Remove template borders */
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
        // Your uploader initialization...
        var uploader1 = new FireUploader({
            dropzoneId: 'dropzone1',
            inputName: "image[]",
            multipleFiles: true,
            allowedExtensions: ["jpg", "png", "webp"],
            files: <?= json_encode($files ?? '[]') ?>
        });

        // In the document ready section
        <?php if(isset($course->course_structure) && !empty($course->course_structure)): ?>
        const existingData = <?= json_encode($course->course_structure ?? []) ?>;
        populateStructure(existingData);
        <?php endif; ?>

        // Bind click event to submit button
        $('#submit-btn').click(function(e) {
            e.preventDefault(); // Prevent default form submission
            const structureData = prepareData(); // Prepare JSON structure data
            $('#course_structure').val(JSON.stringify(structureData)); // Set the hidden input value
            // Option 1: Submit the form programmatically
            $('#course-form').submit();
        });
    });

    // Update addSection function
    function addSection() {
        const $newSection = $('.section-template').clone();
        $newSection.removeClass('section-template').show();
        $newSection.find('.videos-container tbody').empty();
        $('#sections-tbody').append($newSection);
    }

    // Update addVideo function
    function addVideo(btn) {
        const $videoRow = $('.video-template').clone();
        $videoRow.removeClass('video-template').show();
        $(btn).closest('.card-body').find('.videos-container tbody').append($videoRow);
    }

    function removeRow(element) {
        $(element).closest('tr').remove();
    }

    function prepareData() {
        const structure = { sections: [] };

        $('#sections-tbody > tr').each(function(sectionIndex) {
            const section = {
                section_id: sectionIndex + 1,
                title: $(this).find('.section-title').val(),
                sort: parseInt($(this).find('.section-sort').val()) || 1,
                active: parseInt($(this).find('.section-active').val()),
                videos: []
            };

            $(this).find('.videos-container tbody > tr').each(function(videoIndex) {
                section.videos.push({
                    video_id: videoIndex + 1,
                    video_name: $(this).find('.video-name').val(),
                    video_desc: $(this).find('.video-desc').val(),
                    video_link: $(this).find('.video-link').val(),
                    sort: parseInt($(this).find('.video-sort').val()) || 1,
                    active: parseInt($(this).find('.video-active').val())
                });
            });

            structure.sections.push(section);
        });

        return structure;
    }

    // Replace populateStructure with this version
    function populateStructure(data) {
        $('#sections-tbody').empty(); // Clear existing content

        if(data?.sections?.length) {
            data.sections.forEach((section, sectionIndex) => {
                // Create new section
                const $sectionRow = $('.section-template').clone();
                $sectionRow.removeClass('section-template').show();

                // Set section values
                $sectionRow.find('.section-title').val(section.title || `Section ${sectionIndex + 1}`);
                $sectionRow.find('.section-sort').val(section.sort || (sectionIndex + 1));
                $sectionRow.find('.section-active').val(section.active?.toString() || '1');

                // Handle videos
                const $videoContainer = $sectionRow.find('.videos-container tbody');
                if(section.videos?.length) {
                    section.videos.forEach((video, videoIndex) => {
                        const $videoRow = $('.video-template').clone();
                        $videoRow.removeClass('video-template').show();

                        // Set video values
                        $videoRow.find('.video-name').val(video.video_name || `Video ${videoIndex + 1}`);
                        $videoRow.find('.video-desc').val(video.video_desc || '');
                        $videoRow.find('.video-link').val(video.video_link || '#');
                        $videoRow.find('.video-sort').val(video.sort || (videoIndex + 1));
                        $videoRow.find('.video-active').val(video.active?.toString() || '1');

                        $videoContainer.append($videoRow);
                    });
                }

                $('#sections-tbody').append($sectionRow);
            });
        }
    }
</script>
<?= $this->endSection(); ?>
