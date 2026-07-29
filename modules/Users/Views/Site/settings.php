<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>

<style>
/* Custom Settings UI/UX Styles */
.settings-wrapper {
    padding: 2.5rem 0;
}

.profile-hero-card {
    background: linear-gradient(135deg, #136ad5 0%, #0d4fa7 100%);
    border-radius: 20px;
    padding: 2.5rem 2rem;
    color: #ffffff;
    box-shadow: 0 15px 35px rgba(19, 106, 213, 0.2);
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}

.profile-hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 350px;
    height: 350px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    pointer-events: none;
}

.profile-avatar-box {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #ffffff;
    color: #136ad5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    border: 4px solid rgba(255, 255, 255, 0.3);
    position: relative;
}

.status-dot {
    position: absolute;
    bottom: 3px;
    right: 3px;
    width: 18px;
    height: 18px;
    background: #20c997;
    border: 3px solid #ffffff;
    border-radius: 50%;
}

.user-badge-tag {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    border-radius: 50px;
    padding: 0.35rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.custom-settings-tabs {
    background: #f8fafc;
    border-radius: 14px;
    padding: 6px;
    border: 1px solid #e2e8f0;
    gap: 6px;
    margin-bottom: 0;
}

.custom-settings-tabs .nav-link {
    border-radius: 10px;
    color: #64748b;
    font-weight: 600;
    padding: 0.8rem 1.4rem;
    border: none;
    transition: all 0.25s ease-in-out;
    display: flex;
    align-items: center;
    gap: 8px;
}

.custom-settings-tabs .nav-link.active {
    background: #136ad5;
    color: #ffffff;
    box-shadow: 0 6px 15px rgba(19, 106, 213, 0.25);
}

.custom-settings-tabs .nav-link:hover:not(.active) {
    background: #e2e8f0;
    color: #1e293b;
}

.settings-content-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    padding: 2rem;
    margin-top: 1.5rem;
}

.settings-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.settings-section-header h4 {
    color: #1e293b;
}

.form-label-custom {
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.custom-input-group {
    position: relative;
}

.custom-input-group .input-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    z-index: 5;
    transition: color 0.2s ease;
}

.custom-input-group .form-control {
    padding-right: 45px;
    border-radius: 12px;
    border: 1.5px solid #cbd5e1;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    font-size: 0.98rem;
    transition: all 0.2s ease;
}

.custom-input-group .form-control:focus {
    border-color: #136ad5;
    box-shadow: 0 0 0 4px rgba(19, 106, 213, 0.12);
}

.custom-input-group .form-control:focus ~ .input-icon {
    color: #136ad5;
}

.custom-input-group .form-control[readonly],
.custom-input-group .form-control:disabled {
    background: #f8fafc;
    color: #334155;
    opacity: 1;
}

.toggle-password-btn {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    z-index: 5;
    padding: 4px 8px;
    border-radius: 6px;
    transition: color 0.2s;
}

.toggle-password-btn:hover {
    color: #136ad5;
}

.btn-primary-action {
    background: linear-gradient(135deg, #136ad5 0%, #0d4fa7 100%);
    border: none;
    border-radius: 12px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: #ffffff;
    box-shadow: 0 6px 18px rgba(19, 106, 213, 0.25);
    transition: all 0.25s ease;
}

.btn-primary-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(19, 106, 213, 0.35);
    color: #ffffff;
}

.security-info-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 1.25rem;
    color: #166534;
    margin-bottom: 1.5rem;
}

.device-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.25rem;
    background: #f8fafc;
    transition: all 0.2s ease;
}

.device-card:hover {
    border-color: #136ad5;
    background: #ffffff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.device-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #e0f2fe;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.password-strength-bar {
    height: 6px;
    border-radius: 3px;
    background: #e2e8f0;
    overflow: hidden;
    margin-top: 8px;
}

.password-strength-progress {
    height: 100%;
    width: 0%;
    transition: width 0.3s ease, background-color 0.3s ease;
}

.settings-form-row {
    --bs-gutter-y: 1.25rem;
    align-items: flex-start;
}

.settings-helper {
    color: #64748b;
    font-size: 0.84rem;
    line-height: 1.8;
}

