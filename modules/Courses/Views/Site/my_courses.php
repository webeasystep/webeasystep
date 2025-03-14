<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

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
                                    <img   alt="<?= esc($course->course_name) ?>"
                                           style="height: 200px; object-fit: cover;" src="<?= thumb($course->image,170,249) ?>"
                                           class="card-img-top">

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
