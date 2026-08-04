<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Hero Section -->
<div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/main_banner.webp'); padding: 120px 0 80px 0;">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">
                    مقررات السنة الأولى المشتركة
                </h1>
                <p class="mb-4 text-white" style="font-size: 1.1rem; line-height: 1.6; max-width: 800px; margin: 0 auto;" data-aos="fade-up" data-aos-delay="200">
                    كل ما تحتاجه لدراسة مقررات السنة الأولى المشتركة في الجامعة السعودية الإلكترونية، من شروحات مبسطة وملخصات وتدريبات ومراجعات للميد والفاينل.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Courses Section -->
<div class="untree_co-section bg-light" id="courses">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">اختر المادة التي تريد دراستها</h2>
            </div>
        </div>
        <div class="row">
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
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <?php
                    // Only show published courses, ignore waiting list courses
                    if (isset($course['waiting_list']) && $course['waiting_list'] == 1) continue;

                    $hasImage = !empty($course['image']) && $course['image'] !== '[]';
                    $gradient = $gradients[$courseIndex % count($gradients)];
                    $icon = $icons[$courseIndex % count($icons)];
                    $courseIndex++;
                    ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="course-card h-100">
                            <?php if ($hasImage): ?>
                                <div class="course-card-image">
                                    <img src="<?= thumb($course['image'], 400, 200) ?>" alt="<?= esc($course['course_title']) ?>" class="course-img" loading="lazy" decoding="async">
                                    <?php if(isset($course['is_open']) && $course['is_open'] == 1): ?>
                                        <span class="course-badge badge-open" style="background-color: #d4f8e8; color: #20b080;">مفتوح للحجز</span>
                                    <?php else: ?>
                                        <span class="course-badge badge-closed" style="background-color: #fee2e2; color: #ef4444;">مغلق الحجز</span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="course-card-image" style="background: <?= $gradient ?>;">
                                    <div class="course-icon"><i class="fas <?= $icon ?>"></i></div>
                                    <?php if(isset($course['is_open']) && $course['is_open'] == 1): ?>
                                        <span class="course-badge badge-open" style="background-color: #d4f8e8; color: #20b080;">مفتوح للحجز</span>
                                    <?php else: ?>
                                        <span class="course-badge badge-closed" style="background-color: #fee2e2; color: #ef4444;">مغلق الحجز</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="course-card-body d-flex flex-column">
                                <h5 class="course-title"><?= esc($course['course_title']) ?></h5>
                                <div class="course-codes mb-auto"><?= esc($course['short_desc'] ?? '') ?></div>
                                
                                <div class="course-instructor mb-3 mt-3 text-muted" style="font-size: 0.95rem; font-weight: 500;">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="icon-user mr-2" style="font-size: 1.1rem; color: #136ad5;"></span> 
                                        <span><?= esc($course['instructor_name'] ?? 'أحمد فخر الدين') ?></span>
                                    </div>
                                    <div class="d-flex align-items-center" style="font-size: 0.85rem;">
                                        <span>الجامعة السعودية الإلكترونية</span>
                                    </div>
                                </div>

                                <div class="course-footer mt-auto">
                                    <div class="course-price">
                                        <?php if (!empty($course['is_free']) && $course['is_free']): ?>
                                            <span class="price-amount text-success">مجاني</span>
                                        <?php else: ?>
                                            <?php
                                            $discountedPrice = $course['course_price'] ?? 149;
                                            ?>
                                            <span class="price-amount"><?= number_format($discountedPrice) ?></span>
                                            <svg class="riyal-icon" width="16" height="16" viewBox="0 0 1124.14 1256.39" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; margin-right: 4px; vertical-align: middle;">
                                                <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                                <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l334.33-71.05v170.26l-358.3,76.14c-20.06,44.47-33.32,92.75-38.4,143.37l375.04-79.7c30.53-6.35,56.77-24.4,73.83-49.24l68.78-101.97v-.02c7.14-10.55,11.3-23.27,11.3-36.97v-149.98l132.25-28.11v270.4l424.53-90.28Z"/>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(isset($course['is_open']) && $course['is_open'] == 1): ?>
                                        <a href="<?= $course['course_url'] ?? base_url('courses/course_details/' . $course['slug']) ?>" class="btn btn-subscribe" style="padding: 10px 15px;">عرض الكورس</a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-subscribe" style="padding: 10px 15px; background: #6c757d; border-color: #6c757d;" onclick="handleSubscribe('<?= esc($course['course_title']) ?>')">أعلمني عند التوفر</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
