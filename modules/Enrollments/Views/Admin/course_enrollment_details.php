<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4><i class="fas fa-info-circle me-2"></i>تفاصيل طلب شراء الدورة</h4>
        </div>
        <div class="col-sm-6 text-end">
            <a href="<?= ADMIN_URL . 'enrollments/courses' ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> العودة للقائمة
            </a>
        </div>
    </div>
    
    <?= $this->include('admin_layout/admin_msg'); ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الطلب</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">اسم الطالب:</th>
                            <td>
                                <strong><?= esc($enrollment->full_name) ?></strong>
                                <?php if (!empty($enrollment->mobile)): 
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $enrollment->mobile);
                                    if (str_starts_with($cleanPhone, '00')) $cleanPhone = substr($cleanPhone, 2);
                                    if (str_starts_with($cleanPhone, '05') && strlen($cleanPhone) === 10) $cleanPhone = '966' . substr($cleanPhone, 1);
                                    elseif (str_starts_with($cleanPhone, '01') && strlen($cleanPhone) === 11) $cleanPhone = '20' . substr($cleanPhone, 1);
                                ?>
                                    <a href="https://wa.me/<?= esc($cleanPhone) ?>" target="_blank" class="btn btn-sm text-white ms-2" style="background-color:#25D366; border-radius:15px; padding:2px 8px; font-size:11px;">
                                        <i class="fab fa-whatsapp me-1"></i> واتساب
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>البريد الإلكتروني:</th>
                            <td><?= esc($enrollment->email ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>رقم الهاتف:</th>
                            <td><a href="tel:<?= esc($enrollment->mobile) ?>"><?= esc($enrollment->mobile ?? '-') ?></a></td>
                        </tr>
                        <?php if (!empty($enrollment->bundle_title)): ?>
                        <tr>
                            <th>الباقة:</th>
                            <td>
                                <span class="badge bg-primary fs-6"><i class="fas fa-layer-group me-1"></i> <?= esc($enrollment->bundle_title) ?></span>
                                <?php if (!empty($enrollment->bundle_price)): ?>
                                    <span class="badge bg-warning text-dark font-weight-bold fs-6 ms-1"><?= number_format((float)$enrollment->bundle_price, 2) ?> ر.س</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <?php if (!empty($batchEnrollments) && count($batchEnrollments) > 1): ?>
                        <tr>
                            <th>المقررات المشمولة (<?= count($batchEnrollments) ?>):</th>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($batchEnrollments as $bCourse): ?>
                                        <span class="badge bg-light text-dark border p-2"><i class="fas fa-book me-1 text-primary"></i> <?= esc($bCourse->course_title) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <th>اسم الدورة:</th>
                            <td><strong><?= esc($enrollment->course_title) ?></strong></td>
                        </tr>
                        <?php if (!empty($enrollment->course_price)): ?>
                        <tr>
                            <th>سعر الدورة:</th>
                            <td><?= number_format((float)$enrollment->course_price, 2) ?> ر.س</td>
                        </tr>
                        <?php endif; ?>
                        <?php endif; ?>

                        <tr>
                            <th>المبلغ المدفوع:</th>
                            <td><strong class="text-primary fs-5"><?= $enrollment->payment_method === 'free' ? 'مجاني' : number_format((float)($enrollment->paid_amount > 0 ? $enrollment->paid_amount : ($enrollment->bundle_price ?? 0)), 2) . ' ر.س' ?></strong></td>
                        </tr>
                        <tr>
                            <th>طريقة الدفع:</th>
                            <td>
                                <?php
                                $pmNames = [
                                    'anb'           => 'البنك العربي الوطني (ANB)',
                                    'stc_bank'       => 'بنك إس تي سي (STC Bank)',
                                    'paypal'         => 'باي بال (PayPal)',
                                    'instapay'       => 'انستاباي',
                                    'vodafone_cash'  => 'فودافون كاش',
                                    'usdt'           => 'USDT',
                                    'bank_transfer'  => 'تحويل بنكي',
                                    'free'           => 'مجاني',
                                ];
                                echo esc($pmNames[$enrollment->payment_method] ?? $enrollment->payment_method);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>تاريخ الطلب:</th>
                            <td><?= date('Y/m/d H:i', strtotime($enrollment->created_at)) ?></td>
                        </tr>
                        <tr>
                            <th>الحالة:</th>
                            <td>
                                <?php
                                $statusClass = 'bg-warning';
                                $statusText = 'قيد المراجعة';
                                if ($enrollment->status === 'approved') {
                                    $statusClass = 'bg-success';
                                    $statusText = 'مفعّل';
                                } elseif ($enrollment->status === 'refunded') {
                                    $statusClass = 'bg-dark';
                                    $statusText = 'تم الاسترجاع';
                                } elseif ($enrollment->status === 'rejected') {
                                    $statusClass = 'bg-danger';
                                    $statusText = 'مرفوض';
                                }
                                ?>
                                <span class="badge <?= $statusClass ?> fs-6"><?= $statusText ?></span>
                            </td>
                        </tr>
                        <?php if (!empty($enrollment->notes)): ?>
                        <tr>
                            <th>ملاحظات:</th>
                            <td><?= esc($enrollment->notes) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($enrollment->approved_at): ?>
                        <tr>
                            <th>تاريخ الموافقة:</th>
                            <td><?= date('Y/m/d H:i', strtotime($enrollment->approved_at)) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($enrollment->refunded_at)): ?>
                        <tr>
                            <th>تاريخ الاسترجاع:</th>
                            <td><?= date('Y/m/d H:i', strtotime($enrollment->refunded_at)) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($enrollment->refund_proof)): ?>
                        <tr>
                            <th>إثبات الاسترجاع:</th>
                            <td>
                                <a href="<?= base_url($enrollment->refund_proof) ?>" target="_blank" class="btn btn-warning btn-sm">
                                    <i class="fas fa-image me-1"></i> عرض الإثبات
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <?php if ($enrollment->status === 'pending'): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">الموافقة على الطلب</h6>
                </div>
                <div class="card-body">
                    <form action="<?= ADMIN_URL . 'enrollments/courses/approve/' . $enrollment->id ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">تاريخ انتهاء الصلاحية (اختياري)</label>
                            <input type="date" name="expires_at" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="admin_notes" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-1"></i> موافقة وتفعيل
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">رفض الطلب</h6>
                </div>
                <div class="card-body">
                    <form action="<?= ADMIN_URL . 'enrollments/courses/reject/' . $enrollment->id ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">سبب الرفض</label>
                            <textarea name="rejection_reason" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-1"></i> رفض الطلب
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($enrollment->status === 'approved'): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">تنفيذ الاسترجاع</h6>
                </div>
                <div class="card-body">
                    <form action="<?= ADMIN_URL . 'enrollments/courses/refund/' . $enrollment->id ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">صورة إثبات الاسترجاع</label>
                            <input type="file" name="refund_proof" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات الاسترجاع</label>
                            <textarea name="refund_notes" class="form-control" rows="2" placeholder="سبب أو ملاحظة داخلية عن الاسترجاع"></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="fas fa-undo me-1"></i> تنفيذ Refund
                        </button>
                    </form>
                </div>
            </div>
            <?php elseif ($enrollment->status === 'refunded'): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">حالة الاسترجاع</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-dark mb-0">
                        تم استرجاع قيمة هذا الاشتراك وإيقاف وصول العميل إلى الدورة.
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
