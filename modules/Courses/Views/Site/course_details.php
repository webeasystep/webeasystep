<?php $this->extend('site_layout/template'); ?>

<?php $this->section('content'); ?>

<style>
    /* Inline CSS (as requested, can be moved to a separate CSS file later) */
    /* Sidebar video block */
    .course-sidebar .course-video {
        margin-bottom: 30px;
    }
    .course-sidebar .course-video .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 5px;
    }
    .course-sidebar .course-video .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    /* Course Features Block */
    .course-sidebar .block-v1.course-features {
        background-color: #136ad5;
        color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
    }
    .course-sidebar .block-v1.course-features h3,
    .course-sidebar .block-v1.course-features ul,
    .course-sidebar .block-v1.course-features ul li {
        color: #ffffff;
    }
    .course-sidebar .block-v1.course-features ul li span {
        margin-right: 5px;
    }
    /* Improved Price Block */
    .course-sidebar .pricing-block {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
    }
    .course-sidebar .pricing-block h3 {
        color: #136ad5;
        margin-bottom: 15px;
    }
    .course-sidebar .pricing-block .price {
        font-size: 24px;
        color: #333;
        margin-bottom: 15px;
    }
    .course-sidebar .pricing-block .offer-price {
        color: #e74c3c;
        font-weight: bold;
    }
    .course-sidebar .pricing-block .btn {
        background-color: #136ad5;
        border-color: #136ad5;
    }
    /* Custom Accordion (Bootstrap 4 syntax) */
    .custom-accordion .accordion-item {
        border: none;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }
    .custom-accordion .accordion-item:last-child {
        border-bottom: none;
    }
    .custom-accordion .btn-link {
        font-size: 18px;
        font-weight: 600;
        color: #136ad5;
        text-decoration: none;
        padding: 0;
    }
    .custom-accordion .btn-link:hover {
        text-decoration: none;
    }
    .custom-accordion .accordion-body {
        padding: 15px 0;
    }
    .video-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .video-list li {
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }
    .video-icon {
        margin-right: 10px;
        color: #136ad5;
    }
    .video-time {
        float: right;
        color: #999;
    }
    .video-status {
        margin-left: 10px;
        font-size: 14px;
        font-weight: bold;
    }
    .video-status.preview {
        color: #27ae60;
    }
    .video-status.locked {
        color: #e74c3c;
    }
</style>
<!--Banner-->
<!-- Main Section -->
<div class="untree_co-section">
    <div class="container">
        <div class="row">
            <!-- Left Column: Course Description & Outline -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <h2 class="section-title mb-3" data-aos="fade-up" data-aos-delay="200"><?= esc($title) ?></h2>
                <p class="course-description" data-aos="fade-up" data-aos-delay="300">
                    <?= esc($course->course_desc) ?>
                </p>

                <!-- Course Outline using a Custom Accordion (Bootstrap 4) -->
                <div class="course-outline" data-aos="fade-up" data-aos-delay="400">
                    <h2 class="section-title mb-4">محتوى الكورس</h2>
                    <div class="custom-accordion" id="courseOutlineAccordion">
                        <?php if (is_array($structure) && !empty($structure)) : ?>
                            <?php foreach ($structure as $sectionIndex => $section) : ?>
                                <div class="accordion-item">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link <?= ($sectionIndex !== 0) ? 'collapsed' : '' ?>" type="button" data-toggle="collapse" data-target="#collapse<?= $sectionIndex + 1 ?>" aria-expanded="<?= ($sectionIndex === 0) ? 'true' : 'false' ?>" aria-controls="collapse<?= $sectionIndex + 1 ?>">
                                            Section <?= $sectionIndex + 1 ?>: <?= esc($section['section_title'] ?? 'Section Title') ?> <span class="video-count">(<?= count($section['videos'] ?? []) ?> Lessons)</span>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $sectionIndex + 1 ?>" class="collapse <?= ($sectionIndex === 0) ? 'show' : '' ?>" aria-labelledby="headingOne" data-parent="#courseOutlineAccordion">
                                        <div class="accordion-body">
                                            <ul class="video-list">
                                                <?php if (is_array($section['videos'] ?? [])) : ?>
                                                    <?php foreach ($section['videos'] as $video) : ?>
                                                        <li>
                                                            <span class="video-icon"><span class="icon-play-circle-o"></span></span>
                                                            <a href="<?= base_url('courses/course_view/'.$course->slug) ?>"><?= esc($video['video_title'] ?? 'Lesson Title') ?></a>
                                                            <span class="video-time"><?= esc($video['video_duration'] ?? '0:00') ?></span>
                                                            <span class="video-status <?= (isset($video['is_preview']) && $video['is_preview'] == 1) ? 'preview' : 'locked' ?>"><?= (isset($video['is_preview']) && $video['is_preview'] == 1) ? 'Preview' : 'Locked' ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No course outline available.</p>
                        <?php endif; ?>
                    </div> <!-- End custom-accordion -->
                </div>
            </div>
            <!-- Right Sidebar -->
            <div class="col-lg-4 sidebar">
                <div class="course-sidebar">
                    <!-- Video Block in Sidebar -->
                    <div class="block-v1 course-video" data-aos="fade-up" data-aos-delay="50">
                        <h3 class="mb-3">Course Intro Video</h3>
                        <div class="video-container">
                            <iframe src="<?= esc($course->intro_video_url ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ') ?>" title="Intro Video" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    <!-- Course Features Block -->
                    <div class="block-v1 course-features" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="mb-3">Course Features</h3>
                        <ul class="course-features-list">
                            <li><span class="icon-clock-o"></span> Duration: <strong><?= esc($course->duration ?? 'N/A') ?></strong></li>
                            <li><span class="icon-play-circle-o"></span> Video Lessons: <strong><?= esc($course->video_videos_count ?? 'N/A') ?> Video Lessons</strong></li>
                            <li><span class="icon-list"></span> Quizzes: <strong><?= esc($course->quizzes_count ?? 'N/A') ?> Quizzes</strong></li>
                            <li><span class="icon-certificate"></span> Certificate of Completion</li>
                            <li><span class="icon-infinity"></span> Lifetime Access</li>
                        </ul>
                    </div>
                    <!-- Pricing Block with Improved Colors -->
                    <div class="block-v1 pricing-block" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="mb-3">Pricing</h3>
                        <p class="price">$<?= esc(number_format($course->price ?? 0, 2)) ?> <span class="offer-price">$<?= esc(number_format($course->offer_price ?? $course->price ?? 0, 2)) ?></span></p>
                        <p><a href="#" class="btn btn-primary">Enroll Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
