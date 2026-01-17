<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .checkout-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    .checkout-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .course-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 25px;
    }
    .course-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #343a40;
    }
    .course-price-display {
        font-size: 1.8rem;
        font-weight: 700;
        color: #ec661f;
    }
    .course-price-display.free {
        color: #28a745;
    }
    .payment-method-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method-card:hover {
        border-color: #ec661f;
        background-color: #fff8f5;
    }
    .payment-method-card.selected {
        border-color: #ec661f;
        background-color: #fff8f5;
    }
    .payment-method-card.free-payment {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-color: #4caf50;
    }
    .payment-method-card.free-payment.selected {
        background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
        color: white;
    }
    .payment-details {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    .bank-info {
        background: #e8f5e8;
        border: 1px solid #c3e6c3;
        border-radius: 8px;
        padding: 15px;
    }
    .btn-complete-purchase {
        background: linear-gradient(135deg, #ec661f 0%, #d4541a 100%);
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        color: white;
        width: 100%;
    }
    .btn-complete-purchase:hover {
        background: linear-gradient(135deg, #d4541a 0%, #c04717 100%);
        color: white;
    }
</style>

<section class="checkout-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-card card">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">إتمام شراء الدورة</h4>
                    </div>
                    <div class="card-body p-4">
                        <?= $this->include('site_layout/site_msg'); ?>
                        
                        <!-- Course Info -->
                        <div class="course-info">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="course-title mb-2"><?= esc($course->course_title) ?></h5>
                                    <?php if (!empty($course->short_desc)): ?>
                                        <p class="text-muted mb-0"><?= esc($course->short_desc) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 text-end">
                                    <?php if ($is_free): ?>
                                        <span class="course-price-display free">مجاني</span>
                                    <?php else: ?>
                                        <span class="course-price-display"><?= number_format($course->course_price, 2) ?> جنيه</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Form -->
                        <form action="<?= site_url('enrollments/course-checkout') ?>" method="post" id="checkoutForm">
                            <?= csrf_field() ?>
                            
                            <h5 class="mb-3">طريقة الدفع:</h5>
                            
                            <?php if ($is_free): ?>
                                <!-- Free Course -->
                                <div class="payment-method-card free-payment selected" onclick="selectPaymentMethod('free')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment_method" value="free" id="free" checked>
                                        <label for="free" class="mb-0 ms-2">
                                            <strong><i class="fas fa-gift me-2"></i>مجاني</strong>
                                            <small class="d-block">هذه الدورة متاحة مجاناً</small>
                                        </label>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Paid Course Payment Options -->
                                <div class="payment-method-card selected" onclick="selectPaymentMethod('instapay')" id="instapay_card">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment_method" value="instapay" id="instapay" checked>
                                        <label for="instapay" class="mb-0 ms-2">
                                            <strong>انستاباي</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="payment-method-card" onclick="selectPaymentMethod('vodafone_cash')" id="vodafone_cash_card">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment_method" value="vodafone_cash" id="vodafone_cash">
                                        <label for="vodafone_cash" class="mb-0 ms-2">
                                            <strong>فودافون كاش</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Payment Details -->
                                <div class="payment-details">
                                    <div id="instapay_info" class="payment-info-block">
                                        <div class="bank-info">
                                            <h6><i class="fas fa-university me-2"></i>بيانات انستاباي:</h6>
                                            <p class="mb-1"><strong>الحساب:</strong> fakhr@instapay</p>
                                            <p class="mb-0"><strong>الاسم:</strong> احمد **م**ف**ال</p>
                                        </div>
                                    </div>
                                    <div id="vodafone_cash_info" class="payment-info-block" style="display: none;">
                                        <div class="bank-info">
                                            <h6><i class="fas fa-mobile-alt me-2"></i>بيانات فودافون كاش:</h6>
                                            <p class="mb-1"><strong>رقم المحفظة:</strong> 01032863861</p>
                                            <p class="mb-0"><strong>اسم صاحب المحفظة:</strong> احمد **م**ف**ال</p>
                                        </div>
                                    </div>
                                    
                                    <!-- WhatsApp Contact -->
                                    <div class="alert alert-success mt-3 mb-0">
                                        <div class="d-flex align-items-center">
                                            <i class="fab fa-whatsapp fa-2x me-3"></i>
                                            <div>
                                                <strong>للتأكيد بعد التحويل:</strong>
                                                <p class="mb-0">
                                                    <a href="https://wa.me/201032863861" target="_blank" class="text-success">
                                                        تواصل عبر واتساب: 201032863861
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <button type="submit" class="btn btn-complete-purchase mt-4">
                                <?php if ($is_free): ?>
                                    <i class="fas fa-check me-2"></i>تفعيل الدورة المجانية
                                <?php else: ?>
                                    <i class="fas fa-shopping-cart me-2"></i>إتمام الشراء
                                <?php endif; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
function selectPaymentMethod(method) {
    document.getElementById(method).checked = true;
    
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.getElementById(method + '_card').classList.add('selected');
    
    document.querySelectorAll('.payment-info-block').forEach(block => {
        block.style.display = 'none';
    });
    const infoBlock = document.getElementById(method + '_info');
    if (infoBlock) {
        infoBlock.style.display = 'block';
    }
}

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري المعالجة...';
    btn.disabled = true;
});
</script>
<?= $this->endSection(); ?>
