<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Main Section Container */
    .untree_co-section {
        padding-top: 80px;
        padding-bottom: 80px;
        background-color: #f9fafb;
    }

    /* Header: Title & Description */
    .my-courses-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .my-courses-header h1.section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .my-courses-header h1.section-title::after {
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
    .my-courses-header p.course-description {
        font-size: 1.1rem;
        color: #555;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Course Card Container */
    .course-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid #eee;
    }
    .course-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }

    /* Course Image Section */
    .course-image-container {
        position: relative;
        overflow: hidden;
        height: 200px;
    }
    .course-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .course-card:hover .course-thumbnail {
        transform: scale(1.05);
    }
    
    /* Status Badge */
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px; /* Arabic RTL */
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .status-approved { background-color: #28a745; color: white; }
    .status-pending { background-color: #ffc107; color: #333; }
    .status-rejected { background-color: #dc3545; color: white; }

    /* Unit Info Badge */
    .unit-badge {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background-color: rgba(0,0,0,0.7);
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    /* Course Content */
    .course-content {
        flex: 1 1 auto;
        padding: 20px;
        text-align: right;
        direction: rtl;
    }
    .course-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.5;
        height: 52px; /* 2 lines roughly */
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .course-meta {
        font-size: 0.85rem;
        color: #777;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Progress Bar Section */
    .progress-container {
        margin-bottom: 20px;
    }
    .progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        margin-bottom: 5px;
        color: #555;
        font-weight: 600;
    }
    .progress {
        height: 10px;
        background-color: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-bar {
        background-color: #136ad5;
        transition: width 0.6s ease;
    }

    /* Action Button */
    .course-footer {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #eee;
    }
    .btn-action {
        width: 100%;
        border-radius: 6px;
        font-weight: 600;
        padding: 10px;
        transition: all 0.3s;
    }
    .btn-primary-custom {
        background-color: #136ad5;
        color: white;
        border: none;
    }
    .btn-primary-custom:hover {
        background-color: #0b5cbf;
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 20px; }
    .empty-state h3 { font-size: 1.5rem; margin-bottom: 15px; color: #333; }

    /* Dark Mode Overrides */
    body.dark-mode .untree_co-section {
        background-color: #121212 !important;
    }
    body.dark-mode .my-courses-header h1.section-title {
        color: #fff !important;
    }
    body.dark-mode .my-courses-header p.course-description {
        color: #b0b0b0 !important;
    }
    body.dark-mode .course-card {
        background-color: #1e1e1e !important;
        border-color: #333 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    body.dark-mode .course-title {
        color: #fff !important;
    }
    body.dark-mode .course-meta {
        color: #aaa !important;
    }
    body.dark-mode .progress-label {
        color: #ccc !important;
    }
    body.dark-mode .progress {
        background-color: #333 !important;
    }
    body.dark-mode .course-footer {
        background-color: #252525 !important;
        border-top-color: #333 !important;
    }
    body.dark-mode .empty-state {
        background-color: #1e1e1e !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2) !important;
    }
    body.dark-mode .empty-state h3 {
        color: #fff !important;
    }
    body.dark-mode .empty-state p {
        color: #bbb !important;
    }
    body.dark-mode .empty-state i {
        color: #444 !important;
    }
</style>

<div class="untree_co-section bg-light">
    <div class="container">
        <!-- Page Header -->
        <div class="my-courses-header" data-aos="fade-up">
            <h1 class="section-title">مقرراتي المسجلة</h1>
            <p class="course-description">تابع تقدمك التعليمي واستأنف مقرراتك من حيث توقفت</p>
        </div>

        <?= $this->include('site_layout/site_msg'); ?>

        <div class="row">
            <?php if (!empty($enrollments)): ?>
                <?php foreach ($enrollments as $enrollment): ?>
                    <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="course-card">
                            <!-- Image & Badges -->
                            <div class="course-image-container">
                                <img
                                    src="<?= thumb($enrollment->image, 400, 250) ?>"
                                    alt="<?= esc($enrollment->course_title) ?>"
                                    class="course-thumbnail"
                                    onerror="this.src='<?= base_url('assets/images/course-placeholder.jpg') ?>'"
                                >
                                
                                <!-- Status Badge -->
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                if ($enrollment->status === 'approved') {
                                    $statusClass = 'status-approved';
                                    $statusText = 'مفعّل';
                                } elseif ($enrollment->status === 'pending') {
                                    $statusClass = 'status-pending';
                                    $statusText = 'قيد المراجعة';
                                } elseif ($enrollment->status === 'refunded') {
                                    $statusClass = 'status-rejected';
                                    $statusText = 'تم الاسترجاع';
                                } else {
                                    $statusClass = 'status-rejected';
                                    $statusText = 'مرفوض';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>

                                <!-- Unit Info (Only if approved) -->
                                <?php if ($enrollment->status === 'approved'): ?>
                                    <div class="unit-badge">
                                        <i class="fas fa-layer-group me-1"></i>
                                        <?= $enrollment->completed_units ?> / <?= $enrollment->total_units ?> وحدات
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="course-content">
                                <h3 class="course-title" title="<?= esc($enrollment->course_title) ?>">
                                    <?= esc($enrollment->course_title) ?>
                                </h3>
                                
                                <div class="course-meta">
                                    <span><i class="fas fa-calendar-alt me-1"></i> <?= date('Y/m/d', strtotime($enrollment->created_at)) ?></span>
                                    <?php if ($enrollment->paid_amount > 0): ?>
                                        <span class="ms-auto" style="direction: ltr;">
                                            $<?= number_format($enrollment->paid_amount) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Progress Bar (Only if approved) -->
                                <?php if ($enrollment->status === 'approved'): ?>
                                    <div class="progress-container">
                                        <div class="progress-label">
                                            <span>نسبة الإنجاز</span>
                                            <span><?= $enrollment->progress ?>%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?= $enrollment->progress ?>%" 
                                                 aria-valuenow="<?= $enrollment->progress ?>" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($enrollment->status === 'rejected'): ?>
                                    <div class="alert alert-danger p-2 mb-0" style="font-size: 0.85rem;">
                                        <strong>سبب الرفض:</strong> <?= esc($enrollment->notes) ?>
                                    </div>
                                <?php elseif ($enrollment->status === 'refunded'): ?>
                                    <div class="alert alert-dark p-2 mb-0" style="font-size: 0.85rem;">
                                        تم استرجاع قيمة هذا المقرر وتم إيقاف الوصول إليه.
                                    </div>
                                <?php elseif ($enrollment->status === 'pending'): ?>
                                    <div class="alert alert-warning p-2 mb-0" style="font-size: 0.85rem;">
                                        جاري مراجعة طلب الاشتراك وتفعيل المقرر قريباً.
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Footer Actions -->
                            <div class="course-footer">
                                <?php if ($enrollment->status === 'approved'): ?>
                                    <a href="<?= base_url('courses/course_view/' . ($enrollment->slug ?? $enrollment->course_id)) ?>" 
                                       class="btn btn-action btn-primary-custom">
                                        <i class="fas fa-play me-2"></i> متابعة المقرر
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-action btn-secondary" disabled>
                                        <i class="fas fa-lock me-2"></i> مغلق
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h3>لا توجد مقررات مسجلة حالياً</h3>
                        <p class="text-muted">استكشف مكتبة المقررات وابدأ رحلة التعلم اليوم</p>
                        <a href="<?= site_url('/') ?>" class="btn btn-primary mt-3">
                            تصفح جميع المقررات
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
