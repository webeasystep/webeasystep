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
        padding: 16px 20px;
        margin-bottom: 16px;
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
        width: 54px;
        height: 54px;
        background: linear-gradient(135deg, #136ad5, #1e88e5);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
        color: #fff;
        overflow: hidden;
    }
    .course-info-text h5 {
        font-size: 1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 4px;
    }
    body.dark-mode .course-info-text h5 { color: #f1f5f9; }
    .course-info-text p {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0;
    }
    body.dark-mode .course-info-text p { color: #94a3b8; }
    .course-price-badge {
        margin-right: auto;
        background: #e0f2fe;
        color: #0284c7;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
        white-space: nowrap;
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
    
    /* ── Payment Method Accordion Card ── */
    .payment-method-card {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 14px;
        background: #fff;
        transition: all 0.25s ease;
        overflow: hidden;
    }
    body.dark-mode .payment-method-card {
        background: #0f172a;
        border-color: rgba(255,255,255,0.1);
    }
    .payment-method-card:hover {
        border-color: #93c5fd;
    }
    .payment-method-card.active {
        border-color: #136ad5;
        box-shadow: 0 4px 18px rgba(19, 106, 213, 0.12);
    }
    body.dark-mode .payment-method-card.active {
        border-color: #3b82f6;
        box-shadow: 0 4px 18px rgba(59, 130, 246, 0.18);
    }
    
    .payment-method-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        margin: 0;
        cursor: pointer;
        background: inherit;
        transition: background 0.2s;
    }
    .payment-method-card.active .payment-method-header {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
    }
    body.dark-mode .payment-method-card.active .payment-method-header {
        background: rgba(19, 106, 213, 0.12);
        border-bottom-color: rgba(255,255,255,0.08);
    }
    
    .payment-method-header input[type="radio"] {
        display: none;
    }
    .payment-radio {
        width: 22px;
        height: 22px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        position: relative;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .payment-method-card.active .payment-radio {
        border-color: #136ad5;
    }
    body.dark-mode .payment-method-card.active .payment-radio {
        border-color: #3b82f6;
    }
    .payment-method-card.active .payment-radio::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 10px; height: 10px;
        background: #136ad5;
        border-radius: 50%;
    }
    body.dark-mode .payment-method-card.active .payment-radio::after { background: #3b82f6; }
    
    .payment-icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 34px;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .payment-icon-wrap svg, .payment-icon-wrap img {
        max-height: 28px;
        max-width: 46px;
        object-fit: contain;
    }

    .payment-text-area {
        flex: 1;
        min-width: 0;
    }
    .payment-text {
        font-weight: 700;
        color: #1e293b;
        font-size: 1rem;
        margin-bottom: 2px;
    }
    body.dark-mode .payment-text { color: #e2e8f0; }
    .payment-subtext {
        font-size: 0.78rem;
        color: #64748b;
    }
    body.dark-mode .payment-subtext { color: #94a3b8; }

    .payment-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .payment-badge.ksa {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    body.dark-mode .payment-badge.ksa {
        background: rgba(34,197,94,0.15);
        color: #4ade80;
    }
    .payment-badge.intl {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }
    body.dark-mode .payment-badge.intl {
        background: rgba(59,130,246,0.15);
        color: #60a5fa;
    }

    /* ── Details Panel Immediately Below Selected Header ── */
    .payment-method-details {
        display: none;
        padding: 18px 20px;
        animation: fadeIn 0.3s ease;
        background: #fff;
    }
    body.dark-mode .payment-method-details {
        background: #0f172a;
    }
    .payment-method-card.active .payment-method-details {
        display: block;
    }
    
    .pm-alert-box {
        background: #f0f7ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 14px;
        color: #1e40af;
        font-size: 0.83rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    body.dark-mode .pm-alert-box {
        background: rgba(59,130,246,0.1);
        border-color: rgba(59,130,246,0.25);
        color: #93c5fd;
    }

    .payment-info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
    }
    body.dark-mode .payment-info-box {
        background: #1e293b;
        border-color: rgba(255,255,255,0.08);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 8px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
    }
    body.dark-mode .info-row { border-bottom-color: rgba(255,255,255,0.05); }
    .info-row:hover {
        background: #eff6ff;
        padding-right: 12px;
        padding-left: 12px;
    }
    body.dark-mode .info-row:hover { background: #24344d; }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-row.highlight-row {
        background: #f0f7ff;
        border: 1.5px solid #bfdbfe;
    }
    body.dark-mode .info-row.highlight-row {
        background: rgba(59,130,246,0.08);
        border-color: rgba(59,130,246,0.3);
    }
    .info-row.amount-row {
        background: #fffbeb;
        border: 1.5px solid #fde68a;
    }
    body.dark-mode .info-row.amount-row {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.3);
    }

    .info-label {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
    }
    body.dark-mode .info-label { color: #94a3b8; }
    .info-value {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
    }
    body.dark-mode .info-value { color: #f1f5f9; }
    .info-value.monospace {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        direction: ltr;
        text-align: left;
        letter-spacing: 0.6px;
    }
    
    /* ── Copy Button ── */
    .btn-copy {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #e2e8f0;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        cursor: pointer;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        transition: all 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    body.dark-mode .btn-copy {
        background: #334155;
        border-color: rgba(255,255,255,0.15);
        color: #f1f5f9;
    }
    .btn-copy:hover {
        background: #136ad5;
        color: #fff;
        border-color: #136ad5;
    }
    .btn-copy-highlight {
        background: #136ad5;
        color: #fff;
        border-color: #136ad5;
    }
    .btn-copy-highlight:hover {
        background: #0f5bbf;
        color: #fff;
    }
    .btn-copy.copied {
        background: #16a34a !important;
        color: #fff !important;
        border-color: #16a34a !important;
    }
    
    .btn-pay-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none !important;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top: 12px;
    }
    .btn-paypal-pay {
        background: linear-gradient(135deg, #003087, #0070ba);
        color: #fff;
        box-shadow: 0 4px 14px rgba(0,48,135,0.35);
    }
    .btn-paypal-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,48,135,0.45);
        color: #fff;
    }

    /* ── Upload Area ── */
    .upload-section-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
    }
    body.dark-mode .upload-section-card {
        background: #1e293b;
        border-color: rgba(255,255,255,0.08);
    }
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 24px 16px;
        text-align: center;
        background: #fff;
        transition: all 0.2s;
        cursor: pointer;
        position: relative;
    }
    body.dark-mode .upload-area {
        background: #0f172a;
        border-color: rgba(255,255,255,0.15);
    }
    .upload-area:hover {
        border-color: #136ad5;
        background: #eff6ff;
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
        font-size: 32px;
        color: #94a3b8;
        margin-bottom: 8px;
    }
    .upload-area.has-file .upload-icon { color: #10b981; }
    
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
                <h4>إتمام الشراء والاشتراك</h4>
            </div>
            
            <div class="checkout-body">
                <form action="<?= site_url('cart/checkout') ?>" method="POST" enctype="multipart/form-data" id="checkoutForm">
                    <?= csrf_field() ?>
                    
                    <div class="checkout-grid">
                        
                        <!-- Left Column (Form) -->
                        <div class="checkout-form-col">
                            
                            <!-- Items Info -->
                            <h5 class="section-title"><i class="fas fa-shopping-basket"></i> محتويات السلة</h5>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="course-info-block">
                                    <div class="course-info-thumb">
                                        <?php if ($item->image && $item->image !== '[]'): ?>
                                            <?php if ($item->item_type === 'course'): ?>
                                                <img src="<?= base_url('uploads/courses/' . $item->image) ?>" alt="<?= esc($item->title) ?>" class="img-fluid" style="width:100%; height:100%; object-fit:cover;">
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/bundles/' . $item->image) ?>" alt="<?= esc($item->title) ?>" class="img-fluid" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='<?= base_url('uploads/courses/' . $item->image) ?>'">
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
                                        <i class="fas fa-tag"></i> <?= esc(number_format((float) $item->price, 2)) ?> <?= riyal_icon('13px') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Payment Methods (Accordion: Details right after selected radio) -->
                            <div class="payment-methods-section mt-4" <?= $cart_total <= 0 ? 'style="display:none;"' : '' ?>>
                                <h5 class="section-title"><i class="fas fa-wallet"></i> طريقة الدفع</h5>
                                
                                <!-- ═══════════ OPTION 1: ARAB NATIONAL BANK (DEFAULT / ACTIVE) ═══════════ -->
                                <div class="payment-method-card active" id="card-anb">
                                    <label class="payment-method-header" for="pm_anb">
                                        <input type="radio" name="payment_method" value="anb" id="pm_anb" checked>
                                        <div class="payment-radio"></div>
                                        <div class="payment-icon-wrap">
                                            <svg viewBox="0 0 120 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="120" height="42" rx="8" fill="#e8f3fc"/>
                                                <text x="60" y="28" text-anchor="middle" fill="#005baa" font-family="'Segoe UI', Roboto, sans-serif" font-size="20" font-weight="900">anb</text>
                                            </svg>
                                        </div>
                                        <div class="payment-text-area">
                                            <div class="payment-text">البنك العربي الوطني - ANB</div>
                                            <div class="payment-subtext">تحويل بنكي مباشر (حساب / آيبان)</div>
                                        </div>
                                        <span class="payment-badge ksa"><i class="fas fa-university"></i> داخل السعودية</span>
                                    </label>

                                    <!-- Immediately Below Radio 1: ANB Details Block -->
                                    <div class="payment-method-details" id="anb-details">
                                        <div class="pm-alert-box">
                                            <i class="fas fa-info-circle"></i>
                                            <span>يمكنك التحويل من أي بنك سعودي (اضغط على أي حقل لنسخه مباشرة):</span>
                                        </div>

                                        <div class="payment-info-box">
                                            <!-- Bank Name -->
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="Arab National Bank" title="اضغط للنسخ">
                                                <span class="info-label">اسم البنك:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value">Arab National Bank (البنك العربي الوطني)</span>
                                                    <button type="button" class="btn-copy" data-copy-text="Arab National Bank" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Beneficiary Name -->
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="MOSTAFA MAHMOD FAKHRELDIN MOHAMED" title="اضغط للنسخ">
                                                <span class="info-label">اسم المستفيد:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value monospace">MOSTAFA MAHMOD FAKHRELDIN MOHAMED</span>
                                                    <button type="button" class="btn-copy" data-copy-text="MOSTAFA MAHMOD FAKHRELDIN MOHAMED" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- IBAN -->
                                            <div class="info-row highlight-row" onclick="copyFromRow(this)" data-copy="SA2630100991106970328455" title="اضغط للنسخ">
                                                <span class="info-label">رقم الآيبان (IBAN):</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value monospace text-primary font-weight-bold">SA2630100991106970328455</span>
                                                    <button type="button" class="btn-copy btn-copy-highlight" data-copy-text="SA2630100991106970328455" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ الآيبان
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Account Number -->
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="991106970328455" title="اضغط للنسخ">
                                                <span class="info-label">رقم الحساب:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value monospace">991106970328455</span>
                                                    <button type="button" class="btn-copy" data-copy-text="991106970328455" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ═══════════ OPTION 2: STC BANK ═══════════ -->
                                <div class="payment-method-card" id="card-stc">
                                    <label class="payment-method-header" for="pm_stc_bank">
                                        <input type="radio" name="payment_method" value="stc_bank" id="pm_stc_bank">
                                        <div class="payment-radio"></div>
                                        <div class="payment-icon-wrap">
                                            <svg viewBox="0 0 130 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="130" height="42" rx="8" fill="#f7f1fc"/>
                                                <text x="44" y="27" text-anchor="middle" fill="#4f008c" font-family="'Segoe UI', Roboto, sans-serif" font-size="18" font-weight="900">stc</text>
                                                <text x="88" y="27" text-anchor="middle" fill="#00c48c" font-family="'Segoe UI', Roboto, sans-serif" font-size="15" font-weight="700">bank</text>
                                            </svg>
                                        </div>
                                        <div class="payment-text-area">
                                            <div class="payment-text">بنك إس تي سي - STC Bank</div>
                                            <div class="payment-subtext">تحويل فوري مباشر (حساب / آيبان)</div>
                                        </div>
                                        <span class="payment-badge ksa"><i class="fas fa-university"></i> داخل السعودية</span>
                                    </label>

                                    <!-- Immediately Below Radio 2: STC Bank Details Block -->
                                    <div class="payment-method-details" id="stc-details">
                                        <div class="pm-alert-box" style="background:#faf5ff; border-color:#e9d5ff; color:#6b21a8;">
                                            <i class="fas fa-info-circle"></i>
                                            <span>يمكنك التحويل من تطبيق STC Bank أو أي بنك سعودي (اضغط على أي حقل لنسخه مباشرة):</span>
                                        </div>

                                        <div class="payment-info-box">
                                            <!-- Bank Name -->
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="STC Bank" title="اضغط للنسخ">
                                                <span class="info-label">اسم البنك:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value">STC Bank (بنك إس تي سي)</span>
                                                    <button type="button" class="btn-copy" data-copy-text="STC Bank" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Customer / Beneficiary Name -->
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="مصطفى محمد" title="اضغط للنسخ">
                                                <span class="info-label">اسم العميل / المستفيد:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value">مصطفى محمد</span>
                                                    <button type="button" class="btn-copy" data-copy-text="مصطفى محمد" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- IBAN -->
                                            <div class="info-row highlight-row" onclick="copyFromRow(this)" data-copy="SA8178000000001261711229" title="اضغط للنسخ">
                                                <span class="info-label">رقم الحساب ايبان (IBAN):</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value monospace font-weight-bold" style="color:#4f008c;">SA8178000000001261711229</span>
                                                    <button type="button" class="btn-copy btn-copy-highlight" data-copy-text="SA8178000000001261711229" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ الآيبان
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Account Number -->
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="1261711229" title="اضغط للنسخ">
                                                <span class="info-label">رقم الحساب:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value monospace">1261711229</span>
                                                    <button type="button" class="btn-copy" data-copy-text="1261711229" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ═══════════ OPTION 3: PAYPAL ═══════════ -->
                                <div class="payment-method-card" id="card-paypal">
                                    <label class="payment-method-header" for="pm_paypal">
                                        <input type="radio" name="payment_method" value="paypal" id="pm_paypal">
                                        <div class="payment-radio"></div>
                                        <div class="payment-icon-wrap">
                                            <i class="fab fa-paypal" style="color: #003087; font-size: 24px;"></i>
                                        </div>
                                        <div class="payment-text-area">
                                            <div class="payment-text">باي بال - PayPal</div>
                                            <div class="payment-subtext">الدفع الدولي والبطاقات الائتمانية</div>
                                        </div>
                                        <span class="payment-badge intl"><i class="fab fa-paypal"></i> دولي</span>
                                    </label>

                                    <!-- Immediately Below Radio 3: PayPal Details Block -->
                                    <div class="payment-method-details" id="paypal-details">
                                        <div class="payment-info-box">
                                            <div class="info-row">
                                                <span class="info-label">المبلغ المطلوب بالدولار:</span>
                                                <div class="info-value text-primary font-weight-bold" style="font-size: 1.15rem;">
                                                    $<span id="paypalAmountDisplay"><?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></span> USD
                                                </div>
                                            </div>
                                            <div class="info-row" onclick="copyFromRow(this)" data-copy="https://paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?>" title="اضغط للنسخ">
                                                <span class="info-label">رابط الدفع:</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="info-value monospace text-truncate" id="paypalLinkDisplay">paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></span>
                                                    <button type="button" class="btn-copy" id="paypalCopyBtn" data-copy-text="https://paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?>" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                        <i class="far fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <a href="https://paypal.me/webeasystep/<?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?>" target="_blank" class="btn-pay-link btn-paypal-pay" id="paypalPayBtn">
                                            <i class="fab fa-paypal"></i>
                                            <span id="paypalPayBtnText">دفع مبلغ $<span id="paypalBtnAmount"><?= esc(number_format((float) $cart_total / 3.75, 2, '.', '')) ?></span> عبر PayPal</span>
                                        </a>
                                    </div>
                                </div>

                            </div>

                            <!-- ═══════════ File Upload Area (Below payment methods) ═══════════ -->
                            <div class="upload-section-card mt-4" id="uploadSectionBox" <?= $cart_total <= 0 ? 'style="display:none;"' : '' ?>>
                                <h6 class="font-weight-bold mb-2 text-dark"><i class="fas fa-file-invoice text-success me-1"></i> إرفاق إشعار التحويل / الإيصال</h6>
                                <p class="text-muted small mb-2">بعد إتمام التحويل أو الدفع، ارفع صورة الإشعار لتأكيد طلبك وتفعيل اشتراكك.</p>
                                
                                <div class="upload-area" id="uploadArea">
                                    <input type="file" name="payment_proof" id="paymentProof" accept="image/*,.pdf" <?= $cart_total > 0 ? 'required' : '' ?>>
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <h6 class="mb-1 text-dark" style="font-size: 0.95rem; font-weight:700;">اضغط هنا أو اسحب صورة إشعار التحويل</h6>
                                    <p class="text-muted small mb-0">PNG, JPG, JPEG أو PDF (الحد الأقصى 5MB)</p>
                                    <div id="fileNameDisplay" class="mt-2 text-primary font-weight-bold" style="display:none;"></div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-check-circle"></i> تأكيد وإرسال طلب الاشتراك
                            </button>
                            
                        </div>
                        
                        <!-- Right Column (Summary) -->
                        <div class="checkout-summary-col">
                            <h5 class="section-title"><i class="fas fa-receipt"></i> ملخص الطلب</h5>
                            
                            <div class="summary-card">
                                <div class="summary-row">
                                    <span>المجموع الأصلي</span>
                                    <strong id="originalAmountText"><?= esc(number_format((float) $cart_total, 2)) ?> <?= riyal_icon('14px') ?> <small class="text-muted font-weight-normal">($<?= esc(number_format((float) $cart_total / 3.75, 2)) ?>)</small></strong>
                                </div>
                                
                                <div class="summary-row text-success" id="discountRow" style="display: none;">
                                    <span>الخصم (كوبون)</span>
                                    <strong id="discountAmountText">-0.00 <?= riyal_icon('14px') ?></strong>
                                </div>
                                
                                <div class="summary-row total">
                                    <span>الإجمالي المطلوب للدفع</span>
                                    <strong id="finalAmountText" class="text-primary"><?= esc(number_format((float) $cart_total, 2)) ?> <?= riyal_icon('16px', '#136ad5') ?> <small class="text-muted font-weight-normal">($<?= esc(number_format((float) $cart_total / 3.75, 2)) ?>)</small></strong>
                                </div>
                                
                                <hr class="my-4" style="border-color: rgba(0,0,0,0.06);">
                                
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
                                <p class="text-muted small">نحن نضمن حماية بياناتك وأنظمة دفع وتفعيل مؤمنة بالكامل.</p>
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
    
    // ── Payment Option Accordion Switching ──
    const paymentCards = document.querySelectorAll('.payment-method-card');
    
    paymentCards.forEach(card => {
        const header = card.querySelector('.payment-method-header');
        if (header) {
            header.addEventListener('click', function(e) {
                paymentCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                
                const radio = card.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        }
    });

    // ── File Upload UI ──
    const uploadInput = document.getElementById('paymentProof');
    const uploadArea = document.getElementById('uploadArea');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    
    if(uploadInput && uploadArea) {
        uploadInput.addEventListener('change', function() {
            if(this.files && this.files[0]) {
                uploadArea.classList.add('has-file');
                fileNameDisplay.textContent = 'تم اختيار الملف: ' + this.files[0].name;
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

    // ── Coupon Logic ──
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
    const uploadSectionBox = document.getElementById('uploadSectionBox');
    
    const baseAmountSAR = <?= json_encode((float) ($cart_total ?? 0)) ?>;
    
    function updateAllAmounts(sarTotal, discountSAR) {
        const sarFormatted = Number(sarTotal).toFixed(2);
        const usdFormatted = (Number(sarTotal) / 3.75).toFixed(2);
        const discountSarFormatted = Number(discountSAR).toFixed(2);
        const discountUsdFormatted = (Number(discountSAR) / 3.75).toFixed(2);

        // Update all SAR displays in ANB & STC Bank
        document.querySelectorAll('.sar-cart-amount').forEach(el => {
            el.textContent = sarFormatted;
        });

        // Update all SAR copy buttons
        document.querySelectorAll('.sar-copy-btn').forEach(btn => {
            btn.dataset.copyText = sarFormatted;
            const parentRow = btn.closest('.info-row');
            if (parentRow) parentRow.setAttribute('data-copy', sarFormatted);
        });

        const RIYAL_ICON_HTML = '<?= addslashes(riyal_icon("14px")) ?>';
        // Update Summary
        if (discountSAR > 0) {
            discountRow.style.display = 'flex';
            discountAmountText.innerHTML = `-${discountSarFormatted} ${RIYAL_ICON_HTML} ($${discountUsdFormatted})`;
        } else {
            discountRow.style.display = 'none';
        }
        finalAmountText.innerHTML = `${sarFormatted} ${RIYAL_ICON_HTML} <small class="text-muted font-weight-normal">($${usdFormatted})</small>`;

        // Update PayPal displays
        if(paypalAmountDisplay) paypalAmountDisplay.textContent = usdFormatted;
        if(paypalBtnAmount) paypalBtnAmount.textContent = usdFormatted;
        
        const pLink = `paypal.me/webeasystep/${usdFormatted}`;
        const pUrl = `https://paypal.me/webeasystep/${usdFormatted}`;
        
        if(paypalLinkDisplay) paypalLinkDisplay.textContent = pLink;
        if(paypalPayBtn) paypalPayBtn.href = pUrl;
        if(paypalCopyBtn) paypalCopyBtn.dataset.copyText = pUrl;
    }

    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const code = couponInput.value.trim();
            if(!code) return;
            
            applyCouponBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            applyCouponBtn.disabled = true;
            
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
                    
                    const newTotalSAR = parseFloat(data.final_amount !== undefined ? data.final_amount : (data.new_total || 0));
                    const discountSAR = parseFloat(data.discount_amount !== undefined ? data.discount_amount : (baseAmountSAR - newTotalSAR));
                    
                    updateAllAmounts(newTotalSAR, discountSAR);
                    
                    // If 100% free via coupon
                    if(newTotalSAR <= 0) {
                        document.querySelector('.payment-methods-section').style.display = 'none';
                        if (uploadSectionBox) uploadSectionBox.style.display = 'none';
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
                couponMessage.innerHTML = `<span class="text-danger">حدث خطأ في التحقق من الكوبون.</span>`;
            });
        });
    }
    
    function resetPricing() {
        updateAllAmounts(baseAmountSAR, 0);
        
        if (baseAmountSAR > 0) {
            document.querySelector('.payment-methods-section').style.display = 'block';
            if (uploadSectionBox) uploadSectionBox.style.display = 'block';
            if(uploadInput) uploadInput.setAttribute('required', 'required');
        } else {
            document.querySelector('.payment-methods-section').style.display = 'none';
            if (uploadSectionBox) uploadSectionBox.style.display = 'none';
            if(uploadInput) uploadInput.removeAttribute('required');
        }
    }
});

// ── Copy to clipboard helper ──
window.copyToClipboard = function(text, btn) {
    if (!text) return;
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            if (btn) showCopiedState(btn);
        }).catch(() => {
            fallbackCopy(text, btn);
        });
    } else {
        fallbackCopy(text, btn);
    }
};

window.copyFromRow = function(row) {
    const text = row.getAttribute('data-copy');
    if (!text) return;
    const btn = row.querySelector('.btn-copy');
    copyToClipboard(text, btn);
};

function fallbackCopy(text, btn) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    textarea.style.top = '0';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.focus();
    textarea.select();
    try {
        const successful = document.execCommand('copy');
        if (successful && btn) {
            showCopiedState(btn);
        }
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    document.body.removeChild(textarea);
}

function showCopiedState(btn) {
    if (!btn) return;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check text-success"></i> تم النسخ!';
    btn.classList.add('copied');
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('copied');
    }, 2000);
}
</script>

<?= $this->endSection(); ?>
