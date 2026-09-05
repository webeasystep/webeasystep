<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="prep-page-wrapper">
    <div class="container">
        
        <!-- Compact Header & Breadcrumbs -->
        <div class="prep-header-bar" data-aos="fade-up">
            <div class="prep-breadcrumb">
                <a href="<?= base_url() ?>"><i class="fas fa-home ml-1"></i> الرئيسية</a>
                <span class="mx-2">/</span>
                <span>السنة الأولى المشتركة (التحضيرية)</span>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h1 class="prep-title">مقررات السنة الأولى المشتركة</h1>
                    <p class="prep-subtitle">شروحات شاملة، حل واجبات، وتجميعات اختبارات لكل مواد السنة التحضيرية في الجامعة السعودية الإلكترونية SEU</p>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge badge-light border text-muted px-3 py-2" style="font-size: 13px;">
                        <i class="fas fa-university text-primary ml-1"></i> الجامعة السعودية الإلكترونية
                    </span>
                </div>
            </div>
        </div>

        <?= $this->include('site_layout/site_msg'); ?>

        <!-- 1. Featured Bundle Promo Card (Full-Width, Zero Wasted Space) -->
        <?php if (!empty($bundles)): ?>
            <?php foreach ($bundles as $bundle): ?>
                <div class="bundle-promo-card" data-aos="fade-up">
                    <div class="row no-gutters">
                        <div class="col-lg-8 col-md-7 p-4 p-lg-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="bundle-badge-top">
                                    <i class="fas fa-fire ml-1"></i> العرض الأوفر والأكثر طلباً لطلاب التحضيري
                                </div>
                                <h2 class="bundle-promo-title"><?= esc($bundle->bundle_title) ?></h2>
                                <p class="bundle-promo-desc mb-3">
                                    <?= esc($bundle->description) ?>
                                </p>
                                
                                <div class="bundle-chips-list">
                                    <span class="bundle-chip"><i class="fas fa-square-root-alt ml-1"></i> مقدمة في الرياضيات (ريض 001)</span>
                                    <span class="bundle-chip"><i class="fas fa-laptop-code ml-1"></i> أساسيات الحاسب (عال 001)</span>
                                    <span class="bundle-chip"><i class="fas fa-comments ml-1"></i> مهارات الاتصال (علم 001)</span>
                                    <span class="bundle-chip"><i class="fas fa-book-reader ml-1"></i> المهارات الأكاديمية (نهج 001)</span>
                                </div>
                            </div>

                            <div class="mt-2 d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                <span><i class="fas fa-check-circle text-success ml-1"></i> شروحات كاملة</span>
                                <span class="mx-2">•</span>
                                <span><i class="fas fa-check-circle text-success ml-1"></i> تجميعات الميد والفاينل</span>
                                <span class="mx-2">•</span>
                                <span><i class="fas fa-check-circle text-success ml-1"></i> بنوك أسئلة ومتابعة</span>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-5 p-3 p-lg-4">
                            <div class="bundle-price-box">
                                <?php if ($bundle->original_price > $bundle->bundle_price): ?>
                                    <div class="bundle-price-original"><?= number_format($bundle->original_price) ?> <?= riyal_icon('14px', '#94a3b8') ?></div>
                                <?php endif; ?>
                                <div class="bundle-price-current">
                                    <?= number_format($bundle->bundle_price) ?> <?= riyal_icon('24px', '#136ad5') ?>
                                </div>
                                <?php if ($bundle->original_price > $bundle->bundle_price): ?>
                                    <?php $saving = $bundle->original_price - $bundle->bundle_price; ?>
                                    <div>
                                        <span class="bundle-save-tag"><i class="fas fa-tags ml-1"></i> توفير <?= number_format($saving) ?> <?= riyal_icon('12px', '#15803d') ?> فوراً</span>
                                    </div>
                                <?php endif; ?>
                                <div class="bundle-btn-actions d-flex flex-column" style="gap: 10px; margin-top: 14px;">
                                    <button class="btn btn-bundle-cta w-100" onclick="addToCart('bundle', <?= $bundle->id ?>)">
                                        <i class="fas fa-cart-plus ml-2"></i> اشترك في الباقة الآن
                                    </button>
                                    <button type="button" class="btn btn-bundle-preview w-100" id="btnPreviewBundle">
                                        <i class="fas fa-play-circle ml-1"></i> تجربة مجانية
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- 2. Individual Courses Grid (Immediately Below with Compact Section Header) -->
        <div class="section-header-compact" id="individual-courses-section" data-aos="fade-up">
            <h2><i class="fas fa-book-open text-primary ml-2"></i> المقررات الفردية للسنة الأولى المشتركة</h2>
            <span class="count-badge"><?= !empty($courses) ? count($courses) : 0 ?> مقررات</span>
        </div>

        <div class="row">
            <?php
            $gradients = [
                'linear-gradient(135deg, #136ad5 0%, #00aeff 100%)',
                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            ];
            $icons = ['fa-cubes', 'fa-project-diagram', 'fa-database', 'fa-network-wired'];
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

                    $isOpen = isset($course['is_open']) && $course['is_open'] == 1;
                    $courseUrl = $course['course_url'] ?? base_url('courses/course_details/' . $course['slug']);
                    $discountedPrice = $course['course_price'] ?? 149;
                    ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="prep-course-card">
                            
                            <!-- Course Image -->
                            <?php if ($hasImage): ?>
                                <?php if ($isOpen): ?><a href="<?= $courseUrl ?>" class="prep-course-image d-block" style="text-decoration: none;"><?php else: ?><div class="prep-course-image"><?php endif; ?>
                                    <img src="<?= thumb($course['image'], 400, 200) ?>" alt="<?= esc($course['course_title']) ?>" loading="lazy" decoding="async">
                                    <?php if($isOpen): ?>
                                        <span class="badge badge-success position-absolute" style="top: 10px; right: 10px; font-size: 11px; padding: 4px 10px; border-radius: 12px;">مفتوح للحجز</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px; font-size: 11px; padding: 4px 10px; border-radius: 12px;">مغلق الحجز</span>
                                    <?php endif; ?>
                                <?= $isOpen ? '</a>' : '</div>' ?>
                            <?php else: ?>
                                <?php if ($isOpen): ?><a href="<?= $courseUrl ?>" class="prep-course-image d-block" style="background: <?= $gradient ?>; text-decoration: none;"><?php else: ?><div class="prep-course-image" style="background: <?= $gradient ?>;"><?php endif; ?>
                                    <i class="fas <?= $icon ?>" style="font-size: 2.5rem; color: #ffffff;"></i>
                                    <?php if($isOpen): ?>
                                        <span class="badge badge-success position-absolute" style="top: 10px; right: 10px; font-size: 11px; padding: 4px 10px; border-radius: 12px;">مفتوح للحجز</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px; font-size: 11px; padding: 4px 10px; border-radius: 12px;">مغلق الحجز</span>
                                    <?php endif; ?>
                                <?= $isOpen ? '</a>' : '</div>' ?>
                            <?php endif; ?>

                            <!-- Course Body -->
                            <div class="prep-course-body">
                                <h3 class="prep-course-title">
                                    <a href="<?= $courseUrl ?>" class="text-dark" style="text-decoration: none;"><?= esc($course['course_title']) ?></a>
                                </h3>
                                <div class="prep-course-code">
                                    <?= esc($course['short_desc'] ?? '') ?>
                                </div>
                                
                                <div class="prep-course-instructor">
                                    <i class="fas fa-user-tie text-primary ml-1"></i> <?= esc($course['instructor_name'] ?? 'م. أحمد فخر الدين') ?>
                                </div>

                                <div class="prep-course-footer">
                                    <div class="prep-course-price">
                                        <?php if (!empty($course['is_free']) && $course['is_free']): ?>
                                            <span class="text-success" style="font-size: 1rem;">مجاني</span>
                                        <?php else: ?>
                                            <span><?= number_format($discountedPrice) ?> <?= riyal_icon('14px', 'currentColor') ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="prep-course-actions d-flex align-items-center position-relative" style="gap: 6px;">
                                        <?php if ($courseIndex === 1): ?>
                                            <div class="preview-guide-badge d-none" id="previewGuideBadge">
                                                <i class="fas fa-hand-point-down ml-1 text-warning"></i> تجربة مجانية لأول موديول من هنا!
                                            </div>
                                        <?php endif; ?>
                                        <a href="<?= $courseUrl ?>#first-unit" class="btn btn-course-preview <?= ($courseIndex === 1) ? 'first-preview-btn' : '' ?>" title="تجربة مجانية">
                                            <i class="fas fa-play-circle"></i> تجربة مجانية
                                        </a>
                                        <?php if($isOpen): ?>
                                            <button class="btn btn-primary btn-course-enroll" onclick="addToCart('course', <?= $course['id'] ?>)">
                                                <i class="fas fa-cart-plus ml-1"></i> أضف للسلة
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-secondary btn-course-enroll" onclick="handleSubscribe('<?= esc($course['course_title']) ?>')">
                                                أعلمني
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Modal Guide for Previewing Courses -->
<div class="modal fade" id="bundlePreviewGuideModal" tabindex="-1" role="dialog" aria-labelledby="bundlePreviewGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content preview-guide-modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <div class="guide-modal-icon-badge">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="bundlePreviewGuideModalLabel">
                            تجربة مجانية للمقررات
                        </h5>
                        <small class="text-muted">تجربة مجانية كاملة قبل الاشتراك</small>
                    </div>
                </div>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-3 pb-4">
                <div class="guide-modal-alert mb-3">
                    <p class="mb-0">
                        <strong class="d-block mb-1" style="font-size: 1.05rem;">
                            <i class="fas fa-gift ml-1 text-warning"></i> أول موديول (الوحدة الأولى) مجاني في جميع المقررات!
                        </strong>
                        تقدر تشوف وتدرس محتوى الوحدة الأولى بالكامل لأي مقرر لتجربة أسلوب الشرح وجودة الدروس مجاناً بدون الحاجة للاشتراك المسبق.
                    </p>
                </div>

                <div class="guide-modal-card-demo p-3 rounded-lg text-center mb-3">
                    <div class="text-muted mb-2" style="font-size: 0.88rem;">
                        ابحث عن هذا الزر في بطاقة أي مقرر أدناه:
                    </div>
                    <div class="d-inline-flex align-items-center demo-preview-box">
                        <span class="btn btn-course-preview demo-btn">
                            <i class="fas fa-play-circle"></i> تجربة مجانية
                        </span>
                        <span class="demo-pointer-hand animated-bounce-x"><i class="fas fa-hand-point-right text-warning mr-2"></i></span>
                        <span class="text-muted mr-1" style="font-size: 0.85rem;">اضغط هنا لفتح أول موديول فوراً</span>
                    </div>
                </div>

                <p class="text-muted text-center mb-0" style="font-size: 0.9rem;">
                    اختر أي مقرر ترغب بتجربته واضغط على زر <strong>(تجربة مجانية)</strong> لبدء المشاهدة فوراً.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-primary px-4 py-2" data-dismiss="modal" id="btnStartExploreCourses" style="border-radius: 12px; font-weight: 700;">
                    <i class="fas fa-arrow-down ml-1"></i> تصفح المقررات وجرّب الآن
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnPreviewBundle = document.getElementById('btnPreviewBundle');
    const coursesSection = document.getElementById('individual-courses-section');
    const guideBadge = document.getElementById('previewGuideBadge');
    const firstPreviewBtn = document.querySelector('.first-preview-btn');

    function highlightFirstCoursePreview() {
        if (firstPreviewBtn) {
            firstPreviewBtn.classList.add('preview-pulse-highlight');
            if (guideBadge) {
                guideBadge.classList.remove('d-none');
            }

            const removeHighlight = function() {
                firstPreviewBtn.classList.remove('preview-pulse-highlight');
                if (guideBadge) guideBadge.classList.add('d-none');
                firstPreviewBtn.removeEventListener('click', removeHighlight);
                firstPreviewBtn.removeEventListener('mouseenter', removeHighlight);
            };

            firstPreviewBtn.addEventListener('click', removeHighlight);
            firstPreviewBtn.addEventListener('mouseenter', removeHighlight);

            setTimeout(removeHighlight, 9000);
        }
    }

    if (btnPreviewBundle) {
        btnPreviewBundle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 1. Smooth scroll down to individual courses
            if (coursesSection) {
                const nav = document.querySelector('.site-nav') || document.querySelector('header') || document.querySelector('.navbar');
                const navOffset = nav ? nav.offsetHeight : 70;
                const topPos = coursesSection.getBoundingClientRect().top + window.pageYOffset - navOffset - 20;
                window.scrollTo({
                    top: Math.max(0, topPos),
                    behavior: 'smooth'
                });
            }

            // 2. Open Guide Modal
            setTimeout(function() {
                if (typeof $ !== 'undefined' && typeof $('#bundlePreviewGuideModal').modal === 'function') {
                    $('#bundlePreviewGuideModal').modal('show');
                }
            }, 350);
        });
    }

    if (typeof $ !== 'undefined') {
        $('#bundlePreviewGuideModal').on('hidden.bs.modal', function () {
            highlightFirstCoursePreview();
        });
    }
    const btnStart = document.getElementById('btnStartExploreCourses');
    if (btnStart) {
        btnStart.addEventListener('click', function() {
            setTimeout(highlightFirstCoursePreview, 250);
        });
    }
});
</script>

<?= $this->endSection(); ?>
