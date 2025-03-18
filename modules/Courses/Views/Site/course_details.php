<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<style>
    .untree_co-section {
        padding-top: 80px;
        padding-bottom: 80px;
        background-color: #f9fafb;
    }

    /* Center the main heading and description area */
    .course-header-wrapper {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        margin-bottom: 50px;
    }
    .course-header-wrapper .section-title {
        font-size: 2.4rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        position: relative;
    }
    .course-header-wrapper .section-title::after {
        content: "";
        position: absolute;
        right: 50%;
        transform: translateX(50%);
        bottom: -10px;
        width: 60px;
        height: 3px;
        background: #136ad5;
        border-radius: 2px;
    }
    .course-header-wrapper .course-description {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #555;
        margin: 0 auto;
    }

    /* Additional CTA area near the heading */
    .enroll-cta-top {
        margin-top: 25px;
    }

    /* Left Column: Outline / Videos */
    .course-outline {
        margin-top: 30px;
    }

    /* Custom Accordion (Bootstrap 4 syntax) */
    .custom-accordion .accordion-item {
        border: none;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
        background: #fff;
        border-radius: 5px;
    }
    .custom-accordion .accordion-item:last-child {
        border-bottom: none;
    }
    .custom-accordion .btn-link {
        font-size: 18px;
        font-weight: 600;
        color: #136ad5;
        text-decoration: none;
        padding: 15px 0;
        display: block;
        width: 100%;
        text-align: right; /* for RTL */
        position: relative;
        padding-right: 20px; /* Adjust for icon or spacing */
    }
    .custom-accordion .btn-link:hover {
        text-decoration: none;
        color: #0b5cbf;
    }
    .custom-accordion .accordion-body {
        padding: 15px;
    }
    .video-count {
        font-size: 0.85rem;
        color: #999;
        margin-right: 8px; /* Moved to right for RTL */
    }

    /* Video list styling */
    .video-list {
        list-style: none;
        padding-right: 0;
        margin-bottom: 0;
    }
    .video-list li {
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }
    .video-icon {
        margin-left: 10px; /* For RTL */
        color: #136ad5;
    }
    .video-time {
        float: left; /* For RTL, visually at the "end" of line */
        color: #999;
        font-size: 0.9rem;
    }
    .video-status {
        margin-right: 10px;
        font-size: 0.9rem;
        font-weight: bold;
    }
    .video-status.preview {
        color: #27ae60;
    }
    .video-status.locked {
        color: #e74c3c;
    }

    /* Right Sidebar: Arabic styling + subtle gradient + center content */
    .sidebar {
        margin-bottom: 30px;
    }
    .course-sidebar {
        background: linear-gradient(to bottom left, #ffffff, #f0f5ff);
        border: 1px solid #e0e7ee;
        border-radius: 7px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        text-align: center; /* Center the sidebar content */
    }

    /* Video Block in Sidebar */
    .course-video {
        margin-bottom: 30px;
    }
    .course-video h3 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .course-video .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .course-video .video-container iframe {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 5px;
    }

    /* Course Features Block (Arabic) */
    .block-v1.course-features {
        background-color: #136ad5;
        color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
    }
    .block-v1.course-features h3 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .course-features-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .course-features-list li {
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    .course-features-list li span {
        margin-left: 5px; /* For RTL */
    }

    /* Pricing Block */
    .pricing-block {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
    }
    .pricing-block h3 {
        color: #136ad5;
        margin-bottom: 15px;
        font-size: 1.2rem;
    }
    .pricing-block .price {
        font-size: 24px;
        color: #333;
        margin-bottom: 15px;
    }
    .pricing-block .offer-price {
        color: #e74c3c;
        font-weight: bold;
        margin-right: 5px; /* For RTL */
        font-size: 1rem;
    }
    .pricing-block .btn {
        background-color: #136ad5;
        border-color: #136ad5;
        font-size: 1rem;
        font-weight: 600;
    }

    /* Center the video in the modal body & fill the modal area */
    .modal-body {
        text-align: center;
        padding: 0; /* Remove default padding for a larger video */
    }
    .modal-dialog.modal-lg {
        max-width: 80vw; /* Widen the modal for bigger video */
    }
    .modal-content {
        border-radius: 8px;
    }
    .modal-title {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .modal-body .video-container {
        width: 100%;
        margin: 0 auto;
        max-width: 100%;
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
    }
    .modal-body .video-container iframe {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0; /* or right: 0; */
        border: none;
        border-radius: 5px;
    }
</style>

<!-- Main Section -->
<div class="untree_co-section">
    <div class="container">
        <!-- Centered Title & Description -->
        <div class="course-header-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h2 class="section-title"><?= esc($title) ?></h2>
            <p class="course-description"><?= esc($course->course_desc) ?></p>

            <!-- CTA area near top heading (the same "اشترك الآن" style) -->
            <div class="enroll-cta-top">
                <a href="#" class="btn btn-success" style="min-width: 160px; font-weight:600;">
                    اشترك الآن
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Course Outline -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="course-outline" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="section-title mb-4" style="font-size:1.5rem;">محتوى الكورس</h2>
                    <div class="custom-accordion" id="courseOutlineAccordion">
                        <?php if (is_array($structure) && !empty($structure)) : ?>
                            <?php foreach ($structure as $sectionIndex => $section) : ?>
                                <div class="accordion-item">
                                    <h2 class="mb-0">
                                        <button
                                                class="btn btn-link <?= ($sectionIndex !== 0) ? 'collapsed' : '' ?>"
                                                type="button"
                                                data-toggle="collapse"
                                                data-target="#collapse<?= $sectionIndex + 1 ?>"
                                                aria-expanded="<?= ($sectionIndex === 0) ? 'true' : 'false' ?>"
                                                aria-controls="collapse<?= $sectionIndex + 1 ?>"
                                        >
                                            القسم <?= $sectionIndex + 1 ?>:
                                            <?= esc($section['section_title'] ?? 'عنوان القسم') ?>
                                            <span class="video-count">(<?= count($section['videos'] ?? []) ?> دروس)</span>
                                        </button>
                                    </h2>
                                    <div
                                            id="collapse<?= $sectionIndex + 1 ?>"
                                            class="collapse <?= ($sectionIndex === 0) ? 'show' : '' ?>"
                                            aria-labelledby="headingOne"
                                            data-parent="#courseOutlineAccordion"
                                    >
                                        <div class="accordion-body">
                                            <ul class="video-list">
                                                <?php if (is_array($section['videos'] ?? [])) : ?>
                                                    <?php foreach ($section['videos'] as $video) : ?>
                                                        <li>
                                                            <span class="video-icon">
                                                                <span class="icon-play-circle-o"></span>
                                                            </span>
                                                            <?php if (isset($video['is_preview']) && $video['is_preview'] == 1): ?>
                                                                <!-- PREVIEW link => open in modal -->
                                                                <a href="#" class="preview-video-link" data-video-id="<?= esc($video['video_id']) ?>">
                                                                    <?= esc($video['video_title'] ?? 'عنوان الدرس') ?>
                                                                </a>
                                                            <?php else: ?>
                                                                <!-- locked => normal text -->
                                                                <span class="text-muted">
                                                                    <?= esc($video['video_title'] ?? 'عنوان الدرس') ?>
                                                                </span>
                                                            <?php endif; ?>

                                                            <span class="video-time">
                                                                <?= esc($video['video_duration'] ?? '0:00') ?>
                                                            </span>
                                                            <span class="video-status <?= (isset($video['is_preview']) && $video['is_preview'] == 1) ? 'preview' : 'locked' ?>">
                                                                <?= (isset($video['is_preview']) && $video['is_preview'] == 1)
                                                                    ? 'معاينة'
                                                                    : 'مغلق' ?>
                                                            </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <li>لا توجد دروس في هذا القسم.</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>لا يوجد محتوى متاح لهذا الكورس.</p>
                        <?php endif; ?>
                    </div> <!-- End custom-accordion -->
                </div>
            </div>

            <!-- Right Sidebar (in Arabic, content centered) -->
            <div class="col-lg-4 sidebar">
                <div class="course-sidebar" data-aos="fade-up" data-aos-delay="300">
                    <!-- Video Block in Sidebar -->
                    <div class="block-v1 course-video">
                        <h3 class="mb-3">فيديو تقديمي للدورة</h3>
                        <div class="video-container">
                            <iframe
                                    src="https://iframe.mediadelivery.net/embed/395633/<?= $course->intro_video_id ?? 'af30806a-a34e-448e-91fb-b9c3f8d18b02' ?>?autoplay=false"
                                    loading="lazy"
                                    style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                    allowfullscreen="true"
                            ></iframe>
                        </div>
                    </div>

                    <!-- Course Features Block (Arabic) -->
                    <div class="block-v1 course-features">
                        <h3 class="mb-3">مميزات الدورة</h3>
                        <ul class="course-features-list">
                            <li>
                                <span class="icon-clock-o"></span>
                                المدة:
                                <strong><?= esc($course->duration ?? 'غير محدد') ?></strong>
                            </li>
                            <li>
                                <span class="icon-play-circle-o"></span>
                                الدروس المرئية:
                                <strong><?= esc($course->video_count ?? 'غير محدد') ?> درس</strong>
                            </li>
                            <li>
                                <span class="icon-list"></span>
                                الاختبارات:
                                <strong><?= esc($course->quizzes_count ?? 'غير محدد') ?></strong>
                            </li>
                            <li>
                                <span class="icon-certificate"></span>
                                شهادة إتمام
                            </li>
                            <li>
                                <span class="icon-infinity"></span>
                                دخول مدى الحياة
                            </li>
                        </ul>
                    </div>

                    <!-- Pricing Block with the same "اشترك الآن" button -->
                    <div class="block-v1 pricing-block">
                        <h3 class="mb-3">التسعير</h3>
                        <p class="price">
                            $<?= esc(number_format($course->price ?? 0, 2)) ?>
                            <?php if (!empty($course->offer_price) && $course->offer_price < $course->price) : ?>
                                <span class="offer-price">$<?= esc(number_format($course->offer_price, 2)) ?></span>
                            <?php endif; ?>
                        </p>
                        <p>
                            <a href="#" class="btn btn-primary" style="display: inline-block; width: 100%;">
                                اشترك الآن
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Preview Videos -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
    <!-- .modal-dialog.modal-lg => bigger container for video -->
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="videoModalLabel" class="modal-title">فيديو المعاينة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- We removed extra padding in .modal-body to let video fill space -->
            <div class="modal-body">
                <div class="video-container">
                    <iframe
                            id="videoFrame"
                            src=""
                            loading="lazy"
                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                            allowfullscreen="true"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script to handle modal & preview logic -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const previewLinks = document.querySelectorAll(".preview-video-link");
        const videoFrame   = document.getElementById("videoFrame");
        const modal        = $("#videoModal");

        previewLinks.forEach(link => {
            link.addEventListener("click", function (event) {
                event.preventDefault();
                let videoId = this.getAttribute("data-video-id");
                if (videoId) {
                    // Construct the MediaDelivery embed URL
                    let videoUrl = `https://iframe.mediadelivery.net/embed/395633/${videoId}?autoplay=true`;
                    videoFrame.setAttribute("src", videoUrl);
                    modal.modal("show");
                }
            });
        });

        // Reset the iframe src when the modal is closed to stop the video
        modal.on('hidden.bs.modal', function () {
            videoFrame.setAttribute("src", "");
        });
    });
</script>

<?php $this->endSection(); ?>
