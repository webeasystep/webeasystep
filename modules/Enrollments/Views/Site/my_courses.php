<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

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
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="course-card">
                            <!-- Image & Badges -->
                            <div class="course-image-container">
                                <img
                                    src="<?= thumb($enrollment->image, 400, 200) ?>"
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
                                        <span class="mr-auto font-weight-bold text-dark">
                                            <?= number_format($enrollment->paid_amount, 2) ?> <?= riyal_icon('13px') ?>
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
