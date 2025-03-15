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

                <!-- Accordion of Sections & Videos -->
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
                                        <?php foreach ($section['videos'] as $video): ?>
                                            <li class="mb-2">
                                                <a href="<?= site_url('courses/course_view/' . $course->slug . '?video=' . $video['id']) ?>"
                                                   class="text-dark text-decoration-none
                                                   <?= $video['id'] == $current_id ? 'active-video' : '' ?>">
                                                    <?php if ($video['is_preview']): ?>
                                                        <span class="icon-play-circle-o mr-2" style="color:#6c757d;"></span>
                                                    <?php else: ?>
                                                        <span class="icon-lock mr-2" style="color:#dc3545;"></span>
                                                    <?php endif; ?>
                                                    <?= esc($video['video_title']) ?>
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

            <!-- Main Content (Video Player & Description) -->
            <div class="col-lg-8 main-content">
                <!-- Video Title -->
                <h2 class="video-title-main-video mb-2">
                    <?= esc($video_title) ?>
                </h2>

                <!-- Video Player -->
                <div class="course-preview-video">
                    <div class="video-container">
                        <iframe
                                src="https://iframe.mediadelivery.net/embed/395633/<?= $video_id ?>?autoplay=false"
                                loading="lazy"
                                style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen="true"
                        ></iframe>
                    </div>
                </div>

                <!-- Video Description -->
                <p class="course-description-text">
                    <?= esc($video_desc) ?>
                </p>

                <!-- Mark as Complete Button -->
                <form action="<?= site_url('courses/markLessonComplete') ?>" method="post">
                    <input type="hidden" name="id" value="<?= esc($current_id) ?>">
                    <input type="hidden" name="slug" value="<?= esc($course->slug) ?>">
                    <button class="btn btn-primary btn-block mark-complete-button" type="submit">
                        تم الإكمال
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
