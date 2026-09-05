<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

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
                    // Each $item has ['course' => $courseObj, 'progress' => $progress, 'total_units' => $totalUnits, 'completed_units' => $completedUnits, 'remaining_units' => $remainingUnits]
                    $course   = $item['course'];
                    $progress = $item['progress'];
                    $enrolledCourse = $item; // Use the full item array for unit data
                    ?>
                    <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="course-card">
                            <!-- Course Image & Badge -->
                            <div class="course-image-container">
                                <?php 
                                    $courseAlt = esc($course->course_title ?? '') . (!empty($course->course_name_en) ? ' - ' . esc($course->course_name_en) : '');
                                    $courseAlt .= !empty($course->course_code) ? ' | رمز المقرر: ' . esc($course->course_code) : '';
                                    $courseAlt .= !empty($course->college_name_ar) ? ' | ' . esc($course->college_name_ar) : '';
                                    $courseAlt .= ' | الجامعة السعودية الإلكترونية SEU';
                                ?>
                                <img
                                        alt="<?= $courseAlt ?>"
                                        class="course-thumbnail"
                                        src="<?= thumb($course->image, 400, 200) ?>"
                                >
                                <div class="course-badge info-badge">
                                    <i class="fas fa-book"></i> 
                                    <?= $enrolledCourse['completed_units'] ?>/<?= $enrolledCourse['total_units'] ?> وحدة
                                    <?php if ($enrolledCourse['remaining_units'] > 0): ?>
                                        <span class="remaining-units">(<?= $enrolledCourse['remaining_units'] ?> متبقية)</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Course Content -->
                            <div class="course-content">
                                <h3 class="course-title"><?= esc($course->course_title) ?></h3>
                                <p class="course-excerpt text-secondary">
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

                                <!-- Action Buttons -->
                                <div class="d-inline-flex" style="gap: 5px; width: 100%;">
                                    <!-- شراء وحدات button -->
                                    <a href="<?= base_url('courses/course_details/' . $course->slug) ?>"
                                       class="btn btn-primary btn-sm" style="flex: 1;">
                                        شراء وحدات
                                    </a>
                                    <!-- مشاهدة button - always visible in my_courses since user is enrolled -->
                                    <a href="<?= site_url('courses/course_view/' . $course->slug) ?>"
                                       class="btn btn-secondary btn-sm" style="flex: 1;">
                                        مشاهدة
                                    </a>
                                </div>
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
                            استكشف مقرراتنا المتاحة وابدأ رحلة التعلم الخاصة بك
                        </p>
                        <a href="<?= site_url('courses') ?>" class="btn browse-courses-btn">
                            استعرض المقررات المتاحة
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</div> <!-- /.untree_co-section -->

<?= $this->endSection(); ?>
