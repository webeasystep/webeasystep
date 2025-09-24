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
    }
    .purchase-header {
        background: linear-gradient(90deg, #136ad5 0%, #1573e8 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    .purchase-body {
        padding: 20px;
    }
    .status-badge {
        padding: 5px 12px;
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
    .unit-list {
        list-style: none;
        padding: 0;
        margin: 15px 0;
    }
    .unit-item {
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    .unit-item:last-child {
        border-bottom: none;
    }
    .total-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ec661f;
    }
    .payment-proof-img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 8px;
        cursor: pointer;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
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
                    <?php foreach ($purchases as $purchase): ?>
                        <div class="purchase-card">
                            <div class="purchase-header">
                                <div>
                                    <h5 class="mb-1">طلب شراء #<?= $purchase->id ?></h5>
                                    <small><?= date('d/m/Y H:i', strtotime($purchase->created_at)) ?></small>
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
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6>الوحدات المشتراة:</h6>
                                        <ul class="unit-list">
                                            <?php
                                            $unitIds = json_decode($purchase->unit_ids, true);
                                            if ($unitIds):
                                                // Get unit details (you might need to load units model)
                                                $unitsModel = new \Modules\Units\Models\UnitsModel();
                                                $units = $unitsModel->whereIn('id', $unitIds)->findAll();
                                                foreach ($units as $unit):
                                            ?>
                                                <li class="unit-item">
                                                    <span><?= esc($unit->unit_name) ?></span>
                                                </li>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </ul>

                                        <div class="mt-2">
                                            <small class="text-muted">طريقة الدفع: <?= esc($purchase->payment_method) ?></small>
                                        </div>

                                        <?php if (!empty($purchase->admin_notes)): ?>
                                            <div class="alert alert-info mt-3">
                                                <strong>ملاحظات الإدارة:</strong><br>
                                                <?= esc($purchase->admin_notes) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-4">
                                        <?php if (!empty($purchase->payment_proof)): ?>
                                            <h6>إثبات الدفع:</h6>
                                            <?php
                                            // Use the thumb helper function for consistent image handling
                                            $thumbnailUrl = thumb($purchase->payment_proof, 200, 150);

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
                                                 onclick="window.open('<?= $fullImageUrl ?>', '_blank')">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($purchase->status === 'approved'): ?>
                                    <div class="mt-3">
                                        <?php
                                        // Get the first unit's course slug for navigation
                                        $courseSlug = null;
                                        if ($unitIds && !empty($units)) {
                                            $firstUnit = $units[0];
                                            // Get course slug from the first unit's course
                                            $coursesModel = new \Modules\Courses\Models\CoursesModel();
                                            $course = $coursesModel->find($firstUnit->course_id);
                                            if ($course && !empty($course->slug)) {
                                                $courseSlug = $course->slug;
                                            }
                                        }
                                        ?>
                                        <?php if ($courseSlug): ?>
                                            <a href="<?= site_url('courses/course_view/' . $courseSlug) ?>" class="btn btn-success">
                                                <i class="fas fa-play me-2"></i>
                                                الوصول للوحدات
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= site_url('courses') ?>" class="btn btn-success">
                                                <i class="fas fa-play me-2"></i>
                                                الوصول للوحدات
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
