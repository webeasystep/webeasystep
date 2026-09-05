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
                                            <a href="<?= $courseUrl ?>#first-unit" class="btn btn-course-preview" title="شاهد أول موديول مجاناً">
                                                <i class="fas fa-play-circle"></i> شاهد أول موديول مجاناً
                                            </a>
                                            <?php if ($isOpen): ?>
                                                <button type="button" onclick="addToCart('course', <?= $course['id'] ?>);" class="btn btn-primary btn-course-details">
                                                    <i class="fas fa-cart-plus ml-1"></i> أضف للسلة
                                                </button>
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
