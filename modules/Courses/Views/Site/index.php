<!-- app/Views/site/courses.php -->
<?= $this->extend('site_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Start of Courses Section -->
<div id="bookNow" class="untree_co-section bg-light">
    <div class="container">

        <!-- Section Heading & Intro -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">Book Your Free Lesson Now!</h2>
                <p>Explore a variety of courses covering computer science tracks to build a solid foundation in IT.</p>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="row">
            <?php if (isset($courses) && count($courses) > 0): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-4">
                        <div class="custom-media">
                            <!-- If you have a real image path, replace 'default_course.webp' accordingly -->
                            <a href="#">
                                <img src="<?= thumb($course->image,170,249) ?>" alt="Course Image" class="img-fluid">
                            </a>
                            <div class="custom-media-body">
                                <div class="d-flex justify-content-between pb-3">
                                    <!-- Example placeholders: "Coming Soon" & rating -->
                                    <div class="text-primary">
                                        <span class="uil uil-book-open"></span>
                                        <span>Coming Soon</span>
                                    </div>
                                    <div class="review">
                                        <span class="icon-star"></span>
                                        <span>4.8</span>
                                    </div>
                                </div>

                                <!-- Course Title -->
                                <h3><?= esc($course->course_name) ?></h3>

                                <!-- Price & Buttons -->
                                <div class="border-top d-flex justify-content-between pt-3 mt-3 align-items-center">
                                    <div>
                                        <span class="price">$<?= esc(number_format($course->price, 2)) ?></span>
                                    </div>
                                    <!-- Details Link -->
                                    <a href="<?= base_url('courses/course_details/' . $course->slug) ?>" class="btn btn-primary">
                                        تفاصيل
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- If no courses found -->
                <div class="col-12">
                    <p class="text-center">No courses found.</p>
                </div>
            <?php endif; ?>
        </div> <!-- /.row -->

        <!-- Pagination (optional) -->
        <?php if (isset($pager)): ?>
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <?= $pager->links() ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- End of Courses Section -->

<?= $this->endSection(); ?>
