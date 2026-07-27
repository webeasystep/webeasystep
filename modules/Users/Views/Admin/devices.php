<?= $this->extend('admin_layout/template') ?>
<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-shield-alt text-primary me-2"></i><?= esc($title) ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= site_url('dt_admin') ?>">الرئيسية</a></li>
                    <li class="breadcrumb-item active"><?= esc($title) ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Suspicious Users Card -->
        <div class="card card-danger card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title text-danger font-weight-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> التنبيهات والأجهزة المتعددة المشبوهة
                </h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>الطالب</th>
                            <th>البريد / الجوال</th>
                            <th>عدد الأجهزة المسجلة</th>
                            <th>آخر نشاط</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suspiciousList)): ?>
                            <?php foreach ($suspiciousList as $row): ?>
                                <tr>
                                    <td class="font-weight-bold"><?= esc($row['full_name'] ?: 'بدون اسم') ?></td>
                                    <td>
                                        <div><?= esc($row['email'] ?: '-') ?></div>
                                        <div class="text-muted small"><?= esc($row['mobile'] ?: '-') ?></div>
                                    </td>
                                    <td>
                                        <?php if ($row['device_count'] > 2): ?>
                                            <span class="badge bg-danger px-2 py-1" style="font-size: 0.9rem;">
                                                <i class="fas fa-exclamation-circle me-1"></i> <?= $row['device_count'] ?> أجهزة (مشبوه جداً)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.9rem;">
                                                <?= $row['device_count'] ?> أجهزة
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small"><?= esc($row['last_activity']) ?></td>
                                    <td>
                                        <?php if (!empty($row['has_blocked_device'])): ?>
                                            <span class="badge bg-danger">جهاز محظور</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">نشط</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('dt_admin/users/devices/reset/' . $row['user_id']) ?>"
                                           class="btn btn-sm btn-outline-warning text-dark font-weight-bold"
                                           onclick="return confirm('هل أنت تأكد من تصفير وإعادة تعيين أجهزة هذا الطالب؟ سيمكنه ذلك من ربط جهاز جديد.');">
                                            <i class="fas fa-sync-alt me-1"></i> تصفير الأجهزة
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                    لا توجد تنبيهات محاولات مشاركة حسابات حالياً.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Registered Devices Card -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-laptop-house me-1"></i> سجل جميع الأجهزة المسجلة للنظام
                </h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>اسم الطالب</th>
                            <th>معلومات الجهاز / المتصفح</th>
                            <th>عنوان IP</th>
                            <th>الجلسة الحالية</th>
                            <th>تاريخ التسجيل / التحديث</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allDevices)): ?>
                            <?php foreach ($allDevices as $dev): ?>
                                <tr class="<?= !empty($dev['is_blocked']) ? 'table-danger' : ($dev['is_active_session'] ? 'table-success' : '') ?>">
                                    <td><?= $dev['id'] ?></td>
                                    <td>
                                        <div class="font-weight-bold"><?= esc($dev['full_name'] ?: 'مستخدم #' . $dev['user_id']) ?></div>
                                        <div class="small text-muted"><?= esc($dev['mobile'] ?: $dev['email']) ?></div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold"><?= esc($dev['device_name'] ?: 'Unrecognized Device') ?></div>
                                        <div class="small text-muted font-monospace" style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                            <?= esc($dev['user_agent']) ?>
                                        </div>
                                    </td>
                                    <td class="font-monospace small"><?= esc($dev['ip_address']) ?></td>
                                    <td>
                                        <?php if ($dev['is_active_session']): ?>
                                            <span class="badge bg-success"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> جلسة نشطة الآن</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">غير نشط</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small"><?= esc($dev['updated_at']) ?></td>
                                    <td>
                                        <a href="<?= site_url('dt_admin/users/devices/toggle_block/' . $dev['id']) ?>"
                                           class="btn btn-xs <?= !empty($dev['is_blocked']) ? 'btn-success' : 'btn-danger' ?>"
                                           onclick="return confirm('هل أنت متاكد من تغيير حالة حظر هذا الجهاز؟');">
                                            <?= !empty($dev['is_blocked']) ? '<i class="fas fa-unlock"></i> إلغاء الحظر' : '<i class="fas fa-ban"></i> حظر الجهاز' ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">لم يتم تسجيل أي أجهزة بعد.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>
