<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="site-section">
    <div class="container">
        <!-- Course Navigation -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('courses') ?>">الكورسات</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('courses/course_details/' . $course->slug) ?>"><?= esc($course->course_title) ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($unit->unit_name) ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Unit Header -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0"><?= esc($unit->unit_name) ?></h2>
                        <div class="unit-status">
                            <?php if ($isCompleted): ?>
                                <span class="badge badge-success"><i class="fas fa-check"></i> مكتمل</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> جاري التعلم</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Video Player -->
                <?php if ($unit->video_id): ?>
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <div style="position: relative; padding-top: 56.25%;">
                            <iframe
                                src="https://iframe.mediadelivery.net/embed/495222/<?= $unit->video_id ?>?autoplay=false"
                                loading="lazy"
                                style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen="true"
                                id="unit-video">
                            </iframe>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Unit Description -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">وصف الوحدة</h5>
                    </div>
                    <div class="card-body">
                        <?= nl2br(esc($unit->unit_desc)) ?>
                    </div>
                </div>

                <!-- Unit Quizzes -->
                <?php if (!empty($quizzes)): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">الاختبارات المرتبطة</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($quizzes as $quiz): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= esc($quiz->quiz_title) ?></h6>
                                        <p class="card-text text-muted small"><?= esc(substr($quiz->quiz_desc, 0, 100)) ?><?= strlen($quiz->quiz_desc) > 100 ? '...' : '' ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i> <?= $quiz->time_limit ?> دقيقة
                                                </small>
                                            </div>
                                            <a href="<?= base_url('quizzes/take/' . $quiz->id) ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-play"></i> بدء الاختبار
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Unit Navigation -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <?php if ($prevUnit): ?>
                                    <a href="<?= base_url('units/view/' . $prevUnit->id) ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-right"></i> الوحدة السابقة
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-6 text-left">
                                <?php if ($nextUnit): ?>
                                    <a href="<?= base_url('units/view/' . $nextUnit->id) ?>" class="btn btn-primary">
                                        الوحدة التالية <i class="fas fa-arrow-left"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!$isCompleted): ?>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button id="mark-complete-btn" class="btn btn-success">
                                    <i class="fas fa-check"></i> تحديد كمكتمل
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Progress -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">تقدم الكورس</h6>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" style="width: <?= $courseCompletion ?>%" aria-valuenow="<?= $courseCompletion ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted"><?= $courseCompletion ?>% مكتمل</small>
                    </div>
                </div>

                <!-- Unit Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">معلومات الوحدة</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>القسم:</strong> <?= esc($section->section_name) ?>
                            </li>
                            <?php if ($unit->video_duration): ?>
                            <li class="mb-2">
                                <strong>المدة:</strong> <?= esc($unit->video_duration) ?>
                            </li>
                            <?php endif; ?>
                            <li class="mb-2">
                                <strong>النوع:</strong>
                                <?php if ($unit->is_free): ?>
                                    <span class="badge badge-info">معاينة مجانية</span>
                                <?php else: ?>
                                    <span class="badge badge-primary">محتوى مدفوع</span>
                                <?php endif; ?>
                            </li>
                            <?php if (!empty($quizzes)): ?>
                            <li class="mb-2">
                                <strong>الاختبارات:</strong> <?= count($quizzes) ?> اختبار
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Course Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">معلومات الكورس</h6>
                    </div>
                    <div class="card-body">
                        <h6><?= esc($course->course_title) ?></h6>
                        <p class="text-muted small"><?= esc(substr($course->course_desc, 0, 150)) ?><?= strlen($course->course_desc) > 150 ? '...' : '' ?></p>
                        <a href="<?= base_url('courses/course_details/' . $course->slug) ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-book"></i> عرض الكورس
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Mark unit as complete
    $('#mark-complete-btn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري التحديث...');

        $.post('<?= base_url('units/mark-complete') ?>', {
            unit_id: <?= $unit->id ?>,
            course_id: <?= $course->id ?>,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                btn.removeClass('btn-success').addClass('btn-outline-success')
                   .html('<i class="fas fa-check"></i> مكتمل')
                   .prop('disabled', true);

                // Update progress bar if provided
                if (response.course_completion) {
                    $('.progress-bar').css('width', response.course_completion + '%')
                                     .attr('aria-valuenow', response.course_completion);
                    $('.progress').next('small').text(response.course_completion + '% مكتمل');
                }

                // Update unit status badge
                $('.unit-status .badge').removeClass('badge-warning')
                                       .addClass('badge-success')
                                       .html('<i class="fas fa-check"></i> مكتمل');

                toastr.success('تم تحديد الوحدة كمكتملة بنجاح');
            } else {
                btn.prop('disabled', false).html('<i class="fas fa-check"></i> تحديد كمكتمل');
                toastr.error(response.message || 'حدث خطأ أثناء تحديث الحالة');
            }
        }).fail(function() {
            btn.prop('disabled', false).html('<i class="fas fa-check"></i> تحديد كمكتمل');
            toastr.error('حدث خطأ في الاتصال');
        });
    });

    // Track video progress (if video exists)
    <?php if ($unit->video_id): ?>
    var video = document.getElementById('unit-video');
    var progressUpdateInterval;

    // Listen for video events (this would need to be adapted based on the video player)
    // This is a basic example - you might need to use the video player's API

    // Update progress every 30 seconds while video is playing
    function startProgressTracking() {
        progressUpdateInterval = setInterval(function() {
            // This would need to be implemented based on your video player's API
            // updateVideoProgress(getCurrentTime(), getDuration());
        }, 30000);
    }

    function stopProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
        }
    }

    function updateVideoProgress(currentTime, duration) {
        if (duration > 0) {
            var progressPercentage = Math.round((currentTime / duration) * 100);

            $.post('<?= base_url('units/update-progress') ?>', {
                unit_id: <?= $unit->id ?>,
                course_id: <?= $course->id ?>,
                progress_percentage: progressPercentage,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            });
        }
    }
    <?php endif; ?>
});
</script>

<?= $this->endSection(); ?>
