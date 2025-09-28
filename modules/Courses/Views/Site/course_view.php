<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<style>

    .untree_co-section {
        padding-top: 80px;
        padding-bottom: 80px;
    }

    /* Centered Header for the course view page */
    .course-header-wrapper {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 50px auto;
    }
    .course-header-wrapper .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .course-header-wrapper .section-title::after {
        content: "";
        position: absolute;
        bottom: -6px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background-color: #136ad5;
        border-radius: 2px;
    }
    .course-header-wrapper .course-description {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.7;
        margin: 0 auto;
        max-width: 700px;
    }

    /* Sidebar & user info block */
    .sidebar {
        margin-bottom: 30px;
        width: 320px;
        padding-right: 30px;
    }
    .user-info-block {
        background-color: #fff;
        padding: 25px;
        border-radius: 7px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.08);
        border: 1px solid #eee;
        text-align: center; /* Center the content inside the user info block */
    }
    .user-info-block .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 0 auto 15px auto;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px #e0e7ee;
    }
    .user-info-block h3 {
        font-size: 1.4rem;
        color: #343a40;
        margin-bottom: 10px;
    }

    /* Next/Previous buttons styling */
    .nav-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .nav-buttons .btn {
        flex: 0 0 auto;
        white-space: nowrap;
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    /* Accordion for the list of videos/sections */
    .videos-accordion {
        margin-top: 20px;
        max-height: 500px;
        overflow-y: auto;
        direction: rtl;
        text-align: right;
    }
    .videos-accordion .accordion-item {
        border: none;
        margin-bottom: 0;
        overflow: hidden;
        background-color: transparent;
        box-shadow: none;
    }
    .videos-accordion .accordion-header {
        margin-bottom: 0 !important;
    }
    .videos-accordion .accordion-button {
        font-size: 1rem;
        font-weight: 500;
        color: #444;
        background-color: transparent;
        border: none;
        border-radius: 0;
        box-shadow: none;
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        text-align: right;
    }
    .videos-accordion .accordion-button:not(.collapsed) {
        color: #136ad5;
        background-color: #f8f9fa;
        box-shadow: inset 0 -2px #ddd;
    }
    .videos-accordion .accordion-button:focus {
        border-color: transparent;
        box-shadow: none;
    }
    .videos-accordion .accordion-body {
        background-color: #fff;
        padding: 10px 15px;
    }
    .videos-accordion .list-unstyled {
        margin-bottom: 0;
        padding: 0;
    }
    .videos-accordion .list-unstyled li {
        margin-bottom: 6px;
    }
    .videos-accordion .list-unstyled li a {
        display: block;
        padding: 8px 10px;
        border-radius: 5px;
        color: #555;
        font-size: 0.95rem;
        text-decoration: none;
    }
    .videos-accordion .list-unstyled li a:hover,
    .videos-accordion .list-unstyled li a.active-video {
        background-color: #e9f1fd;
        color: #007bff;
        text-decoration: none;
    }

    /* Main Content Column: Video & Description */
    .main-content {
        margin-top: 30px;
    }
    .course-preview-video .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 7px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .course-preview-video .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 7px;
    }
    .video-title-main-video {
        font-size: 1.6rem;
        color: #333;
        margin-bottom: 10px;
        margin-top: 15px !important;
    }
    .course-description-text {
        color: #666;
        line-height: 1.7;
        margin-bottom: 25px;
    }
    .btn.btn-primary.btn-block.mark-complete-button {
        padding: 14px 30px;
        font-size: 1.1rem;
        border-radius: 7px;
        margin-top: 25px;
        font-weight: 600;
        background-color: #007bff;
        border-color: #007bff;
        transition: all 0.3s ease;
    }

    .btn.btn-primary.btn-block.mark-complete-button:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
    }

    /* Quiz Content Styles */
    .quiz-content-area .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }

    .quiz-content-area .card-header {
        border-bottom: none;
        padding: 20px;
    }

    .quiz-content-area .card-body {
        padding: 30px;
    }

    .quiz-stat {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .quiz-stat i {
        width: 20px;
        text-align: center;
    }

    /* Page Content Styles */
    .page-content-area .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }

    .page-content-area .card-header {
        border-bottom: none;
        padding: 20px;
    }

    .page-content-area .card-body {
        padding: 30px;
    }

    .page-content .page-body {
        line-height: 1.8;
        font-size: 16px;
    }

    .page-content .page-body h1,
    .page-content .page-body h2,
    .page-content .page-body h3,
    .page-content .page-body h4,
    .page-content .page-body h5,
    .page-content .page-body h6 {
        margin-top: 25px;
        margin-bottom: 15px;
        color: #333;
    }

    .page-content .page-body p {
        margin-bottom: 15px;
    }

    .page-content .page-body ul,
    .page-content .page-body ol {
        margin-bottom: 15px;
        padding-right: 20px;
    }

    /* Default Content Styles */
    .default-content-area .card {
        border: 2px dashed #dee2e6;
        background-color: #f8f9fa;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .sidebar {
            margin-bottom: 30px;
            padding-right: 0;
            width: 100%;
        }
        .main-content {
            margin-top: 0;
        }
        .user-info-block,
        .videos-accordion .accordion-item {
            border-left: none;
            border-right: none;
            border-radius: 0;
            box-shadow: none;
        }
        .videos-accordion {
            max-height: none;
        }
        .nav-buttons {
            flex-direction: row;
        }
        .nav-buttons .btn {
            margin: 5px 2px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="untree_co-section bg-light">
    <div class="container">
        <!-- Header / Title & Short Description -->
        <div class="course-header-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h2 class="section-title"><?= esc($title) ?></h2>
            <p class="course-description">
                <?= esc($course->course_desc) ?>
            </p>
        </div>

        <div class="row">
            <!-- Sidebar (Progress, Next/Prev, Videos List) -->
            <div class="col-lg-4 sidebar">
                <div class="user-info-block">
                    <!-- Progress Bar -->
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar"
                             style="width: <?= esc($course_progress) ?>%;"
                             aria-valuenow="<?= esc($course_progress) ?>"
                             aria-valuemin="0" aria-valuemax="100">
                            <?= esc($course_progress) ?>%
                        </div>
                    </div>

                    <!-- Previous / Next Buttons -->
                    <div class="nav-buttons">
                        <!-- Previous Button -->
                        <?php if ($prevLessonUrl): ?>
                            <a class="btn btn-outline-secondary prev-btn" href="<?= $prevLessonUrl ?>">السابق</a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary prev-btn" disabled>السابق</button>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($nextLessonUrl): ?>
                            <a class="btn btn-secondary next-btn" href="<?= $nextLessonUrl ?>">التالي</a>
                        <?php else: ?>
                            <button class="btn btn-secondary next-btn" disabled>التالي</button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Accordion of Units & Items -->
                <div class="videos-accordion accordion" id="videoAccordion">
                    <?php foreach ($units as $unit): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= esc($unit->id) ?>">
                                <button class="accordion-button <?= isset($unit->is_open) && $unit->is_open ? '' : 'collapsed' ?>"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapse<?= esc($unit->id) ?>"
                                        aria-expanded="<?= isset($unit->is_open) && $unit->is_open ? 'true' : 'false' ?>"
                                        aria-controls="collapse<?= esc($unit->id) ?>">
                                    <?= esc($unit->unit_name) ?>
                                    <span class="badge badge-secondary ml-2"><?= count($unit->items ?? []) ?> عنصر</span>
                                </button>
                            </h2>
                            <div id="collapse<?= esc($unit->id) ?>"
                                 class="collapse <?= isset($unit->is_open) && $unit->is_open ? 'show' : '' ?>"
                                 aria-labelledby="heading<?= esc($unit->id) ?>"
                                 data-parent="#videoAccordion">
                                <div class="accordion-body">
                                    <ul class="list-unstyled mb-0">
                                        <?php if (isset($unit->items)): ?>
                                            <?php foreach ($unit->items as $item): ?>
                                                <li class="mb-2">
                                                    <a href="<?= site_url('courses/course_view/' . $course->slug . '?video=' . $item->id) ?>"
                                                       class="text-dark text-decoration-none
                                                       <?= $item->id == $current_id ? 'active-video' : '' ?>">
                                                        <?php if ($item->item_type === 'video'): ?>
                                                            <span class="icon-play-circle-o mr-2" style="color:#6c757d;"></span>
                                                        <?php elseif ($item->item_type === 'quiz'): ?>
                                                            <span class="icon-question-circle mr-2" style="color:#28a745;"></span>
                                                        <?php elseif ($item->item_type === 'page'): ?>
                                                            <span class="icon-file-text-o mr-2" style="color:#17a2b8;"></span>
                                                        <?php else: ?>
                                                            <span class="icon-circle mr-2" style="color:#6c757d;"></span>
                                                        <?php endif; ?>

                                                        <!-- Item Title and Duration -->
                                                        <div class="flex-grow-1">
                                                            <span class="item-title"><?= esc($item->title) ?></span>
                                                            <?php
                                                            $metadata = json_decode($item->metadata ?? '{}', true);
                                                            $duration = null;

                                                            // Get duration based on item type
                                                            if ($item->item_type === 'video') {
                                                                $duration = round($metadata['video_duration'] / 60) ?? null;
                                                                if ($duration) {
                                                                    echo '<br><small class="text-muted">(' . esc($duration) . ' دقيقة)</small>';
                                                                }
                                                            } elseif ($item->item_type === 'quiz') {
                                                                echo '<br><small class="text-success">اختبار تفاعلي</small>';
                                                            } elseif ($item->item_type === 'page') {
                                                                echo '<br><small class="text-info">صفحة إضافية</small>';
                                                            }
                                                            ?>
                                                        </div>

                                                        <!-- Completion Status (if available) -->
                                                        <?php if ($item->id == $current_id): ?>
                                                            <i class="fas fa-chevron-left mr-1" style="color: #007bff; font-size: 0.8rem;"></i>
                                                        <?php endif; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div><!-- End .videos-accordion -->
            </div>

            <!-- Main Content (Video Player, Quiz, or Page Content) -->
            <div class="col-lg-8 main-content">
                <!-- Content Title -->
                <h2 class="video-title-main-video mb-2">
                    <?= esc($itemTitle ?? $video_title) ?>
                </h2>

                <!-- Dynamic Content Based on Item Type -->
                <?php

                if (isset($current_item_type)): ?>

                    <?php if ($current_item_type === 'video'): ?>
                        <!-- Video Player -->
                        <div class="course-preview-video">
                            <div class="video-container">
                                <?php if ($video_id): ?>
                                    <iframe
                                            src="https://iframe.mediadelivery.net/embed/<?= $video_library_id ?? '495222' ?>/<?= $video_id ?>?autoplay=false"
                                            loading="lazy"
                                            style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                            allowfullscreen="true">
                                    </iframe>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                        <div class="text-center">
                                            <i class="fas fa-video fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا يوجد فيديو متاح</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Video Description -->
                        <p class="course-description-text">
                            <?= esc($itemDesc ?? $video_desc) ?>
                        </p>

                    <?php elseif ($current_item_type === 'quiz'): ?>
                        <!-- Quiz Content -->
                        <div class="quiz-content-area">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0">
                                        <i class="fas fa-question-circle mr-2"></i>
                                        اختبار تفاعلي
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($quiz_data) && $quiz_data): ?>
                                        <div class="quiz-info mb-4">
                                            <h5><?= esc($quiz_data->quiz_title) ?></h5>
                                            <p class="text-muted"><?= esc($quiz_data->quiz_desc) ?></p>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="quiz-stat">
                                                        <i class="fas fa-clock text-warning"></i>
                                                        <span>المدة: <?= esc($quiz_data->time_limit ?? 'غير محدد') ?> دقيقة</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="quiz-stat">
                                                        <i class="fas fa-percentage text-info"></i>
                                                        <span>النجاح: <?= esc($quiz_data->passing_score ?? '70') ?>%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="quiz-stat">
                                                        <i class="fas fa-redo text-danger"></i>
                                                        <span>المحاولات: <?= esc($quiz_data->user_attempt_count ?? 0) ?>/<?= esc($quiz_data->max_attempts ?? 3) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- User Progress Information -->
                                            <?php if (isset($quiz_data->user_attempt_count) && $quiz_data->user_attempt_count > 0): ?>
                                                <div class="quiz-user-progress mt-3 p-3 bg-light rounded">
                                                    <h6 class="mb-2"><i class="fas fa-chart-line text-primary"></i> تقدمك في هذا الاختبار</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted">أفضل نتيجة:</small>
                                                            <strong class="text-success"><?= esc($quiz_data->user_best_score ?? 0) ?>%</strong>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted">المحاولات المتبقية:</small>
                                                            <strong class="<?= ($quiz_data->remaining_attempts ?? 0) > 0 ? 'text-info' : 'text-danger' ?>">
                                                                <?= esc($quiz_data->remaining_attempts ?? 0) ?>
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="text-center">
                                            <?php if (isset($quiz_data->has_exceeded_attempts) && $quiz_data->has_exceeded_attempts): ?>
                                                <button class="btn btn-secondary btn-lg" disabled>
                                                    <i class="fas fa-ban mr-2"></i>
                                                    تم استنفاد المحاولات المسموحة
                                                </button>
                                                <p class="text-muted mt-2 small">
                                                    لقد استخدمت جميع المحاولات المسموحة (<?= esc($quiz_data->max_attempts ?? 3) ?>) لهذا الاختبار.
                                                    <?php if (isset($quiz_data->user_best_score) && $quiz_data->user_best_score > 0): ?>
                                                        أفضل نتيجة حققتها: <?= esc($quiz_data->user_best_score) ?>%
                                                    <?php endif; ?>
                                                </p>
                                            <?php else: ?>
                                                <button class="btn btn-success btn-lg take-embedded-quiz-btn"
                                                        data-quiz-id="<?= $quiz_data->id ?>"
                                                        data-quiz-title="<?= esc($quiz_data->quiz_title) ?>">
                                                    <i class="fas fa-play mr-2"></i>
                                                    <?php if (isset($quiz_data->user_attempt_count) && $quiz_data->user_attempt_count > 0): ?>
                                                        إعادة المحاولة
                                                    <?php else: ?>
                                                        ابدأ الاختبار
                                                    <?php endif; ?>
                                                </button>
                                                <?php if (isset($quiz_data->remaining_attempts) && $quiz_data->remaining_attempts > 0): ?>
                                                    <p class="text-info mt-2 small">
                                                        <i class="fas fa-info-circle"></i>
                                                        لديك <?= esc($quiz_data->remaining_attempts) ?> محاولة متبقية
                                                    </p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                            <p class="text-muted">الاختبار غير متاح حالياً</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php
                    elseif ($current_item_type === 'page'): ?>
                        <!-- Page Content -->
                        <div class="page-content-area">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h4 class="mb-0">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        صفحة إضافية
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($page_data) && $page_data): ?>
                                        <div class="page-content">
                                            <?php if ($page_data->desc): ?>
                                                <div class="page-description mb-3">
                                                    <p class="text-muted"><?= esc($page_data->desc) ?></p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="page-body">
                                                <?= $page_data->content ?? '<p>لا يوجد محتوى متاح</p>' ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">المحتوى غير متاح حالياً</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Default/Unknown Content Type -->
                        <div class="default-content-area">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-question fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">نوع المحتوى غير مدعوم</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Fallback to original video content -->
                    <div class="course-preview-video">
                        <div class="video-container">
                            <iframe
                                    src="https://iframe.mediadelivery.net/embed/<?= $metadata['video_library_id'] ?? '495222' ?>/<?= $video_id ?>?autoplay=false"
                                    loading="lazy"
                                    style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                    allowfullscreen="true">
                            </iframe>
                        </div>
                    </div>
                    <p class="course-description-text">
                        <?= esc($video_desc) ?>
                    </p>
                <?php endif; ?>

                <!-- Mark as Complete Button -->
                <div class="completion-section mt-4">
                    <?php
                    // Debug logging for current_item
                    error_log('COURSE_VIEW DEBUG - current_item: ' . json_encode($current_item ?? 'NOT_SET'));
                    error_log('COURSE_VIEW DEBUG - current_id: ' . ($current_id ?? 'NOT_SET'));
                    error_log('COURSE_VIEW DEBUG - course_id: ' . ($course->id ?? 'NOT_SET'));

                    // Write to custom debug file
                    file_put_contents('D:\laragon\www\msarlink\debug.log',
                        date('Y-m-d H:i:s') . ' COURSE_VIEW DEBUG - current_item: ' . json_encode($current_item ?? 'NOT_SET') . "\n",
                        FILE_APPEND | LOCK_EX);
                    file_put_contents('D:\laragon\www\msarlink\debug.log',
                        date('Y-m-d H:i:s') . ' COURSE_VIEW DEBUG - current_id: ' . ($current_id ?? 'NOT_SET') . "\n",
                        FILE_APPEND | LOCK_EX);
                    file_put_contents('D:\laragon\www\msarlink\debug.log',
                        date('Y-m-d H:i:s') . ' COURSE_VIEW DEBUG - course_id: ' . ($course->id ?? 'NOT_SET') . "\n",
                        FILE_APPEND | LOCK_EX);
                    ?>
                    <?php if (isset($current_item) && !empty($current_item['id'])): ?>
                        <button class="btn btn-success btn-block mark-complete-button"
                                onclick="markItemComplete(<?= $course->id ?>, <?= $current_item['id'] ?>)">
                            <i class="fas fa-check mr-2"></i>
                            تم الإكمال
                        </button>
                        <script>
                            console.log('COURSE_VIEW JS DEBUG - Course ID:', <?= $course->id ?>);
                            console.log('COURSE_VIEW JS DEBUG - Item ID:', <?= $current_item['id'] ?>);
                            console.log('COURSE_VIEW JS DEBUG - Current Item:', <?= json_encode($current_item) ?>);
                        </script>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            لا يمكن تحديد العنصر
                        </button>
                        <script>
                            console.log('COURSE_VIEW JS DEBUG - Current item missing or invalid:', <?= json_encode($current_item ?? 'NOT_SET') ?>);
                        </script>
                    <?php endif; ?>
                </div>

                <script>
                    function markItemComplete(courseId, itemId) {
                        console.log('MARK_COMPLETE DEBUG - Function called with:', {courseId, itemId});

                        if (!courseId || !itemId) {
                            console.error('MARK_COMPLETE ERROR - Missing parameters:', {courseId, itemId});
                            alert('خطأ: Course ID and Item ID required');
                            return;
                        }

                        // Disable button to prevent double clicks
                        const button = event.target;
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري الحفظ...';

                        console.log('MARK_COMPLETE DEBUG - Sending request to:', '<?= base_url('progress/mark-completed') ?>');
                        console.log('MARK_COMPLETE DEBUG - Request body:', {course_id: courseId, item_id: itemId});

                        fetch('<?= base_url('progress/mark-completed') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                course_id: courseId,
                                item_id: itemId
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update progress bar immediately
                                    const progressBar = document.querySelector('.progress-bar');
                                    if (progressBar && data.course_completion !== undefined) {
                                        progressBar.style.width = data.course_completion + '%';
                                        progressBar.setAttribute('aria-valuenow', data.course_completion);
                                        progressBar.textContent = data.course_completion + '%';
                                    }

                                    // Show success message briefly
                                    button.innerHTML = '<i class="fas fa-check mr-2"></i>مكتمل';
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-secondary');
                                    button.disabled = true;

                                    // Auto-navigate to next item or unit without confirmation
                                    if (data.next_item) {
                                        console.log(1111111111111111)
                                        setTimeout(() => {
                                            window.location.href = data.next_item.url;
                                        }, 500);
                                    } else if (data.next_unit) {
                                        console.log(22222222222222)
                                        setTimeout(() => {
                                            window.location.href = data.next_unit.url;
                                        }, 500);
                                    } else if (data.course_completed && data.redirect_url) {
                                        console.log(333333333333333333)
                                        // Course is completed, redirect to my_courses
                                        setTimeout(() => {
                                            window.location.href = data.redirect_url;
                                        }, 1000);
                                    }
                                    // No alert for course completion - just stay on current page
                                } else {
                                    alert('خطأ: ' + (data.message || 'فشل في حفظ التقدم'));
                                    button.disabled = false;
                                    button.innerHTML = '<i class="fas fa-check mr-2"></i>تم الإكمال';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('خطأ في الاتصال');
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-check mr-2"></i>تم الإكمال';
                            });
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Include Video Progress Tracking Script -->
<script src="<?= base_url('assets/js/video-progress.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize video progress tracking if video exists
        <?php if ($current_item_type === 'video' && isset($current_item)): ?>
        const videoElement = document.querySelector('iframe[src*="mediadelivery.net"]');
        if (videoElement && <?= $current_item['id'] ?? 0 ?>) {
            // Initialize progress tracker for the current video using item_id
            const progressTracker = new VideoProgressTracker(videoElement, <?= $current_item['id'] ?? 0 ?>, {
                updateInterval: 10000, // Update every 10 seconds
                completionThreshold: 0.85, // Mark complete at 85%
                autoMarkComplete: true,
                apiEndpoint: '<?= base_url('progress/update') ?>',
                onUnitCompleted: function(result) {
                    // Update progress bar
                    const progressBar = document.querySelector('.progress-bar');
                    if (progressBar && result.course_completion) {
                        progressBar.style.width = result.course_completion + '%';
                        progressBar.setAttribute('aria-valuenow', result.course_completion);
                        progressBar.textContent = result.course_completion + '%';
                    }

                    // Show next unit button if available
                    if (result.next_unit) {
                        const nextBtn = document.querySelector('.btn-next');
                        if (nextBtn) {
                            nextBtn.href = result.next_unit.url;
                            nextBtn.classList.remove('disabled');
                        }
                    }
                }
            });
        }
        <?php endif; ?>

        // Handle navigation item clicks
        const navItems = document.querySelectorAll('.unit-item-link');

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all items
                navItems.forEach(navItem => {
                    navItem.classList.remove('active');
                });

                // Add active class to clicked item
                this.classList.add('active');

                // Get the item ID from the href
                const href = this.getAttribute('href');
                const urlParams = new URLSearchParams(href.split('?')[1]);
                const itemId = urlParams.get('item_id');

                if (itemId) {
                    // Redirect to the new item
                    window.location.href = href;
                }
            });
        });

        // Handle accordion functionality
        const accordionButtons = document.querySelectorAll('.accordion-button');

        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target');
                const collapse = document.querySelector(target);

                if (collapse) {
                    // Toggle the collapse
                    if (collapse.classList.contains('show')) {
                        collapse.classList.remove('show');
                        this.classList.add('collapsed');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        collapse.classList.add('show');
                        this.classList.remove('collapsed');
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });

        // Auto-expand accordion containing active item
        const activeItem = document.querySelector('.unit-item-link.active');
        if (activeItem) {
            const accordionCollapse = activeItem.closest('.accordion-collapse');
            if (accordionCollapse) {
                accordionCollapse.classList.add('show');
                const accordionButton = document.querySelector(`[data-bs-target="#${accordionCollapse.id}"]`);
                if (accordionButton) {
                    accordionButton.classList.remove('collapsed');
                    accordionButton.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
</script>

<!-- Embedded Quiz Modal -->
<div id="embeddedQuizModal" class="embedded-quiz-modal" style="display: none;">
    <div class="embedded-quiz-container">
        <div class="embedded-quiz-header">
            <div class="quiz-title-section">
                <h4 id="quizTitle">اختبار</h4>
                <button class="close-quiz-btn" onclick="EmbeddedQuiz.close()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="quiz-progress-section">
                <div class="quiz-timer">
                    <i class="fas fa-clock"></i>
                    <span id="quizTimer">00:00</span>
                </div>
                <div class="quiz-progress">
                    <span id="questionCounter">1 من 5</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar" id="quizProgressBar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="embedded-quiz-content">
            <div id="quizQuestions"></div>
        </div>

        <div class="embedded-quiz-navigation">
            <button id="prevQuestionBtn" class="nav-btn prev-btn" onclick="EmbeddedQuiz.previousQuestion()">
                <i class="fas fa-chevron-right"></i> السابق
            </button>
            <button id="nextQuestionBtn" class="nav-btn next-btn" onclick="EmbeddedQuiz.nextQuestion()">
                التالي <i class="fas fa-chevron-left"></i>
            </button>
            <button id="submitQuizBtn" class="nav-btn submit-btn" onclick="EmbeddedQuiz.submitQuiz()" style="display: none;">
                <i class="fas fa-check"></i> إرسال الإجابات
            </button>
        </div>

        <div id="quizResults" class="quiz-results" style="display: none;">
            <div class="results-content">
                <div class="results-header">
                    <i class="fas fa-trophy results-icon"></i>
                    <h3>نتائج الاختبار</h3>
                </div>
                <div class="results-stats">
                    <div class="stat-item">
                        <span class="stat-label">النتيجة:</span>
                        <span class="stat-value" id="finalScore">0%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">الإجابات الصحيحة:</span>
                        <span class="stat-value" id="correctAnswers">0/0</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">الوقت المستغرق:</span>
                        <span class="stat-value" id="completionTime">0 دقيقة</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">الحالة:</span>
                        <span class="stat-value" id="passStatus">-</span>
                    </div>
                </div>
                <div class="results-actions">
                    <button class="btn btn-primary" onclick="EmbeddedQuiz.continueToNext()">
                        <i class="fas fa-arrow-left"></i> متابعة للعنصر التالي
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<!-- Additional JS if needed -->
<!-- Include Embedded Quiz CSS and JS -->
<link rel="stylesheet" href="<?= base_url() ?>site/css/embedded-quiz.css">
<script src="<?= base_url() ?>site/js/embedded-quiz.js"></script>

<?php $this->endSection(); ?>
