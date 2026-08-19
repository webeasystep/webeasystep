<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<?php
$gradients = [
    'linear-gradient(135deg, #136ad5 0%, #00aeff 100%)',
    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
    'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
];
$icons = ['fa-laptop-code', 'fa-database', 'fa-network-wired', 'fa-cogs', 'fa-square-root-alt', 'fa-brain', 'fa-project-diagram'];
$courseIndex = 0;
?>

<style>
    /* Hero Section */
    .courses-hero {
        background: linear-gradient(135deg, rgba(19, 106, 213, 0.94), rgba(10, 45, 95, 0.92)), url('<?= base_url('site/images/main_banner.webp') ?>') center/cover no-repeat;
        padding: 70px 0 60px;
        color: #ffffff;
        text-align: center;
        position: relative;
    }
    .courses-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
        color: #ffffff;
    }
    .courses-hero p {
        font-size: 1.15rem;
        max-width: 800px;
        margin: 0 auto 30px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.7;
    }
    .courses-search-bar {
        max-width: 620px;
        margin: 0 auto;
        position: relative;
    }
    .courses-search-bar input {
        border-radius: 30px;
        padding: 14px 25px 14px 120px;
        height: auto;
        font-size: 1rem;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .courses-search-bar button {
        position: absolute;
        left: 5px;
        top: 5px;
        bottom: 5px;
        border-radius: 25px;
        padding: 0 25px;
        font-weight: 600;
    }

    /* Filter Tabs */
    .courses-filter-nav {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
    }
    .filter-tab-btn {
        background: #ffffff;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        border-radius: 25px;
        padding: 9px 22px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }
    .filter-tab-btn:hover {
        color: #136ad5;
        border-color: #136ad5;
        background: #f0f7ff;
    }
    .filter-tab-btn.active {
        background: #136ad5;
        color: #ffffff;
        border-color: #136ad5;
        box-shadow: 0 4px 15px rgba(19, 106, 213, 0.25);
    }
    .filter-tab-btn .badge-counter {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 0.8rem;
        margin-right: 6px;
    }
    .filter-tab-btn.active .badge-counter {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Course Cards */
    .course-dir-card {
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
    }
    .course-dir-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(19, 106, 213, 0.12);
    }
    .course-dir-img-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #eef2f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .course-dir-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .course-dir-card:hover .course-dir-img-wrapper img {
        transform: scale(1.06);
    }
    .course-dir-icon-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 3rem;
    }
    .course-code-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(19, 106, 213, 0.92);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .course-status-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .course-status-open {
        background-color: #d4f8e8;
        color: #108e64;
    }
    .course-status-closed {
        background-color: #fee2e2;
        color: #ef4444;
    }

    .course-dir-body {
        padding: 22px 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .course-dir-college {
        font-size: 0.82rem;
        color: #136ad5;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .course-dir-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1.45;
        margin-bottom: 10px;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s ease;
    }
    .course-dir-title:hover {
        color: #136ad5;
        text-decoration: none;
    }
    .course-dir-desc {
        color: #718096;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    .course-dir-stats {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf2f7;
    }
    .course-dir-instructor {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #4a5568;
        font-weight: 500;
        margin-bottom: 15px;
    }
    .course-dir-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 10px;
    }
    .course-price-wrap {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }
    .course-price-original {
        font-size: 0.9rem;
        color: #a0aec0;
        text-decoration: line-through;
    }
    .course-price-current {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1a202c;
    }
    .course-cta-btns {
        display: flex;
        gap: 6px;
    }
    .btn-course-details {
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Course Request CTA Banner */
    .course-request-cta {
        background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
        border: 2px dashed #bfdbfe;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        margin-top: 50px;
    }
    .course-request-cta h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 10px;
    }
    .course-request-cta p {
        font-size: 1rem;
        color: #4b5563;
        max-width: 650px;
        margin: 0 auto 20px;
        line-height: 1.6;
    }
</style>

