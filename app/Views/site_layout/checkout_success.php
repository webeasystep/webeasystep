<?= $this->extend('site_layout/template') ?>
<?= $this->section('content') ?>

<style>
    .checkout-success-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        padding: 70px 0;
        background: #f1f5f9;
    }
    body.dark-mode .checkout-success-section { background: #0f172a; }
    .checkout-success-card {
        max-width: 620px;
        margin: auto;
        padding: 44px 32px;
        text-align: center;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.12);
    }
    body.dark-mode .checkout-success-card { background: #1e293b; }
    .checkout-success-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        margin-bottom: 20px;
        border-radius: 50%;
        color: #fff;
        background: #16a34a;
        font-size: 34px;
    }
    .checkout-success-card h1 { color: #1e293b; font-size: 1.7rem; font-weight: 700; }
    .checkout-success-card p { color: #64748b; font-size: 1.05rem; line-height: 1.8; }
    body.dark-mode .checkout-success-card h1 { color: #f8fafc; }
    body.dark-mode .checkout-success-card p { color: #cbd5e1; }
</style>

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
