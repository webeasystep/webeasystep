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
            <?php foreach ($courses as $course): ?>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-4">
                    <div class="custom-media">
                        <!-- Image -->
                        <img
                                alt="<?= esc($course['course_name']) ?>"
                                style="height: 200px; object-fit: cover;"
                                src="<?= thumb($course['image'], 170, 249) ?>"
                                class="card-img-top"
                        >
                        <div class="custom-media-body" style="padding: 15px;">
                            <!-- Title -->
                            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">
                                <?= esc($course['course_name']) ?>
                            </h3>

                            <!-- Short Description -->
                            <div style="font-size: 14px; color: #666; margin-bottom: 10px;">
                                <?= esc($course['short_desc']) ?>
                            </div>

                            <!-- Lesson count -->
                            <div class="d-flex justify-content-between pb-3">
                              <span style="font-size: 14px; color: #666;">
                                <?= $course['lesson_count'] ?> درس
                              </span>
                            </div>

                            <!-- Price & Action Buttons -->
                            <div class="border-top d-flex justify-content-between pt-3 mt-3 align-items-center">
                                <div>
                                <span class="price" style="font-size: 1rem; font-weight: bold;">
                                  $<?= esc(number_format($course['price'], 2)) ?>
                                </span>
                                </div>
                                <div class="d-inline-flex" style="gap: 5px;">
                                    <!-- Details button -->
                                    <a href="<?= base_url('courses/course_details/' . $course['slug']) ?>"
                                       class="btn btn-primary btn-sm">
                                        تفاصيل
                                    </a>

                                    <!-- If the user is already enrolled, show "استكمل" / "Go to Course" -->
                                    <?php if ($course['is_enrolled']): ?>
                                        <a href="<?= base_url('courses/course_view/' . $course['slug']) ?>"
                                           class="btn btn-secondary btn-sm"
                                           style="font-weight:600;">
                                            استكمل
                                        </a>
                                    <?php else: ?>
                                        <!-- Otherwise, show Register button (free or paid) -->
                                        <?php if (!empty($course['is_free'])): ?>
                                            <a href="<?= base_url('checkout/' . $course['id']) ?>"
                                               class="btn btn-warning btn-sm"
                                               style="font-weight:600;">
                                                سجل مجانًا
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('checkout/' . $course['id']) ?>"
                                               class="btn btn-success btn-sm"
                                               style="font-weight:600;">
                                                سجل الآن
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div><!-- /.custom-media-body -->
                    </div><!-- /.custom-media -->
                </div><!-- /.col -->
            <?php endforeach; ?>
        </div><!-- /.row -->
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
