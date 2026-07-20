<!-- app/Views/site/courses.php -->
<?= $this->extend('site_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Start of Courses Section -->
<div id="bookNow" class="untree_co-section bg-light">
    <div class="container">

        <!-- Section Heading & Intro -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">كورسات الأساسيات</h2>
                <p>ابدأ الان في تعلم أساسيات البرمجة بأبسط طريقة.</p>
            </div>
        </div>

        <?php
        $gradients = [
            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
            'linear-gradient(135deg, #ff0844 0%, #ffb199 100%)',
        ];
        $icons = ['fa-cubes', 'fa-project-diagram', 'fa-database', 'fa-network-wired', 'fa-cogs', 'fa-globe', 'fa-brain'];
        $courseIndex = 0;
        ?>
        <!-- Courses Grid -->
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <?php
                $hasImage = !empty($course['image']) && $course['image'] !== '[]';
                $gradient = $gradients[$courseIndex % count($gradients)];
                $icon = $icons[$courseIndex % count($icons)];
                $courseIndex++;
                ?>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-4">
                    <div class="custom-media">
                        <!-- Image -->
                        <?php if ($hasImage): ?>
                            <img
                                    alt="<?= esc($course['course_title']) ?>"
                                    style="height: 200px; width: 100%; object-fit: cover;"
                                    src="<?= thumb($course['image'], 400, 225) ?>"
                                    class="card-img-top"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="card-img-top"
                                 style="height: 200px; width: 100%; background: <?= $gradient ?>; display: none; align-items: center; justify-content: center;">
                                <i class="fas <?= $icon ?>" style="font-size: 3rem; color: rgba(255,255,255,0.8);"></i>
                            </div>
                        <?php else: ?>
                            <div class="card-img-top"
                                 style="height: 200px; width: 100%; background: <?= $gradient ?>; display: flex; align-items: center; justify-content: center;">
                                <i class="fas <?= $icon ?>" style="font-size: 3rem; color: rgba(255,255,255,0.8);"></i>
                            </div>
                        <?php endif; ?>
                        <div class="custom-media-body" style="padding: 15px;">
                            <!-- Title -->
                            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">
                                <?= esc($course['course_title']) ?>
                            </h3>

                            <!-- Short Description -->
                            <div class="text-secondary" style="font-size: 14px; margin-bottom: 10px;">
                                <?= esc($course['short_desc']) ?>
                            </div>


                            <!-- Course Info & Action Buttons -->
                            <div class="border-top d-flex justify-content-between pt-3 mt-3 align-items-center">
                                <div class="course-stats text-secondary">
                                <span class="units-count" style="font-size: 0.9rem; margin-left: 15px;">
                                  <i class="fas fa-book"></i> <?= $course['unit_count'] ?? 0 ?> وحدة
                                </span>
                                <span class="quizzes-count" style="font-size: 0.9rem;">
                                  <i class="fas fa-question-circle"></i> <?= $course['quiz_count'] ?? 0 ?> اختبار
                                </span>
                                </div>
                                <div class="d-inline-flex" style="gap: 5px;">
                                    <!-- شراء وحدات button - always visible -->
                                    <a href="<?= base_url('courses/course_details/' . $course['slug']) ?>"
                                       class="btn btn-primary btn-sm">
                                        شراء وحدات
                                    </a>
                                    <!-- مشاهدة button - only visible if user is logged in and enrolled -->
                                    <?php if (auth()->loggedIn() && $course['is_enrolled']): ?>
                                        <a href="<?= base_url('courses/course_view/' . $course['slug']) ?>"
                                           class="btn btn-secondary btn-sm"
                                           style="font-weight:600;">
                                            مشاهدة
                                        </a>
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