@media (max-width: 991.98px) {
    .profile-hero-card {
        padding: 2rem 1.5rem;
    }

    .settings-content-card {
        padding: 1.5rem;
    }
}

@media (max-width: 767.98px) {
    .custom-settings-tabs .nav-link {
        font-size: 0.92rem;
        padding: 0.75rem 0.9rem;
    }

    .settings-section-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="container settings-wrapper">
    <?php
        $isInstructor = \App\Libraries\UserType::isInstructor($user ?? null);
        $accountLabel = $isInstructor ? 'حساب محاضر مفعل' : 'حساب طالب مفعل';
        $accountIcon = $isInstructor ? 'fas fa-chalkboard-teacher' : 'fas fa-graduation-cap';
        $defaultName = $isInstructor ? 'اسم المحاضر' : 'اسم الطالب';
    ?>
    <!-- Hero User Banner -->
    <div class="profile-hero-card d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-3 text-center text-md-start flex-column flex-md-row">
            <div class="profile-avatar-box">
                <?php 
                    $fullName = trim($user->full_name ?? '');
                    $initial = mb_substr($fullName, 0, 1, 'UTF-8');
                    echo esc($initial ?: 'ط');
                ?>
                <span class="status-dot" title="متصل الآن"></span>
            </div>
            <div>
                <h2 class="h3 fw-bold mb-1 text-white" style="color: #fff !important;"><?= esc($user->full_name ?? $defaultName) ?></h2>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start mt-2">
                    <span class="user-badge-tag">
                        <i class="<?= esc($accountIcon) ?>"></i> <?= esc($accountLabel) ?>
                    </span>
                    <span class="user-badge-tag">
                        <i class="fas fa-phone-alt"></i> <?= esc(format_mobile_display($user->mobile ?? '')) ?>
                    </span>
                    <?php if (!empty($user->email)): ?>
                        <span class="user-badge-tag">
                            <i class="fas fa-envelope"></i> <?= esc($user->email) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="text-md-end text-center">
            <small class="text-white-50 d-block mb-1">مركز التحكم بالحساب</small>
            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold">
                <i class="fas fa-shield-alt"></i> جلسة آمنة ومعتمدة
            </span>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-check-circle fs-5 text-success"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle fs-5 text-danger"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-exclamation-triangle fs-5 text-danger mt-1"></i>
                <div>
                    <strong class="d-block mb-1">يرجى مراجعة الأخطاء التالية:</strong>
                    <ul class="mb-0 ps-3">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    <?php endif; ?>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills custom-settings-tabs" id="settingsTabs" role="tablist">
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link w-100 justify-content-center active" id="profile-tab" data-bs-toggle="tab" data-toggle="tab" data-bs-target="#profile" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                <i class="fas fa-user-circle"></i> البيانات الشخصية
            </button>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link w-100 justify-content-center" id="password-tab" data-bs-toggle="tab" data-toggle="tab" data-bs-target="#password" data-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                <i class="fas fa-key"></i> تغيير كلمة المرور
            </button>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link w-100 justify-content-center" id="devices-tab" data-bs-toggle="tab" data-toggle="tab" data-bs-target="#devices" data-target="#devices" type="button" role="tab" aria-controls="devices" aria-selected="false">
                <i class="fas fa-laptop-house"></i> الأجهزة المعتمدة
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="settings-content-card">
        <div class="tab-content" id="settingsTabContent">
            
            <!-- TAB 1: PROFILE INFO -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="settings-section-header">
                    <div>
                        <h4 class="h5 fw-bold mb-1">معلومات الملف الشخصي</h4>
                        <p class="text-muted small mb-0">تحديث الاسم والبريد الإلكتروني المسجل بالحساب</p>
                    </div>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                        <i class="fas fa-user-edit"></i> تحديث البيانات
                    </span>
                </div>

                <form action="<?= base_url('settings/update-profile') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row settings-form-row">
                        <div class="col-12 col-lg-6">
                            <div>
                                <label for="full_name" class="form-label-custom">الاسم الكامل <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                           value="<?= esc($user->full_name ?? '') ?>" placeholder="أدخل اسمك الثلاثي أو الرباعي" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div>
                                <label for="email" class="form-label-custom">البريد الإلكتروني</label>
                                <div class="custom-input-group">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= esc($user->email ?? '') ?>" placeholder="name@example.com">
                                </div>
                                <div class="settings-helper mt-1">
                                    <i class="fas fa-info-circle text-primary"></i> يساعدك البريد الإلكتروني في استقبال الإشعارات الهامة وتحديثات المواد.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <label for="mobile" class="form-label-custom">رقم الجوال المسجل <span class="badge bg-secondary ms-1">مؤكد</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-phone-alt input-icon text-muted"></i>
                                    <input type="tel" class="form-control" id="mobile" name="mobile"
                                           value="<?= esc(format_mobile_display($user->mobile ?? '')) ?>" disabled readonly>
                                </div>
                                <div class="settings-helper mt-1">
                                    <i class="fas fa-lock text-warning"></i> رقم الجوال هو الهوية الأساسية للحساب ولا يمكن تغييره لضمان الحماية.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary-action">
                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: CHANGE PASSWORD -->
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <div class="settings-section-header">
                    <div>
                        <h4 class="h5 fw-bold mb-1">تغيير كلمة المرور</h4>
                        <p class="text-muted small mb-0">قم بتحديث كلمة المرور لحماية حسابك من أي وصول غير مصرح به</p>
                    </div>
                    <span class="badge bg-light text-warning border px-3 py-2 rounded-pill">
                        <i class="fas fa-shield-alt"></i> حماية الحساب
                    </span>
                </div>

                <form action="<?= base_url('settings/change-password') ?>" method="post" id="passwordChangeForm">
                    <?= csrf_field() ?>
                    
                    <div class="row settings-form-row">
                        <div class="col-12">
                            <div>
                                <label for="current_password" class="form-label-custom">كلمة المرور الحالية <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="••••••••" required>
                                    <button type="button" class="toggle-password-btn" data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div>
                                <label for="new_password" class="form-label-custom">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-key input-icon"></i>
                                    <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" placeholder="••••••••" required>
                                    <button type="button" class="toggle-password-btn" data-target="new_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength-bar">
                                    <div class="password-strength-progress" id="strengthProgress"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted">قوة كلمة المرور:</small>
                                    <small class="fw-bold" id="strengthLabel">غير مدخلة</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div>
                                <label for="confirm_password" class="form-label-custom">تأكيد كلمة المرور الجديدة <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-check-double input-icon"></i>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" placeholder="••••••••" required>
                                    <button type="button" class="toggle-password-btn" data-target="confirm_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="matchFeedback" class="small mt-1 font-weight-bold"></div>
                            </div>
                        </div>
                    </div>

                    <div class="security-info-box mt-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-lightbulb fs-5 text-success"></i>
                            <strong class="text-dark">نصائح لكلمة مرور قوية:</strong>
                        </div>
                        <ul class="mb-0 small text-secondary ps-3">
                            <li>استخدم 8 أحرف على الأقل تحتوي على مزيج من الحروف والأرقام.</li>
                            <li>تجنب استخدام كلمات مرور سهلة أو مكررة في مواقع أخرى.</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary-action">
                            <i class="fas fa-key me-1"></i> تحديث كلمة المرور
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: AUTHORIZED DEVICES -->
            <div class="tab-pane fade" id="devices" role="tabpanel" aria-labelledby="devices-tab">
                <div class="settings-section-header">
                    <div>
                        <h4 class="h5 fw-bold mb-1">الأجهزة والجلسات الفعالة</h4>
                        <p class="text-muted small mb-0">عرض الأجهزة المعتمدة التي قمت بتسجيل الدخول منها</p>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="fas fa-shield-alt"></i> نظام الحماية نشط
                    </span>
                </div>

                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-info-circle fs-3 text-info"></i>
                        <div class="small">
                            <strong>سياسة أمان الحساب:</strong> يُسمح لك بالدخول والتنقل بين أجهزتك الشخصية بأمان، ويتم حفظ جلسة نشطة واحدة في نفس الوقت لحماية المواد والدورة الخاصة بك من أي استخدام غير مصرح به.
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <?php if (!empty($userDevices)): ?>
                        <?php foreach ($userDevices as $dev): ?>
                            <div class="col-md-6">
                                <div class="device-card d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="device-icon-box">
                                            <?php 
                                                $ua = strtolower($dev['user_agent'] ?? '');
                                                if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                                                    echo '<i class="fas fa-mobile-alt"></i>';
                                                } else {
                                                    echo '<i class="fas fa-desktop"></i>';
                                                }
                                            ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark"><?= esc($dev['device_name'] ?: 'جهاز شخصي') ?></h6>
                                            <div class="text-muted small">
                                                <span><i class="fas fa-network-wired text-secondary"></i> IP: <?= esc($dev['ip_address'] ?? 'غير معروف') ?></span>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <i class="far fa-clock text-secondary"></i> آخر تواجد: <?= esc($dev['updated_at'] ?? 'الآن') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if (!empty($dev['is_active_session'])): ?>
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> الجلسة الحالية
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                <i class="fas fa-history me-1"></i> جهاز سابق
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="device-card text-center py-4">
                                <i class="fas fa-mobile-alt fs-1 text-muted mb-2"></i>
                                <h6 class="fw-bold text-dark">الجهاز الحالي هو الجهاز المسجل والمعتمد</h6>
                                <p class="text-muted small mb-0">تم الربط التلقائي بجهازك الحالي بنجاح.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 0. Robust Tab Switcher for Bootstrap 4/5 & Custom Click
    const tabButtons = document.querySelectorAll('.custom-settings-tabs .nav-link');
    const tabPanes = document.querySelectorAll('#settingsTabContent .tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Deactivate all tab buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });

            // Hide all tab content panes
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });

            // Activate clicked button
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');

            // Find target element by data-bs-target, data-target, or href
            const targetSelector = this.getAttribute('data-bs-target') || this.getAttribute('data-target') || this.getAttribute('href');
            if (targetSelector) {
                const targetPane = document.querySelector(targetSelector);
                if (targetPane) {
                    targetPane.style.display = 'block';
                    setTimeout(() => {
                        targetPane.classList.add('show', 'active');
                    }, 20);
                }
            }
        });
    });

    // 1. Password Eye Toggle
    document.querySelectorAll('.toggle-password-btn').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // 2. Live Password Strength Calculation
    const newPasswordInput = document.getElementById('new_password');
    const strengthProgress = document.getElementById('strengthProgress');
    const strengthLabel = document.getElementById('strengthLabel');

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const val = this.value;
            let score = 0;

            if (val.length >= 8) score += 30;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) score += 25;
            if (val.match(/\d/)) score += 25;
            if (val.match(/[^a-zA-Z0-9]/)) score += 20;

            strengthProgress.style.width = score + '%';

            if (val.length === 0) {
                strengthProgress.style.width = '0%';
                strengthLabel.textContent = 'غير مدخلة';
                strengthLabel.style.color = '#64748b';
            } else if (score < 40) {
                strengthProgress.style.backgroundColor = '#ef4444';
                strengthLabel.textContent = 'ضعيفة جداً';
                strengthLabel.style.color = '#ef4444';
            } else if (score < 75) {
                strengthProgress.style.backgroundColor = '#f59e0b';
                strengthLabel.textContent = 'متوسطة';
                strengthLabel.style.color = '#f59e0b';
            } else {
                strengthProgress.style.backgroundColor = '#10b981';
                strengthLabel.textContent = 'قوية وآمنة';
                strengthLabel.style.color = '#10b981';
            }

            checkPasswordMatch();
        });
    }

    // 3. Password Match Checker
    const confirmPasswordInput = document.getElementById('confirm_password');
    const matchFeedback = document.getElementById('matchFeedback');

    function checkPasswordMatch() {
        if (!confirmPasswordInput || !newPasswordInput) return;

        const newPass = newPasswordInput.value;
        const confirmPass = confirmPasswordInput.value;

        if (confirmPass.length === 0) {
            matchFeedback.textContent = '';
            confirmPasswordInput.classList.remove('is-invalid', 'is-valid');
            return;
        }

        if (newPass === confirmPass) {
            matchFeedback.textContent = '✓ كلمات المرور متطابقة';
            matchFeedback.style.color = '#10b981';
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
        } else {
            matchFeedback.textContent = '✗ كلمات المرور غير متطابقة';
            matchFeedback.style.color = '#ef4444';
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
        }
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
});
</script>
<?= $this->endSection(); ?>
