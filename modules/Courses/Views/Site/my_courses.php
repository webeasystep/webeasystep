<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Main Section Container */
    .untree_co-section {
        padding-top: 80px;
        padding-bottom: 80px;
        background-color: #f9fafb;
    }

    /* Header: Title & Description */
    .my-courses-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .my-courses-header h1.section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .my-courses-header h1.section-title::after {
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
    .my-courses-header p.course-description {
        font-size: 1.1rem;
        color: #555;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Course Card Container */
    .course-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .course-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    /* Course Image Section */
    .course-image-container {
        position: relative;
        overflow: hidden;
    }
    .course-thumbnail {
        width: 100%;
        height: 220px; /* or any suitable height */
        object-fit: cover;
        display: block;
    }
    /* Course Badge (Free or Price) */
    .course-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #136ad5;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    .free-badge {
        background-color: #27ae60 !important; /* Green for free */
    }
    .price-badge {
        background-color: #e74c3c !important; /* Red for price */
    }

    /* Course Content */
    .course-content {
        flex: 1 1 auto;
        padding: 15px;
        text-align: right; /* Arabic alignment */
        direction: rtl;    /* If you want full RTL text flow */
    }
    .course-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .course-excerpt {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 15px;
        height: 50px;       /* Limit excerpt height if you want */
        overflow: hidden;   /* Hide overflow */
    }

    /* Progress Bar Section */
    .progress-container {
        margin-bottom: 15px;
    }
    .progress-label {
        font-size: 0.9rem;
        margin-bottom: 5px;
        color: #444;
    }
    .progress {
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-bar {
        background-color: #136ad5;
        transition: width 0.4s ease;
    }

    /* Action Button */
    .continue-course-btn {
        display: inline-block;
        width: 100%;
        background-color: #136ad5;
        color: #fff;
        padding: 10px;
        text-align: center;
        border-radius: 4px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    .continue-course-btn:hover {
        background-color: #0b5cbf;
        color: #fff;
        text-decoration: none;
    }
    .continue-course-btn .icon-play-circle-o {
        margin-left: 6px; /* For Arabic, you might prefer margin-right: 6px; */
    }

    /* Empty Courses Placeholder */
    .empty-courses-container {
        text-align: center;
        background: #fff;
        border-radius: 8px;
        padding: 40px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .empty-courses-icon i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    .empty-courses-title {
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 10px;
    }
    .empty-courses-message {
        font-size: 1rem;
        color: #777;
        margin-bottom: 20px;
    }
    .browse-courses-btn {
        display: inline-block;
        background-color: #136ad5;
        color: #fff;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
    }
    .browse-courses-btn:hover {
        background-color: #0b5cbf;
        text-decoration: none;
    }
</style>

<div class="untree_co-section bg-light">
    <div class="container">
        <div class="my-courses-header" data-aos="fade-up" data-aos-delay="100">
            <h1 class="section-title text-center"><?= esc($title) ?></h1>
            <p class="text-center course-description"><?= esc($desc) ?></p>
        </div>

        <div class="row">
            <?php if (!empty($enrolledCourses)): ?>
                <?php foreach ($enrolledCourses as $item): ?>
                    <?php
                    // Each $item has ['course' => $courseObj, 'progress' => $progress]
                    $course   = $item['course'];
                    $progress = $item['progress'];
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="course-card">
                            <!-- Course Image & Badge -->
                            <div class="course-image-container">
                                <img
                                        alt="<?= esc($course->course_title) ?>"
                                        class="course-thumbnail"
                                        src="<?= thumb($course->image, 170, 249) ?>"
                                >
                                <?php if ($course->is_free): ?>
                                    <div class="course-badge free-badge">مجاناً</div>
                                <?php else: ?>
                                    <div class="course-badge price-badge">
                                        $<?= number_format($course->price, 2) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Course Content -->
                            <div class="course-content">
                                <h3 class="course-title"><?= esc($course->course_title) ?></h3>
                                <p class="course-excerpt">
                                    <?= character_limiter(strip_tags($course->course_desc), 90) ?>
                                </p>

                                <!-- Progress -->
                                <div class="progress-container">
                                    <div class="progress-label">تقدمك في الدورة</div>
                                    <div class="progress">
                                        <div class="progress-bar"
                                             role="progressbar"
                                             style="width: <?= $progress ?>%;"
                                             aria-valuenow="<?= $progress ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            <?= $progress ?>%
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="<?= site_url('courses/course_view/' . $course->slug) ?>"
                                   class="btn continue-course-btn">
                                    <i class="icon-play-circle-o"></i> مشاهدة الدورة
                                </a>
                            </div> <!-- /.course-content -->
                        </div> <!-- /.course-card -->
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <!-- If no enrolled courses -->
                <div class="col-12">
                    <div class="empty-courses-container">
                        <div class="empty-courses-icon">
                            <i class="icon-book"></i>
                        </div>
                        <h3 class="empty-courses-title">لم تسجل في أي دورة بعد</h3>
                        <p class="empty-courses-message">
                            استكشف دوراتنا المتاحة وابدأ رحلة التعلم الخاصة بك
                        </p>
                        <a href="<?= site_url('courses') ?>" class="btn browse-courses-btn">
                            استعرض الدورات المتاحة
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</div> <!-- /.untree_co-section -->

<?= $this->endSection(); ?>