<!-- Hero Section -->
<div class="courses-hero">
    <div class="container">
        <h1 data-aos="fade-up">مقررات الجامعة السعودية الإلكترونية</h1>
        <p data-aos="fade-up" data-aos-delay="100">
            شروحات شاملة، حل واجبات، تجميعات اختبارات ومتابعة مستمرة لكلية الحوسبة والمعلوماتية والسنة الأولى المشتركة SEU
        </p>

        <!-- Search Bar -->
        <div class="courses-search-bar" data-aos="fade-up" data-aos-delay="200">
            <div class="position-relative">
                <input type="text" id="courseSearchInput" class="form-control" placeholder="ابحث باسم المقرر أو الرمز (مثال: CS140، MATH 001، هياكل البيانات...)" onkeyup="filterCoursesLive()" aria-label="ابحث عن مقرر">
                <button type="button" class="btn btn-primary" onclick="filterCoursesLive()">
                    <i class="fas fa-search ml-1"></i> بحث
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Courses Directory Section -->
<div class="untree_co-section bg-light py-5">
    <div class="container">

        <!-- Category / College Filter Tabs -->
        <div class="courses-filter-nav" data-aos="fade-up">
            <button type="button" class="filter-tab-btn active" onclick="filterByCollege('all', this)">
                جميع المقررات <span class="badge-counter"><?= count($courses) ?></span>
            </button>
            <button type="button" class="filter-tab-btn" onclick="filterByCollege('prep', this)">
                السنة الأولى المشتركة (التحضيرية)
            </button>
            <button type="button" class="filter-tab-btn" onclick="filterByCollege('computing', this)">
                كلية الحوسبة والمعلوماتية
            </button>
        </div>

        <!-- Courses Grid -->
        <div class="row" id="coursesGridContainer">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <?php
                        $course = (array) $course;
                        $isOpen = isset($course['is_open']) && $course['is_open'] == 1;
                        $hasImage = !empty($course['image']) && $course['image'] !== '[]';
                        $gradient = $gradients[$courseIndex % count($gradients)];
                        $icon = $icons[$courseIndex % count($icons)];
                        $courseIndex++;

                        $courseUrl = site_url('courses/course_details/' . ($course['slug'] ?: $course['id']));
                        $viewUrl = site_url('courses/course_view/' . ($course['slug'] ?: $course['id']));
                        $isEnrolled = !empty($course['is_enrolled']);
                        $isFree = !empty($course['is_free']);
                        $courseCode = $course['course_code'] ?? '';
                        $collegeName = $course['college_name'] ?? 'الجامعة السعودية الإلكترونية';

                        // Classification for college filter
                        $collegeType = 'computing';
                        if (str_contains($courseCode, '001') || str_contains($course['course_title'], '001') || str_contains($collegeName, 'الأولى') || str_contains($collegeName, 'التحضيرية')) {
                            $collegeType = 'prep';
                        }

                        $courseSearchText = mb_strtolower(($course['course_title'] ?? '') . ' ' . ($course['course_name_en'] ?? '') . ' ' . $courseCode . ' ' . $collegeName . ' ' . ($course['short_desc'] ?? ''));
                    ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 course-grid-item" data-college="<?= $collegeType ?>" data-search="<?= esc($courseSearchText) ?>">
                        <div class="course-dir-card">
                            <!-- Image / Thumbnail -->
                            <div class="course-dir-img-wrapper" style="<?= !$hasImage ? 'background: ' . $gradient . ';' : '' ?>">
                                <a href="<?= $courseUrl ?>" class="d-block w-100 h-100" style="text-decoration: none;">
                                    <?php if ($hasImage): ?>
                                        <img src="<?= thumb($course['image'], 400, 220) ?>" alt="<?= esc($course['course_title']) ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="course-dir-icon-placeholder">
                                            <i class="fas <?= $icon ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <?php if (!empty($courseCode)): ?>
                                    <span class="course-code-badge"><?= esc($courseCode) ?></span>
                                <?php endif; ?>

                                <?php if ($isOpen): ?>
                                    <span class="course-status-badge course-status-open"><i class="fas fa-check-circle ml-1"></i> متاح للحجز</span>
                                <?php else: ?>
                                    <span class="course-status-badge course-status-closed"><i class="fas fa-clock ml-1"></i> قائمة الانتظار</span>
                                <?php endif; ?>
                            </div>

                            <!-- Body Content -->
                            <div class="course-dir-body">
                                <div class="course-dir-college">
                                    <i class="fas fa-graduation-cap ml-1"></i> <?= esc($collegeName) ?>
                                </div>
                                <h3 class="h5">
                                    <a href="<?= $courseUrl ?>" class="course-dir-title">
                                        <?= esc($course['course_title']) ?>
                                    </a>
                                </h3>
                                <p class="course-dir-desc">
                                    <?= esc($course['short_desc'] ?: ($course['course_desc'] ? strip_tags(mb_substr($course['course_desc'], 0, 100)) : 'شرح متكامل لجميع مفردات المقرر مع ملخصات وتجميعات اختبارات.')) ?>
                                </p>

                                <!-- Stats Row -->
                                <div class="course-dir-stats">
                                    <span><i class="fas fa-book-open ml-1 text-primary"></i> <?= (int)($course['unit_count'] ?? 0) ?> وحدة</span>
                                    <span><i class="fas fa-question-circle ml-1 text-primary"></i> <?= (int)($course['quiz_count'] ?? 0) ?> اختبار</span>
                                </div>

                                <!-- Instructor -->
                                <div class="course-dir-instructor">
                                    <i class="far fa-user-circle ml-1 text-primary" style="font-size: 1.1rem;"></i>
                                    <span>المحاضر: <strong><?= esc($course['instructor_name'] ?? 'أحمد فخر الدين') ?></strong></span>
                                </div>

                                <!-- Pricing & Actions -->
                                <div class="course-dir-footer">
                                    <div class="course-price-wrap">
                                        <?php if ($isFree): ?>
                                            <span class="text-success font-weight-bold" style="font-size: 1.2rem;">مجاني</span>
                                        <?php else: ?>
                                            <?php
                                                $price = (float)($course['course_price'] ?? 135);
                                                $originalPrice = ceil($price / 0.75);
                                            ?>
                                            <span class="course-price-original"><?= number_format($originalPrice) ?></span>
                                            <span class="course-price-current"><?= number_format($price) ?></span>
                                            <svg class="riyal-icon" width="15" height="15" viewBox="0 0 1124.14 1256.39" xmlns="http://www.w3.org/2000/svg" style="fill: currentColor; vertical-align: middle;">
                                                <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                                <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l334.33-71.05v170.26l-358.3,76.14c-20.06,44.47-33.32,92.75-38.4,143.37l375.04-79.7c30.53-6.35,56.77-24.4,73.83-49.24l68.78-101.97v-.02c7.14-10.55,11.3-23.27,11.3-36.97v-149.98l132.25-28.11v270.4l424.53-90.28Z"/>
                                            </svg>
                                        <?php endif; ?>
                                    </div>

                                    <div class="course-cta-btns">
                                        <?php if (auth()->loggedIn() && $isEnrolled): ?>
                                            <a href="<?= $viewUrl ?>" class="btn btn-sm btn-success btn-course-details">
                                                <i class="fas fa-play ml-1"></i> مشاهدة
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $courseUrl ?>" class="btn btn-sm btn-outline-primary btn-course-details">
                                                التفاصيل
                                            </a>
                                            <?php if ($isOpen): ?>
                                                <a href="javascript:void(0);" onclick="addToCart('course', <?= $course['id'] ?>);" class="btn btn-sm btn-primary btn-course-details">
                                                    <i class="fas fa-cart-plus ml-1"></i> اشترك
                                                </a>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-secondary btn-course-details" onclick="handleSubscribe('<?= esc($course['course_title']) ?>')">
                                                    أعلمني
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">لا توجد مقررات متاحة حالياً.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- No search results placeholder -->
        <div id="noCoursesFound" class="text-center py-5 d-none">
            <div class="mb-3 text-muted">
                <i class="fas fa-search" style="font-size: 50px; opacity: 0.3;"></i>
            </div>
            <h4 class="font-weight-bold text-dark mb-2">لم يتم العثور على مقررات مطابقة</h4>
            <p class="text-muted mb-4">جرّب البحث بكلمة أخرى أو رمز مقرر مختلف.</p>
            <button type="button" class="btn btn-primary px-4" style="border-radius: 20px;" onclick="resetCourseFilters()">
                عرض جميع المقررات
            </button>
        </div>

        <!-- Course Request Callout Box -->
        <div class="course-request-cta" data-aos="fade-up">
            <div class="mb-3">
                <span class="badge badge-primary px-3 py-2" style="font-size: 0.9rem; border-radius: 20px;">
                    <i class="fas fa-clipboard-list ml-1"></i> طلب مقرر جديد
                </span>
            </div>
            <h3>لم تجد مقررك الدراسي في القائمة؟</h3>
            <p>
                نعمل باستمرار على إضافة وشرح المزيد من مقررات الجامعة السعودية الإلكترونية. أخبرنا بالمقرر الذي تحتاجه وسنعمل على توفيره ومساعدتك فيه بأسرع وقت!
            </p>
            <button type="button" class="btn btn-primary btn-lg px-4 font-weight-bold" style="border-radius: 30px;" data-toggle="modal" data-target="#courseRequestModal">
                <i class="fas fa-plus-circle ml-2"></i> اطلب مقررك الآن
            </button>
        </div>

    </div>
</div>

<script>
let currentCollegeFilter = 'all';

function filterByCollege(college, btnElement) {
    currentCollegeFilter = college;
    
    // Update active tab button
    document.querySelectorAll('.filter-tab-btn').forEach(btn => btn.classList.remove('active'));
    if (btnElement) {
        btnElement.classList.add('active');
    }
    
    filterCoursesLive();
}

function filterCoursesLive() {
    const searchInput = document.getElementById('courseSearchInput');
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const items = document.querySelectorAll('.course-grid-item');
    let visibleCount = 0;

    items.forEach(item => {
        const itemCollege = item.getAttribute('data-college');
        const itemSearch = item.getAttribute('data-search') || '';

        const matchesCollege = (currentCollegeFilter === 'all' || itemCollege === currentCollegeFilter);
        const matchesSearch = (!query || itemSearch.includes(query));

        if (matchesCollege && matchesSearch) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const noResults = document.getElementById('noCoursesFound');
    if (noResults) {
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
}

function resetCourseFilters() {
    const searchInput = document.getElementById('courseSearchInput');
    if (searchInput) searchInput.value = '';
    const allBtn = document.querySelector('.filter-tab-btn');
    filterByCollege('all', allBtn);
}
</script>

<?= $this->endSection(); ?>
