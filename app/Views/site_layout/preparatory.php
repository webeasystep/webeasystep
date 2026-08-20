<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .prep-page-wrapper {
        background-color: #f8fafc;
        padding-top: 25px;
        padding-bottom: 50px;
    }
    
    /* Compact Breadcrumb & Title Bar */
    .prep-header-bar {
        margin-bottom: 25px;
    }
    .prep-breadcrumb {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 8px;
    }
    .prep-breadcrumb a {
        color: #136ad5;
        text-decoration: none;
    }
    .prep-title {
        font-size: 1.85rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .prep-subtitle {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Full-Width High-Conversion Bundle Hero Card */
    .bundle-promo-card {
        background: #ffffff;
        border-radius: 18px;
        border: 2px solid #136ad5;
        box-shadow: 0 10px 30px rgba(19, 106, 213, 0.08);
        overflow: hidden;
        margin-bottom: 35px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .bundle-promo-card:hover {
        box-shadow: 0 15px 40px rgba(19, 106, 213, 0.14);
        transform: translateY(-2px);
    }
    .bundle-badge-top {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #ec661f, #ff8c42);
        color: #ffffff;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .bundle-promo-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .bundle-promo-desc {
        color: #475569;
        font-size: 0.92rem;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    .bundle-chips-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }
    .bundle-chip {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .bundle-price-box {
        background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
        border-radius: 14px;
        padding: 24px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        border: 1px solid #bae6fd;
    }
    .bundle-price-original {
        color: #94a3b8;
        text-decoration: line-through;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .bundle-price-current {
        font-size: 2.2rem;
        font-weight: 900;
        color: #136ad5;
        line-height: 1.1;
        margin-bottom: 6px;
    }
    .bundle-save-tag {
        display: inline-block;
        background: #dcfce7;
        color: #15803d;
        border-radius: 12px;
        padding: 3px 10px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 16px;
    }
    .btn-bundle-cta {
        background: linear-gradient(135deg, #136ad5 0%, #0b5cbf 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 6px 18px rgba(19, 106, 213, 0.25);
    }
    .btn-bundle-cta:hover {
        background: linear-gradient(135deg, #0b5cbf 0%, #094794 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(19, 106, 213, 0.35);
    }

    /* Compact Courses Section */
    .section-header-compact {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 12px;
    }
    .section-header-compact h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }
    .section-header-compact .count-badge {
        background: #e2e8f0;
        color: #475569;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    /* Course Card Aesthetics */
    .prep-course-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid #e2e8f0;
    }
    .prep-course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }
    .prep-course-image {
        position: relative;
        width: 100%;
        aspect-ratio: 2 / 1;
        overflow: hidden;
        background-color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .prep-course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        display: block;
        transition: transform 0.5s ease;
    }
    .prep-course-card:hover .prep-course-image img {
        transform: scale(1.04);
    }
    .prep-course-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .prep-course-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .prep-course-code {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 12px;
    }
    .prep-course-instructor {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 14px;
        margin-top: auto;
    }
    .prep-course-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
        margin-top: auto;
    }
    .prep-course-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
    }
    .btn-course-enroll {
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 8px;
    }
</style>

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
                                    <div class="bundle-price-original"><?= number_format($bundle->original_price) ?> ر.س</div>
                                <?php endif; ?>
                                <div class="bundle-price-current">
                                    <?= number_format($bundle->bundle_price) ?> <small style="font-size: 1.1rem;">ر.س</small>
                                </div>
                                <?php if ($bundle->original_price > $bundle->bundle_price): ?>
                                    <?php $saving = $bundle->original_price - $bundle->bundle_price; ?>
                                    <div>
                                        <span class="bundle-save-tag"><i class="fas fa-tags ml-1"></i> توفير <?= number_format($saving) ?> ر.س فوراً</span>
                                    </div>
                                <?php endif; ?>
                                <button class="btn btn-bundle-cta" onclick="addToCart('bundle', <?= $bundle->id ?>)">
                                    <i class="fas fa-cart-plus ml-2"></i> اشترك في الباقة الآن
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- 2. Individual Courses Grid (Immediately Below with Compact Section Header) -->
        <div class="section-header-compact" data-aos="fade-up">
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
                                            <span><?= number_format($discountedPrice) ?> <small style="font-size: 11px;">ر.س</small></span>
                                        <?php endif; ?>
                                    </div>
                                    
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
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>
