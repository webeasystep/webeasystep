<?php $this->extend('site_layout/template'); ?>

<?php $this->section('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-envelope-open-text text-primary" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h2 class="card-title mb-3">تم إرسال رسالة التفعيل</h2>
                    
                    <p class="card-text text-muted mb-4">
                        تم إنشاء حسابك بنجاح! لقد أرسلنا رسالة تفعيل إلى بريدك الإلكتروني.
                        يرجى التحقق من صندوق الوارد الخاص بك والنقر على رابط التفعيل لإكمال عملية التسجيل.
                    </p>
                    
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        إذا لم تجد الرسالة في صندوق الوارد، يرجى التحقق من مجلد الرسائل غير المرغوب فيها (Spam).
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('/login') ?>" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            العودة لصفحة تسجيل الدخول
                        </a>
                        <a href="<?= site_url('/') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>
                            العودة للصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>