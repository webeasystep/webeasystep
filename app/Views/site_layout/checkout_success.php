<?= $this->extend('site_layout/template') ?>
<?= $this->section('content') ?>

<section class="checkout-success-section">
    <div class="container">
        <div class="checkout-success-card">
            <div class="checkout-success-icon"><i class="fas fa-check"></i></div>
            <h1>تم استلام طلبك بنجاح</h1>
            <p>جاري مراجعة إيصال التحويل وتفعيل الكورس في حسابك خلال ساعتين.</p>
            <a href="<?= site_url('enrollments/my-courses') ?>" class="btn btn-primary px-4 mt-2">
                <i class="fas fa-graduation-cap ml-1"></i> الانتقال إلى مقرراتي
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
