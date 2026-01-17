<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .my-courses-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    .enrollment-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .enrollment-card .card-body {
        padding: 25px;
    }
    .course-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #343a40;
    }
    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    .page-header {
        background: linear-gradient(135deg, #ec661f 0%, #d4541a 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 40px;
    }
    .enrollment-meta {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .btn-view-course {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 6px;
    }
    .btn-view-course:hover {
        background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        color: white;
    }
</style>

<div class="page-header text-center">
    <div class="container">
        <h1>دوراتي</h1>
        <p class="mb-0">جميع الدورات التي اشتركت بها</p>
    </div>
</div>

<section class="my-courses-section">
    <div class="container">
        <?= $this->include('site_layout/site_msg'); ?>
        
        <div class="row mb-4">
            <div class="col-12 text-end">
                <a href="<?= site_url('enrollments/courses-shop') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> اشترك في دورة جديدة
                </a>
            </div>
        </div>
        
        <?php if (empty($enrollments)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                لم تشترك في أي دورة بعد.
                <a href="<?= site_url('enrollments/courses-shop') ?>" class="alert-link">تصفح الدورات المتاحة</a>
            </div>
        <?php else: ?>
            <?php foreach ($enrollments as $enrollment): ?>
                <div class="enrollment-card card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="course-title mb-2"><?= esc($enrollment->course_title) ?></h5>
                                <div class="enrollment-meta">
                                    <span><i class="fas fa-calendar me-1"></i> <?= date('Y/m/d', strtotime($enrollment->created_at)) ?></span>
                                    <span class="mx-2">|</span>
                                    <span><i class="fas fa-credit-card me-1"></i> <?= esc($enrollment->payment_method) ?></span>
                                    <?php if ($enrollment->paid_amount > 0): ?>
                                        <span class="mx-2">|</span>
                                        <span><i class="fas fa-money-bill me-1"></i> <?= number_format($enrollment->paid_amount, 2) ?> <img src="<?= base_url('site/images/Saudi_Riyal_Symbol-2.svg') ?>" alt="ر.س" style="height: 1em; vertical-align: middle;"></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <?php
                                $statusClass = 'status-pending';
                                $statusText = 'قيد المراجعة';
                                if ($enrollment->status === 'approved') {
                                    $statusClass = 'status-approved';
                                    $statusText = 'مفعّل';
                                } elseif ($enrollment->status === 'rejected') {
                                    $statusClass = 'status-rejected';
                                    $statusText = 'مرفوض';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                            </div>
                            <div class="col-md-3 text-end">
                                <?php if ($enrollment->status === 'approved'): ?>
                                    <a href="<?= site_url('courses/course_view/' . ($enrollment->slug ?? $enrollment->course_id)) ?>" class="btn btn-view-course">
                                        <i class="fas fa-play me-1"></i> مشاهدة الدورة
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">في انتظار التفعيل</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($enrollment->notes) && $enrollment->status === 'rejected'): ?>
                            <div class="alert alert-danger mt-3 mb-0">
                                <strong>سبب الرفض:</strong> <?= esc($enrollment->notes) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection(); ?>
