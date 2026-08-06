<?= $this->extend('site_layout/template') ?>
<?= $this->section('content') ?>

<style>
    /* ── Base Section ── */
    .checkout-section {
        padding: 60px 0 80px;
        background: #f1f5f9;
        min-height: 80vh;
        transition: background 0.3s;
    }
    body.dark-mode .checkout-section {
        background: linear-gradient(135deg, #0f172a 0%, #1a2540 100%);
    }

    /* ── Card ── */
    .checkout-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        background: #ffffff;
        transition: all 0.3s;
    }
    body.dark-mode .checkout-card {
        background: #1e293b;
        box-shadow: 0 10px 40px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.07);
    }

    /* ── Card Header ── */
    .checkout-header {
        background: linear-gradient(135deg, #136ad5 0%, #1e88e5 100%);
        padding: 28px 30px;
        color: #fff;
        text-align: center;
        position: relative;
    }
    .checkout-header h4 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .checkout-header .header-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 20px;
    }

    /* ── Card Body ── */
    .checkout-body {
        padding: 32px;
    }
    @media (max-width: 576px) {
        .checkout-body { padding: 20px 16px; }
    }

    /* ── Course Info Block ── */
    .course-info-block {
        background: #f8fafc;
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 28px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    body.dark-mode .course-info-block {
        background: #0f172a;
        border-color: rgba(255,255,255,0.08);
    }
    .course-info-thumb {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #136ad5, #1e88e5);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 22px;
        color: #fff;
    }
    .course-info-text h5 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 4px;
    }
    body.dark-mode .course-info-text h5 { color: #f1f5f9; }
    .course-info-text p {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }
    body.dark-mode .course-info-text p { color: #94a3b8; }
    .course-price-badge {
        margin-right: auto;
        background: #e0f2fe;
        color: #0284c7;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.95rem;
    }
    body.dark-mode .course-price-badge {
        background: rgba(2, 132, 199, 0.2);
        color: #38bdf8;
    }

    /* ── Layout grid ── */
    .checkout-grid {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 32px;
    }
    @media (max-width: 992px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ── Sections Titles ── */
    .section-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    body.dark-mode .section-title { color: #f1f5f9; }
    .section-title i { color: #136ad5; }
    
    /* ── Summary Card ── */
    .summary-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e2e8f0;
    }
    body.dark-mode .summary-card {
        background: #1e293b;
        border-color: rgba(255,255,255,0.08);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #475569;
        font-size: 0.95rem;
    }
    body.dark-mode .summary-row { color: #94a3b8; }
    .summary-row.total {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px dashed #cbd5e1;
        color: #0f172a;
        font-size: 1.25rem;
        font-weight: 700;
    }
    body.dark-mode .summary-row.total {
        border-color: rgba(255,255,255,0.1);
        color: #fff;
    }

    /* ── Form Controls ── */
    .form-group label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    body.dark-mode .form-group label { color: #cbd5e1; }
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
        background: #fff;
        transition: all 0.2s;
        box-shadow: none;
        height: auto;
    }
    body.dark-mode .form-control, body.dark-mode .form-select {
        background: #0f172a;
        border-color: rgba(255,255,255,0.1);
        color: #f1f5f9;
    }
    .form-control:focus, .form-select:focus {
        border-color: #136ad5;
        box-shadow: 0 0 0 4px rgba(19, 106, 213, 0.1);
    }
    
    /* ── Coupon Group ── */
    .coupon-group {
        display: flex;
        gap: 10px;
    }
    .btn-apply {
        background: #1e293b;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0 20px;
        font-weight: 600;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-apply:hover {
        background: #0f172a;
        color: #fff;
    }
    
    /* ── Payment Options ── */
    .payment-option {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s;
        background: #fff;
    }
    body.dark-mode .payment-option {
        background: #0f172a;
        border-color: rgba(255,255,255,0.1);
    }
    .payment-option:hover {
        border-color: #cbd5e1;
    }
    .payment-option.active {
        border-color: #136ad5;
        background: #f0f7ff;
    }
    body.dark-mode .payment-option.active {
        background: rgba(19, 106, 213, 0.15);
        border-color: #3b82f6;
    }
    .payment-option input[type="radio"] {
        display: none;
    }
    .payment-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        position: relative;
    }
    .payment-option.active .payment-radio {
        border-color: #136ad5;
    }
    body.dark-mode .payment-option.active .payment-radio {
        border-color: #3b82f6;
    }
    .payment-option.active .payment-radio::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 10px; height: 10px;
        background: #136ad5;
        border-radius: 50%;
    }
    body.dark-mode .payment-option.active .payment-radio::after { background: #3b82f6; }
    
    .payment-icon {
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
    }
    .payment-option.paypal .payment-icon { color: #00457C; }
    .payment-option.fawry .payment-icon { color: #fdb913; }
    .payment-option.vodafone .payment-icon { color: #e60000; }
    .payment-option.instapay .payment-icon { color: #6e00ff; }
    .payment-option.usdt .payment-icon { color: #26A17B; }
    body.dark-mode .payment-option.paypal .payment-icon { color: #0079c1; }
    body.dark-mode .payment-option.instapay .payment-icon { color: #9d4edd; }

    .payment-text {
        font-weight: 600;
        color: #1e293b;
        font-size: 1.05rem;
    }
    body.dark-mode .payment-text { color: #e2e8f0; }
    
    /* ── Submit Button ── */
    .btn-submit {
        background: linear-gradient(135deg, #136ad5 0%, #1e88e5 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 16px;
        font-size: 1.15rem;
        font-weight: 700;
        width: 100%;
        margin-top: 24px;
        box-shadow: 0 4px 15px rgba(19, 106, 213, 0.3);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(19, 106, 213, 0.4);
        color: #fff;
    }
    .btn-submit:disabled {
        opacity: 0.7;
        transform: none;
        cursor: not-allowed;
    }

    /* ── Instructions Box ── */
    .instructions-box {
        background: #fff;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        margin-top: 16px;
        display: none;
        animation: fadeIn 0.3s ease;
    }
    body.dark-mode .instructions-box {
        background: rgba(15, 23, 42, 0.5);
        border-color: rgba(255,255,255,0.1);
    }
    .instructions-box.active {
        display: block;
    }
    
    /* Custom PayPal Box Styling */
    .paypal-instructions-box {
        background: linear-gradient(to right, rgba(0, 112, 186, 0.05), rgba(0, 48, 135, 0.05));
        border: 2px solid rgba(0, 112, 186, 0.2);
        border-radius: 16px;
        padding: 25px;
    }
    body.dark-mode .paypal-instructions-box {
        background: linear-gradient(to right, rgba(0, 112, 186, 0.1), rgba(0, 48, 135, 0.1));
        border-color: rgba(0, 112, 186, 0.3);
    }
    
    .paypal-logo-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px dashed rgba(0, 112, 186, 0.2);
    }
    .paypal-logo-header img {
        height: 24px;
        width: auto;
    }
    .paypal-logo-header h5 {
        margin: 0;
        color: #003087;
        font-weight: 700;
        font-size: 1.1rem;
    }
    body.dark-mode .paypal-logo-header h5 { color: #3b82f6; }
    
    .paypal-step {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    .paypal-step-num {
        width: 28px;
        height: 28px;
        background: #0070ba;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
        font-size: 0.9rem;
    }
    
    .payment-info-box {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        margin: 15px 0 15px 43px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    body.dark-mode .payment-info-box {
        background: #0f172a;
        border-color: rgba(255,255,255,0.1);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .info-row:last-child {
        margin-bottom: 0;
    }
    .info-label {
        color: #64748b;
        font-size: 0.9rem;
    }
    body.dark-mode .info-label { color: #94a3b8; }
    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-family: monospace;
        font-size: 1.1rem;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 6px;
    }
    body.dark-mode .info-value { 
        color: #f1f5f9; 
        background: #1e293b;
    }
    
    .btn-copy {
        background: none;
        border: none;
        color: #0070ba;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-copy:hover {
        background: rgba(0, 112, 186, 0.1);
    }
    
    .btn-pay-link {
        display: block;
        text-align: center;
        background: #ffc439;
        color: #000;
        text-decoration: none;
        padding: 12px;
        border-radius: 25px;
        font-weight: 700;
        margin: 15px 0 15px 43px;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(255, 196, 57, 0.3);
    }
    .btn-pay-link:hover {
        background: #f4bb33;
        transform: translateY(-2px);
        color: #000;
        text-decoration: none;
    }
    
    .payment-warning {
        font-size: 0.85rem;
        color: #eab308;
        display: flex;
        gap: 8px;
        align-items: flex-start;
        background: rgba(234, 179, 8, 0.1);
        padding: 10px 12px;
        border-radius: 8px;
        margin-top: 15px;
    }

    /* ── Upload Area ── */
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    body.dark-mode .upload-area {
        background: rgba(15, 23, 42, 0.5);
        border-color: rgba(255,255,255,0.2);
    }
    .upload-area:hover {
        border-color: #136ad5;
        background: rgba(19, 106, 213, 0.02);
    }
    .upload-area.has-file {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }
    .upload-area input[type="file"] {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .upload-icon {
        font-size: 36px;
        color: #94a3b8;
        margin-bottom: 12px;
    }
    .upload-area.has-file .upload-icon { color: #10b981; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="checkout-section">
    <div class="container">
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="checkout-card">
            <div class="checkout-header">
                <div class="header-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h4>إتمام الشراء الآمن للسلة</h4>
            </div>
            
            <div class="checkout-body">
                <form action="<?= site_url('cart/checkout') ?>" method="POST" enctype="multipart/form-data" id="checkoutForm">
                    <?= csrf_field() ?>
                    
                    <div class="checkout-grid">
                        
                        <!-- Left Column (Form) -->
                        <div class="checkout-form-col">
                            
                            <!-- Items Info (loop over cart items) -->
                            <h5 class="section-title"><i class="fas fa-shopping-basket"></i> محتويات السلة</h5>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="course-info-block">
                                    <div class="course-info-thumb">
                                        <?php if ($item->image && $item->image !== '[]'): ?>
                                            <?php if ($item->item_type === 'course'): ?>
                                                <img src="<?= base_url('uploads/courses/' . $item->image) ?>" alt="<?= esc($item->title) ?>" class="img-fluid rounded" style="width:100%; height:100%; object-fit:cover;">
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/bundles/' . $item->image) ?>" alt="<?= esc($item->title) ?>" class="img-fluid rounded" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='<?= base_url('uploads/courses/' . $item->image) ?>'">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-book-open"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="course-info-text">
                                        <h5><?= esc($item->title) ?></h5>
                                        <?php if ($item->item_type === 'bundle'): ?>
                                            <p>باقة تحتوي على <?= count($item->courses) ?> مواد</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="course-price-badge">
                                        <i class="fas fa-tag"></i> $<?= esc(number_format((float) $item->price / 3.75, 2)) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Payment Methods (Only PayPal available based on previous work) -->
                            <div class="payment-methods-section mt-5">
                                <h5 class="section-title"><i class="fas fa-wallet"></i> طريقة الدفع</h5>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="payment-option active paypal" data-method="paypal">
                                            <input type="radio" name="payment_method" value="paypal" checked>
                                            <div class="payment-radio"></div>
                                            <div class="payment-icon"><i class="fab fa-paypal"></i></div>
                                            <div class="payment-text">دفع آمن عبر PayPal (بطاقة ائتمانية / بيبال)</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- PayPal Instructions Box -->
                            <div class="instructions-box paypal-instructions-box active" id="paypal-instructions">
                                <div class="paypal-logo-header">
                                    <i class="fab fa-paypal" style="color: #003087; font-size: 24px;"></i>
                                    <h5>الدفع عبر PayPal</h5>
                                </div>
                                
                                <div class="paypal-step">
                                    <div class="paypal-step-num">1</div>
                                    <div>
                                        <strong>اضغط على الزر أدناه للدفع</strong>
                                        <p class="text-muted mb-0" style="font-size: 0.9rem;">سيتم تحويلك لصفحة الدفع، أو يمكنك نسخ الرابط والمبلغ.</p>
                                    </div>
                                </div>
                                
                                <div class="payment-info-box">
                                    <div class="info-row">
                                        <span class="info-label">المبلغ المطلوب بالدولار:</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="info-value text-primary" style="font-size: 1.3rem;">
                                                $<span id="paypalAmountDisplay"><?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-row mt-3 pt-3 border-top">
                                        <span class="info-label">رابط الدفع:</span>
                                        <div class="d-flex align-items-center gap-2" style="width: 70%;">
                                            <div class="info-value text-truncate" id="paypalLinkDisplay" style="flex:1;">paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></div>
                                            <button type="button" class="btn-copy" id="paypalCopyBtn" data-copy-text="https://paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?>" onclick="copyToClipboard(this.dataset.copyText, this)" title="نسخ الرابط">
                                                <i class="far fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="https://paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?>" target="_blank" class="btn-pay-link btn-paypal-pay" id="paypalPayBtn">
                                    <span id="paypalPayBtnText">دفع مبلغ $<span id="paypalBtnAmount"><?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></span> عبر PayPal</span>
                                </a>
                                
                                <div class="paypal-step mt-4">
                                    <div class="paypal-step-num">2</div>
                                    <div>
                                        <strong>إرفاق الإيصال وإتمام الطلب</strong>
                                        <p class="text-muted mb-2" style="font-size: 0.9rem;">بعد الدفع، التقط صورة للإيصال وارفعها هنا لتأكيد اشتراكك.</p>
                                        
                                        <div class="upload-area mt-2" id="uploadArea">
                                            <input type="file" name="payment_proof" id="paymentProof" accept="image/*,.pdf" required>
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <h6 class="mb-1 text-dark">اسحب وأفلت صورة الإيصال هنا</h6>
                                            <p class="text-muted small mb-0">أو اضغط لاختيار ملف (صورة أو PDF)</p>
                                            <div id="fileNameDisplay" class="mt-2 text-primary font-weight-bold" style="display:none;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="payment-warning">
                                    <i class="fas fa-exclamation-triangle mt-1"></i>
                                    <div>يرجى التأكد من دفع المبلغ المطلوب <strong>بالدولار الأمريكي (USD)</strong> لتجنب تأخير تفعيل حسابك.</div>
                                </div>
                            </div>
                            
                            <!-- Submit Area -->
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-check-circle"></i> تأكيد وإرسال الطلب
                            </button>
                            
                        </div>
                        
                        <!-- Right Column (Summary) -->
                        <div class="checkout-summary-col">
                            <h5 class="section-title"><i class="fas fa-receipt"></i> ملخص الطلب</h5>
                            
                            <div class="summary-card">
                                <div class="summary-row">
                                    <span>المجموع الأصلي</span>
                                    <strong id="originalAmountText">$<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></strong>
                                </div>
                                
                                <div class="summary-row text-success" id="discountRow" style="display: none;">
                                    <span>الخصم (كوبون)</span>
                                    <strong id="discountAmountText">-$0.00</strong>
                                </div>
                                
                                <div class="summary-row total">
                                    <span>الإجمالي المطلوب للدفع</span>
                                    <strong id="finalAmountText" class="text-primary">$<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></strong>
                                </div>
                                
                                <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">
                                
                                <div class="form-group mb-0">
                                    <label><i class="fas fa-ticket-alt text-primary mr-1"></i> هل لديك كود خصم؟</label>
                                    <div class="coupon-group">
                                        <input type="text" id="couponCode" name="coupon_code" class="form-control" placeholder="أدخل الكود هنا">
                                        <button type="button" class="btn-apply" id="applyCouponBtn">تطبيق</button>
                                    </div>
                                    <small id="couponMessage" class="form-text mt-2"></small>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <i class="fas fa-shield-alt text-success" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                <h6 class="font-weight-bold mb-1">دفع آمن وموثوق</h6>
                                <p class="text-muted small">نحن نضمن حماية بياناتك وأنظمة دفع مشفرة بالكامل.</p>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // -- File Upload UI --
    const uploadInput = document.getElementById('paymentProof');
    const uploadArea = document.getElementById('uploadArea');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    
    if(uploadInput) {
        uploadInput.addEventListener('change', function() {
            if(this.files && this.files[0]) {
                uploadArea.classList.add('has-file');
                fileNameDisplay.textContent = this.files[0].name;
                fileNameDisplay.style.display = 'block';
            } else {
                uploadArea.classList.remove('has-file');
                fileNameDisplay.style.display = 'none';
            }
        });
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#136ad5';
            uploadArea.style.background = 'rgba(19, 106, 213, 0.05)';
        });
        
        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '';
            uploadArea.style.background = '';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '';
            uploadArea.style.background = '';
            
            if(e.dataTransfer.files && e.dataTransfer.files[0]) {
                uploadInput.files = e.dataTransfer.files;
                
                const event = new Event('change');
                uploadInput.dispatchEvent(event);
            }
        });
    }

    // -- Coupon Logic --
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponInput = document.getElementById('couponCode');
    const couponMessage = document.getElementById('couponMessage');
    
    const finalAmountText = document.getElementById('finalAmountText');
    const discountRow = document.getElementById('discountRow');
    const discountAmountText = document.getElementById('discountAmountText');
    
    const paypalAmountDisplay = document.getElementById('paypalAmountDisplay');
    const paypalLinkDisplay = document.getElementById('paypalLinkDisplay');
    const paypalPayBtn = document.getElementById('paypalPayBtn');
    const paypalBtnAmount = document.getElementById('paypalBtnAmount');
    const paypalCopyBtn = document.getElementById('paypalCopyBtn');
    
    const baseAmountSAR = <?= json_encode((float) ($cart_total ?? 0)) ?>;
    
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const code = couponInput.value.trim();
            if(!code) return;
            
            applyCouponBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            applyCouponBtn.disabled = true;
            
            // Pass course_id = 0 for cart
            const formData = new FormData();
            formData.append('coupon_code', code);
            formData.append('course_id', 0);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            
            fetch('<?= site_url('enrollments/validate-coupon') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                applyCouponBtn.innerHTML = 'تطبيق';
                applyCouponBtn.disabled = false;
                
                if(data.success) {
                    couponMessage.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${data.message}</span>`;
                    
                    const newTotalSAR = parseFloat(data.new_total);
                    const discountSAR = baseAmountSAR - newTotalSAR;
                    
                    const newTotalUSD = (newTotalSAR / 3.75).toFixed(2);
                    const discountUSD = (discountSAR / 3.75).toFixed(2);
                    
                    discountRow.style.display = 'flex';
                    discountAmountText.textContent = `-$${discountUSD}`;
                    finalAmountText.textContent = `$${newTotalUSD}`;
                    
                    // Update PayPal
                    if(paypalAmountDisplay) paypalAmountDisplay.textContent = newTotalUSD;
                    if(paypalBtnAmount) paypalBtnAmount.textContent = newTotalUSD;
                    
                    const pLink = `paypal.me/webeasystep/${newTotalUSD}`;
                    const pUrl = `https://paypal.me/webeasystep/${newTotalUSD}`;
                    
                    if(paypalLinkDisplay) paypalLinkDisplay.textContent = pLink;
                    if(paypalPayBtn) paypalPayBtn.href = pUrl;
                    if(paypalCopyBtn) paypalCopyBtn.dataset.copyText = pUrl;
                    
                    // If free via coupon
                    if(newTotalSAR <= 0) {
                        document.getElementById('paypal-instructions').style.display = 'none';
                        document.querySelector('.payment-methods-section').style.display = 'none';
                        if(uploadInput) uploadInput.removeAttribute('required');
                    }
                    
                } else {
                    couponMessage.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle"></i> ${data.message}</span>`;
                    resetPricing();
                }
            })
            .catch(err => {
                applyCouponBtn.innerHTML = 'تطبيق';
                applyCouponBtn.disabled = false;
                couponMessage.innerHTML = `<span class="text-danger">حدث خطأ في الشبكة.</span>`;
            });
        });
    }
    
    function resetPricing() {
        const baseUSD = (baseAmountSAR / 3.75).toFixed(2);
        discountRow.style.display = 'none';
        finalAmountText.textContent = `$${baseUSD}`;
        
        if(paypalAmountDisplay) paypalAmountDisplay.textContent = baseUSD;
        if(paypalBtnAmount) paypalBtnAmount.textContent = baseUSD;
        
        const pLink = `paypal.me/webeasystep/${baseUSD}`;
        const pUrl = `https://paypal.me/webeasystep/${baseUSD}`;
        
        if(paypalLinkDisplay) paypalLinkDisplay.textContent = pLink;
        if(paypalPayBtn) paypalPayBtn.href = pUrl;
        if(paypalCopyBtn) paypalCopyBtn.dataset.copyText = pUrl;
        
        document.getElementById('paypal-instructions').style.display = 'block';
        document.querySelector('.payment-methods-section').style.display = 'block';
        if(uploadInput) uploadInput.setAttribute('required', 'required');
    }
});

function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    });
}
</script>

<?= $this->endSection(); ?>
