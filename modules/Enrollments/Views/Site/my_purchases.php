<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .purchases-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    .purchase-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .purchase-header {
        background: linear-gradient(90deg, #136ad5 0%, #1573e8 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .purchase-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
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
    .unit-item {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 4px;
        border-left: 3px solid #136ad5;
    }
    .payment-proof-img {
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .payment-proof-img:hover {
        transform: scale(1.05);
    }
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    @media (max-width: 768px) {
        .purchase-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        .purchase-header h6 {
            margin-bottom: 0;
        }
    }
</style>

<div class="purchases-section">
    <div class="container">
        <?= $this->include('site_layout/site_msg'); ?>

        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">مشترياتي من الوحدات</h2>

                <?php if (empty($purchases)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h4>لا توجد مشتريات حتى الآن</h4>
                        <p class="text-muted">لم تقم بشراء أي وحدات بعد. تصفح الدورات واختر الوحدات التي تريد شراءها.</p>
                        <a href="<?= site_url('courses') ?>" class="btn btn-primary">تصفح الدورات</a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($purchases as $index => $purchase): ?>
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                <div class="purchase-card h-100">
                                    <div class="purchase-header">
                                        <div>
                                            <h6 class="mb-1">طلب شراء #<?= $purchase->id ?></h6>
                                            <small><?= date('d/m/Y', strtotime($purchase->created_at)) ?></small>
                                        </div>
                                        <div>
                                            <?php
                                            $statusClass = 'status-pending';
                                            $statusText = 'قيد المراجعة';

                                            if ($purchase->status === 'approved') {
                                                $statusClass = 'status-approved';
                                                $statusText = 'مُفعل';
                                            } elseif ($purchase->status === 'rejected') {
                                                $statusClass = 'status-rejected';
                                                $statusText = 'مرفوض';
                                            }
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </div>
                                    </div>

                                    <div class="purchase-body">
                                        <h6>الوحدة المشتراة:</h6>
                                        <?php
                                        if (!empty($purchase->unit_id)):
                                            // Get unit details
                                            $unitsModel = new \Modules\Units\Models\UnitsModel();
                                            $unit = $unitsModel->find($purchase->unit_id);
                                            if ($unit):
                                        ?>
                                            <div class="unit-item mb-2">
                                                <span class="fw-bold"><?= esc($unit->unit_name) ?></span>
                                            </div>
                                        <?php
                                            endif;
                                        endif;
                                        ?>

                                        <div class="mb-2">
                                            <small class="text-muted">طريقة الدفع: <?= esc($purchase->payment_method) ?></small>
                                        </div>

                                        <?php if (!empty($purchase->admin_notes)): ?>
                                            <div class="alert alert-info p-2 mb-2">
                                                <small><strong>ملاحظات الإدارة:</strong><br>
                                                <?= esc($purchase->admin_notes) ?></small>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($purchase->payment_proof)): ?>
                                            <div class="mb-2">
                                                <small class="text-muted d-block mb-1">إثبات الدفع:</small>
                                                <?php
                                                // Use the thumb helper function for consistent image handling
                                                $thumbnailUrl = thumb($purchase->payment_proof, 150, 100);

                                                // Get full image URL for modal display
                                                $paymentProofData = json_decode($purchase->payment_proof, true);
                                                if ($paymentProofData && isset($paymentProofData['files']) && !empty($paymentProofData['files'])) {
                                                    $fullImageUrl = base_url($paymentProofData['files'][0]['full_path']);
                                                } else {
                                                    $fullImageUrl = $thumbnailUrl; // Fallback to thumbnail if no full path
                                                }
                                                ?>
                                                <img src="<?= $thumbnailUrl ?>"
                                                     alt="إثبات الدفع"
                                                     class="payment-proof-img img-fluid"
                                                     style="max-width: 100%; max-height: 80px; border-radius: 4px; cursor: pointer;"
                                                     onclick="window.open('<?= $fullImageUrl ?>', '_blank')">
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($purchase->status === 'approved'): ?>
                                            <div class="mt-auto">
                                                <?php
                                                // Get the unit's course slug for navigation
                                                $courseSlug = null;
                                                if (!empty($purchase->unit_id)) {
                                                    $unitsModel = new \Modules\Units\Models\UnitsModel();
                                                    $unit = $unitsModel->find($purchase->unit_id);
                                                    if ($unit) {
                                                        // Get course slug from the unit's course
                                                        $coursesModel = new \Modules\Courses\Models\CoursesModel();
                                                        $course = $coursesModel->find($unit->course_id);
                                                        if ($course && !empty($course->slug)) {
                                                            $courseSlug = $course->slug;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?php if ($courseSlug): ?>
                                                    <a href="<?= site_url('courses/course_view/' . $courseSlug) ?>" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-play me-1"></i>
                                                        الوصول للوحدة
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= site_url('courses') ?>" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-play me-1"></i>
                                                        الوصول للوحدات
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
