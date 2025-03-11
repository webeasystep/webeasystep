<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<style>
    /* ====== KEEPING YOUR ORIGINAL DESIGN ====== */

    /* ===== NAV & TOP BAR ===== */
    .site-nav {
        position: relative !important;
        background: #fff !important; /* White nav background */
        border-radius: 0 0 12px 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        padding-bottom: 60px; /* more bottom padding for a balanced header */
    }
    .site-nav a {
        color: #000 !important; /* Dark nav link text */
    }
    .site-nav .site-navigation .site-menu > li > a:hover {
        color: #136ad5 !important;
        opacity: 1;
    }
    .site-nav .site-navigation .site-menu {
        display: flex;
        align-items: center;
        justify-content: center; /* Center items horizontally */
    }
    .site-nav .site-navigation .site-menu > li {
        margin-right: 15px;
    }
    .site-nav .btn-book {
        position: relative;
        margin-left: 20px;
        right: auto;
    }
    .top-bar {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    .top-bar a {
        color: #777 !important;
    }

    /* ===== MAIN SECTION ===== */
    .untree_co-section.bg-light {
        padding-top: 60px;
        padding-bottom: 60px;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        margin-bottom: 30px;
        width: 320px; /* Wider sidebar to fit text */
        padding-right: 30px;
    }
    .user-info-block {
        background-color: #fff;
        padding: 25px;
        border-radius: 7px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.08);
        border: 1px solid #eee;
        text-align: center;
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
        text-align: center;
        margin-bottom: 10px;
    }
    .progress {
        height: 6px; /* Thinner progress bar */
        border-radius: 5px;
        margin-bottom: 15px;
        background-color: #e9ecef;
    }
    .progress-bar {
        background-color: #007bff;
        padding: 0 5px;
        font-size: 0.8rem;
        line-height: 1.4;
    }
    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .nav-buttons .btn {
        flex: 1;
        margin: 0 5px;
        white-space: nowrap;
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    .nav-buttons .btn.btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .nav-buttons .btn.btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    /* ===== ACCORDION (SECTION BLOCK) ===== */
    .videos-accordion {
        margin-top: 20px;
        max-height: 500px;
        overflow-y: auto;
    }
    .videos-accordion .accordion-item {
        border: none;
        border-radius: 0;
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
        text-align: left;
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

    /* ===== MAIN CONTENT ===== */
    .main-content {
        margin-top: 30px;
    }
    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 7px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .video-container iframe {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        border: none;
        border-radius: 7px;
    }
    .main-content h2.lesson-title-main-video {
        font-size: 1.6rem;
        color: #333;
        margin-bottom: 10px;
        margin-top: 15px !important;
    }
    .main-content p.course-description-text {
        color: #666;
        line-height: 1.7;
        margin-bottom: 25px;
    }
    .main-content .btn.btn-primary.btn-block {
        padding: 14px 30px;
        font-size: 1.1rem;
        border-radius: 7px;
        margin-top: 25px;
        font-weight: 600;
    }

    /* ===== RESPONSIVE ADJUSTMENTS ===== */
    @media (max-width: 991.98px) {
        .sidebar {
            margin-bottom: 30px;
            padding-right: 0;
            width: 100%;
        }
        .main-content {
            margin-top: 0;
        }
        .user-info-block, .videos-accordion .accordion-item {
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

<!-- Main Section -->
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row">
            <!-- Sidebar (col-lg-4) -->
            <div class="col-lg-4 sidebar">
                <div class="user-info-block">
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar"
                             style="width: <?= esc($course_progress) ?>%;"
                             aria-valuenow="<?= esc($course_progress) ?>"
                             aria-valuemin="0" aria-valuemax="100">
                            <?= esc($course_progress) ?>%
                        </div>
                    </div>
                    <div class="nav-buttons">
                        <!-- Previous Button -->
                        <?php if ($prevLessonUrl): ?>
                            <a class="btn btn-outline-secondary prev-btn" href="<?= $prevLessonUrl ?>">Previous</a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary prev-btn" disabled>Previous</button>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($nextLessonUrl): ?>
                            <a class="btn btn-secondary next-btn" href="<?= $nextLessonUrl ?>">Next</a>
                        <?php else: ?>
                            <button class="btn btn-secondary next-btn" disabled>Next</button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Collapsible video sections in a scrollable accordion -->
                <div class="videos-accordion accordion" id="videoAccordion">
                    <?php foreach ($structure as $section): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= esc($section['section_id']) ?>">
                                <button class="accordion-button <?= $section['is_open'] ? '' : 'collapsed' ?>"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapse<?= esc($section['section_id']) ?>"
                                        aria-expanded="<?= $section['is_open'] ? 'true' : 'false' ?>"
                                        aria-controls="collapse<?= esc($section['section_id']) ?>">
                                    <?= esc($section['section_title']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= esc($section['section_id']) ?>"
                                 class="collapse <?= $section['is_open'] ? 'show' : '' ?>"
                                 aria-labelledby="heading<?= esc($section['section_id']) ?>"
                                 data-parent="#videoAccordion">
                                <div class="accordion-body">
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($section['lessons'] as $lesson): ?>
                                            <li class="mb-2">
                                                <a href="<?= site_url('courses/course_view/' . $course->slug . '?lesson=' . $lesson['lesson_id']) ?>"
                                                   class="text-dark text-decoration-none
                                                   <?= $lesson['lesson_id'] == $current_lesson_id ? 'active-video' : '' ?>">
                                                    <?php if ($lesson['is_preview']): ?>
                                                        <span class="icon-play-circle-o mr-2" style="color:#6c757d;"></span>
                                                    <?php else: ?>
                                                        <span class="icon-lock mr-2" style="color:#dc3545;"></span>
                                                    <?php endif; ?>
                                                    <?= esc($lesson['lesson_title']) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div><!-- End .videos-accordion -->
            </div>

            <!-- Main Content (col-lg-8) -->
            <div class="col-lg-8 main-content">
                <!-- Title above video -->
                <h2 class="lesson-title-main-video mb-2">
                    <?= esc($lesson_title) ?>
                </h2>
                <!-- Video Preview -->
                <div class="course-preview-video">
                    <div class="video-container">
                        <iframe width="100%" height="315"
                                src="<?= esc($video_url) ?>"
                                title="<?= esc($lesson_title) ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- Description below video -->
                <p class="course-description-text">
                    <?= esc($course_description) ?>
                </p>

                <!-- “Mark as Complete” Button -->
                <form action="<?= site_url('courses/markLessonComplete') ?>" method="post">
                    <input type="hidden" name="lesson_id" value="<?= esc($current_lesson_id) ?>">
                    <button class="btn btn-primary btn-block mark-complete-button" type="submit">
                        MARK AS COMPLETE
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
