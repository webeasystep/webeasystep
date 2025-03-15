<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<style>
    /* Header area */
    .my-courses-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .my-courses-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .my-courses-header h1::after {
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
    .my-courses-header p {
        font-size: 1.1rem;
        color: #555;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
</style>
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="my-courses-header">
            <h1><?= esc($title) ?></h1>
            <p><?= esc($desc) ?></p>
        </div>

        <div class="row">
            <?php if (!empty($enrolledCourses)): ?>
                <?php foreach ($enrolledCourses as $item): ?>
                    <?php
                    // Each $item has ['course' => $courseObj, 'progress' => $progress]
                    $course   = $item['course'];
                    $progress = $item['progress'];
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <!-- Course Image -->
                            <img
                                    alt="<?= esc($course->course_name) ?>"
                                    style="height: 200px; object-fit: cover;"
                                    src="<?= thumb($course->image, 170, 249) ?>"
                                    class="card-img-top"
                            >

                            <div class="card-body d-flex flex-column">
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

                                <div class="mt-auto">
                                    <!-- Actions & Price -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="<?= site_url('courses/course_view/' . $course->slug) ?>"
                                               class="btn btn-outline-secondary">
                                                مشاهدة الدورة
                                            </a>
                                        </div>

                                        <?php if ($course->is_free): ?>
                                            <small class="text-muted">مجاناً</small>
                                        <?php else: ?>
                                            <small class="text-muted">
                                                $<?= number_format($course->price, 2) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12">
                    <p class="text-center" style="font-size:1rem;color:#555;">
                        لم تسجل في أي دورة بعد.
                    </p>
                </div>
            <?php endif; ?>
        </div> <!-- /.row -->
    </div>
</div> <!-- /.untree_co-section -->

<?php $this->endSection(); ?>
