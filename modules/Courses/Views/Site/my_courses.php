<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<!-- Hero Banner -->
<div class="untree_co-hero overlay" style="background-image: url('site/images/img-school-6-min.jpg');">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">
                            <?= esc($title) ?>
                        </h1>
                        <div class="mb-5 text-white desc mx-auto" data-aos="fade-up" data-aos-delay="200">
                            <p><?= esc($desc) ?></p>
                        </div>

                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300">
                            <a href="<?= site_url('courses') ?>" class="btn btn-secondary">Explore More Courses</a>
                        </p>
                    </div>
                </div>
            </div>
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</div> <!-- /.untree_co-hero -->

<!-- My Enrolled Courses Section -->
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row align-items-stretch">
            <div class="container">
                <!-- Title & Description -->
                <h1 class="mb-4"><?= esc($title) ?></h1>
                <p class="mb-5"><?= esc($desc) ?></p>

                <div class="row">
                    <?php if (!empty($enrolledCourses)): ?>
                        <?php foreach ($enrolledCourses as $item): ?>
                            <?php
                            // Each $item has ['course' => $courseObj, 'progress' => $progress]
                            $course   = $item['course'];
                            $progress = $item['progress'];
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card mb-4 shadow-sm">
                                    <!-- Course Image -->
                                    <img
                                            src="<?= base_url('uploads/' . $course->image) ?>"
                                            class="card-img-top"
                                            alt="<?= esc($course->course_name) ?>"
                                            style="height: 200px; object-fit: cover;"
                                    >
                                    <div class="card-body">
                                        <!-- Course Title & Description -->
                                        <h5 class="card-title"><?= esc($course->course_name) ?></h5>
                                        <p class="card-text"><?= esc($course->course_desc) ?></p>

                                        <!-- Progress Bar -->
                                        <div class="progress mb-2">
                                            <div class="progress-bar"
                                                 role="progressbar"
                                                 style="width: <?= $progress ?>%;"
                                                 aria-valuenow="<?= $progress ?>"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <?= $progress ?>%
                                            </div>
                                        </div>

                                        <!-- Actions & Price -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a href="<?= site_url('courses/course_view/' . $course->slug) ?>"
                                                   class="btn btn-sm btn-outline-secondary">
                                                    View Course
                                                </a>
                                            </div>
                                            <?php if ($course->is_free): ?>
                                                <small class="text-muted">Free</small>
                                            <?php else: ?>
                                                <small class="text-muted">
                                                    $<?= number_format($course->price, 2) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-center">You have not enrolled in any courses yet.</p>
                        </div>
                    <?php endif; ?>
                </div> <!-- /.row -->
            </div>
        </div>
    </div>
</div> <!-- /.untree_co-section -->

<?= $this->endSection(); ?>
