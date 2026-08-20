<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

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
    .course-price-tag {
        margin-right: auto;
        text-align: center;
        flex-shrink: 0;
    }
    .price-badge-free {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .price-badge-paid {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Divider ── */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 600;
    }
    body.dark-mode .section-divider { color: #94a3b8; }
    .section-divider::before,
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    body.dark-mode .section-divider::before,
    body.dark-mode .section-divider::after { background: rgba(255,255,255,0.1); }

    /* ── FREE COURSE BLOCK ── */
    .free-course-block {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #86efac;
        border-radius: 16px;
        padding: 28px;
        text-align: center;
        margin-bottom: 28px;
    }
    body.dark-mode .free-course-block {
        background: rgba(34,197,94,0.08);
        border-color: rgba(34,197,94,0.3);
    }
    .free-course-block .free-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
        color: #fff;
        box-shadow: 0 8px 20px rgba(34,197,94,0.35);
    }
    .free-course-block h5 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #15803d;
        margin-bottom: 6px;
    }
    body.dark-mode .free-course-block h5 { color: #4ade80; }
    .free-course-block p {
        color: #166534;
        font-size: 0.9rem;
        margin: 0;
    }
    body.dark-mode .free-course-block p { color: #86efac; }

    /* ── Payment Method Radio Cards ── */
    .payment-methods-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 28px;
    }
    .payment-method-card {
        position: relative;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    body.dark-mode .payment-method-card {
        background: #0f172a;
        border-color: rgba(255,255,255,0.1);
    }
    .payment-method-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 4px 16px rgba(19,106,213,0.12);
        transform: translateY(-2px);
    }
    body.dark-mode .payment-method-card:hover {
        border-color: rgba(96,165,250,0.4);
        box-shadow: 0 4px 16px rgba(96,165,250,0.15);
    }
    .payment-method-card.selected {
        border-color: #136ad5;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        box-shadow: 0 6px 24px rgba(19,106,213,0.18);
    }
    body.dark-mode .payment-method-card.selected {
        border-color: #3b82f6;
        background: rgba(59,130,246,0.08);
        box-shadow: 0 6px 24px rgba(59,130,246,0.2);
    }
    .payment-method-card input[type="radio"] { display: none; }
    .payment-method-card .pm-header {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .pm-radio-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    body.dark-mode .pm-radio-circle { border-color: #475569; }
    .pm-radio-circle::after {
        content: '';
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #136ad5;
        transform: scale(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .payment-method-card.selected .pm-radio-circle {
        border-color: #136ad5;
    }
    body.dark-mode .payment-method-card.selected .pm-radio-circle { border-color: #3b82f6; }
    .payment-method-card.selected .pm-radio-circle::after {
        transform: scale(1);
    }
    body.dark-mode .payment-method-card.selected .pm-radio-circle::after { background: #3b82f6; }
    
    .pm-logo-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 42px;
        min-width: 90px;
        flex-shrink: 0;
    }
    .pm-logo {
        height: 40px;
        width: auto;
        max-width: 120px;
        object-fit: contain;
    }
    .pm-title-area {
        flex: 1;
        min-width: 0;
    }
    .pm-title {
        font-size: 1.02rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px;
    }
    body.dark-mode .pm-title { color: #f1f5f9; }
    .pm-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
    }
    body.dark-mode .pm-subtitle { color: #94a3b8; }
    
    /* ── Region Badges ── */
    .pm-region-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .pm-region-badge.ksa {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 1px solid #86efac;
    }
    body.dark-mode .pm-region-badge.ksa {
        background: rgba(34,197,94,0.15);
        color: #4ade80;
        border-color: rgba(34,197,94,0.3);
    }
    .pm-region-badge.international {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border: 1px solid #93c5fd;
    }
    body.dark-mode .pm-region-badge.international {
        background: rgba(59,130,246,0.15);
        color: #60a5fa;
        border-color: rgba(59,130,246,0.3);
    }

    /* ── Payment Details Panel ── */
    .pm-details {
        display: none;
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid #e2e8f0;
        animation: slideDown 0.35s ease-out;
    }
    body.dark-mode .pm-details { border-top-color: rgba(255,255,255,0.08); }
    .payment-method-card.selected .pm-details { display: block; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0);    }
    }

    .pm-alert-box {
        background: #f0f7ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 10px 14px;
        margin-bottom: 14px;
        color: #1e40af;
        font-size: 0.84rem;
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

    .pm-info-row {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    body.dark-mode .pm-info-row {
        background: #1e293b;
        border-color: rgba(255,255,255,0.1);
    }
    .pm-info-row:hover {
        border-color: #93c5fd;
        background: #f8fafc;
        transform: translateX(-2px);
    }
    body.dark-mode .pm-info-row:hover {
        border-color: rgba(59,130,246,0.4);
        background: #24344d;
    }
    .pm-info-row.highlight-row {
        background: #f0f7ff;
        border: 1.5px solid #93c5fd;
    }
    body.dark-mode .pm-info-row.highlight-row {
        background: rgba(59,130,246,0.08);
        border-color: rgba(59,130,246,0.35);
    }
    .pm-info-row.amount-row {
        background: #fffbeb;
        border: 1.5px solid #fde68a;
    }
    body.dark-mode .pm-info-row.amount-row {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.3);
    }

    .pm-info-row .info-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }
    .pm-info-row .info-icon.anb-icon {
        background: linear-gradient(135deg, #005baa, #0078d4);
        color: #fff;
    }
    .pm-info-row .info-icon.stc-icon {
        background: linear-gradient(135deg, #4f008c, #00c48c);
        color: #fff;
    }
    .pm-info-row .info-icon.paypal-icon {
        background: linear-gradient(135deg, #003087, #009cde);
        color: #fff;
    }

    .pm-info-row .info-content {
        flex: 1;
        min-width: 0;
    }
    .pm-info-row .info-label {
        font-size: 0.76rem;
        color: #64748b;
        margin-bottom: 2px;
        font-weight: 600;
    }
    body.dark-mode .pm-info-row .info-label { color: #94a3b8; }
    
    .pm-info-row .info-value {
        font-size: 0.94rem;
        font-weight: 700;
        color: #1e293b;
        word-break: break-all;
    }
    body.dark-mode .pm-info-row .info-value { color: #f1f5f9; }
    .pm-info-row .info-value.monospace {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        letter-spacing: 0.6px;
        direction: ltr;
        text-align: left;
    }
    .pm-info-row .info-value.text-anb {
        color: #005baa;
    }
    body.dark-mode .pm-info-row .info-value.text-anb { color: #60a5fa; }
    .pm-info-row .info-value.text-stc {
        color: #4f008c;
    }
    body.dark-mode .pm-info-row .info-value.text-stc { color: #c084fc; }

    /* ── Copy Button ── */
    .btn-copy {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #334155;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    body.dark-mode .btn-copy {
        background: #0f172a;
        border-color: rgba(255,255,255,0.15);
        color: #cbd5e1;
    }
    .btn-copy:hover {
        background: #136ad5;
        color: #fff;
        border-color: #136ad5;
        transform: scale(1.03);
    }
    .btn-copy-highlight {
        background: #136ad5;
        color: #fff;
        border-color: #136ad5;
    }
    .btn-copy-highlight:hover {
        background: #0f5bbf;
        color: #fff;
        border-color: #0f5bbf;
    }
    .btn-copy.copied {
        background: #16a34a !important;
        color: #fff !important;
        border-color: #16a34a !important;
        box-shadow: 0 0 10px rgba(22,163,74,0.4);
    }

    .btn-pay-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none !important;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
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
    .upload-instructions {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 2px solid #f59e0b;
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 20px;
        display: none;
    }
    body.dark-mode .upload-instructions {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.35);
    }
    .upload-instructions.visible { display: block; }
    .upload-instructions h6 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    body.dark-mode .upload-instructions h6 { color: #fbbf24; }
    .upload-instructions ol {
        margin: 0;
        padding-right: 20px;
        color: #78350f;
        font-size: 0.85rem;
        line-height: 1.8;
    }
    body.dark-mode .upload-instructions ol { color: #fcd34d; }
    
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 32px 20px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        margin-bottom: 20px;
        display: none;
        position: relative;
        background: #f8fafc;
    }
    body.dark-mode .upload-area {
        border-color: rgba(255,255,255,0.15);
        background: #0f172a;
    }
    .upload-area.visible { display: block; }
    .upload-area:hover,
    .upload-area.dragover {
        border-color: #136ad5;
        background: #eff6ff;
        box-shadow: 0 0 0 3px rgba(19,106,213,0.1);
    }
    body.dark-mode .upload-area:hover,
    body.dark-mode .upload-area.dragover {
        border-color: #3b82f6;
        background: rgba(59,130,246,0.05);
    }
    .upload-area .upload-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #136ad5, #1e88e5);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 24px;
        color: #fff;
    }
    .upload-area .upload-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }
    body.dark-mode .upload-area .upload-text { color: #94a3b8; }
    .upload-area .upload-hint {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    .upload-preview {
        display: none;
        margin-bottom: 20px;
    }
    .upload-preview.visible { display: block; }
    .preview-container {
        position: relative;
        display: inline-block;
        border-radius: 14px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    body.dark-mode .preview-container { border-color: rgba(255,255,255,0.1); }
    .preview-container img {
        max-width: 100%;
        max-height: 250px;
        display: block;
    }
    .btn-remove-preview {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(239,68,68,0.9);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
        backdrop-filter: blur(4px);
    }
    .btn-remove-preview:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* ── Submit Button ── */
    .btn-submit-checkout {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #136ad5 0%, #1e88e5 100%);
        color: #fff;
        font-size: 1.1rem;
        font-weight: 700;
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: none;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(19,106,213,0.4);
    }
    .btn-submit-checkout.visible {
        display: flex;
    }
    .btn-submit-checkout:hover {
        background: linear-gradient(135deg, #0f5bbf 0%, #136ad5 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(19,106,213,0.5);
        color: #fff;
    }
    .btn-submit-checkout:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .btn-enroll-free {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #136ad5 0%, #1e88e5 100%);
        color: #fff;
        font-size: 1.05rem;
        font-weight: 700;
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(19,106,213,0.4);
    }
    .btn-enroll-free:hover {
        background: linear-gradient(135deg, #0f5bbf 0%, #136ad5 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(19,106,213,0.5);
        color: #fff;
    }

    /* ── Trust badges ── */
    .trust-badges {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .trust-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #64748b;
    }
    body.dark-mode .trust-badge { color: #94a3b8; }
    .trust-badge i { color: #136ad5; }
    body.dark-mode .trust-badge i { color: #60a5fa; }

    /* ── Amount display ── */
    .amount-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-size: 1.3rem;
        font-weight: 800;
        padding: 14px 24px;
        border-radius: 14px;
        margin-bottom: 16px;
        box-shadow: 0 4px 14px rgba(245,158,11,0.35);
        letter-spacing: 0.3px;
        flex-wrap: wrap;
    }
    .amount-display .currency {
        font-size: 0.85rem;
        font-weight: 600;
        opacity: 0.9;
    }
    .coupon-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 18px;
    }
    body.dark-mode .coupon-panel {
        background: #0f172a;
        border-color: rgba(255,255,255,0.08);
    }
    .coupon-panel-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-weight: 700;
        color: #1e293b;
    }
    body.dark-mode .coupon-panel-header { color: #f1f5f9; }
    .coupon-panel-header i { color: #136ad5; }
    .coupon-row {
        display: flex;
        gap: 10px;
    }
    .coupon-input {
        flex: 1;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 12px 14px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .coupon-input:focus {
        outline: none;
        border-color: #136ad5;
        box-shadow: 0 0 0 3px rgba(19,106,213,0.12);
    }
    .coupon-apply-btn {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #136ad5 0%, #1e88e5 100%);
        color: #fff;
        padding: 12px 18px;
        font-weight: 700;
        white-space: nowrap;
    }
    .coupon-help {
        margin-top: 8px;
        font-size: 0.84rem;
        color: #64748b;
    }
    body.dark-mode .coupon-help { color: #94a3b8; }
    .coupon-feedback {
        display: none;
        margin-top: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    .coupon-feedback.visible { display: block; }
    .coupon-feedback.success {
        background: #ecfdf5;
        border: 1px solid #86efac;
        color: #166534;
    }
    .coupon-feedback.error {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }
    body.dark-mode .coupon-feedback.success {
        background: rgba(34,197,94,0.08);
        border-color: rgba(34,197,94,0.3);
        color: #86efac;
    }
    body.dark-mode .coupon-feedback.error {
        background: rgba(239,68,68,0.08);
        border-color: rgba(239,68,68,0.3);
        color: #fca5a5;
    }
    .price-breakdown {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 18px;
    }
    body.dark-mode .price-breakdown {
        background: #0f172a;
        border-color: rgba(255,255,255,0.08);
    }
    .price-breakdown-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: #334155;
        font-size: 0.95rem;
        margin-bottom: 10px;
    }
    .price-breakdown-row:last-child { margin-bottom: 0; }
    body.dark-mode .price-breakdown-row { color: #cbd5e1; }
    .price-breakdown-row.discount {
        color: #15803d;
        display: none;
    }
    .price-breakdown-row.discount.visible { display: flex; }
    body.dark-mode .price-breakdown-row.discount { color: #4ade80; }
    .price-breakdown-row.total {
        padding-top: 10px;
        border-top: 1px dashed #cbd5e1;
        font-weight: 800;
        color: #0f172a;
        font-size: 1rem;
    }
    body.dark-mode .price-breakdown-row.total {
        border-top-color: rgba(255,255,255,0.12);
        color: #f8fafc;
    }
    .fully-discounted-note {
        display: none;
        margin-bottom: 18px;
        background: linear-gradient(135deg, #ecfdf5, #dcfce7);
        border: 1px solid #86efac;
        color: #166534;
        border-radius: 14px;
        padding: 16px 18px;
        font-weight: 700;
        line-height: 1.7;
    }
    .fully-discounted-note.visible { display: block; }
    body.dark-mode .fully-discounted-note {
        background: rgba(34,197,94,0.08);
        border-color: rgba(34,197,94,0.3);
        color: #86efac;
    }

    /* ── Steps indicator ── */
    .pm-steps {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }
    .pm-step {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        padding: 10px 14px;
        border-radius: 10px;
        flex: 1;
        font-size: 0.8rem;
        color: #475569;
        font-weight: 600;
    }
    body.dark-mode .pm-step {
        background: rgba(255,255,255,0.05);
        color: #94a3b8;
    }
    .pm-step .step-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #136ad5;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    @media (max-width: 576px) {
        .pm-steps { flex-direction: column; }
        .pm-header { flex-wrap: wrap; gap: 10px; }
        .pm-region-badge { order: -1; width: 100%; text-align: center; justify-content: center; }
        .pm-info-row { flex-wrap: wrap; }
        .btn-copy { width: 100%; justify-content: center; margin-top: 4px; }
    }
</style>

<section class="checkout-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <div class="checkout-card">
                    <!-- Header -->
                    <div class="checkout-header">
                        <div class="header-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4>إتمام الاشتراك في الدورة</h4>
                    </div>

                    <div class="checkout-body">
                        <?= $this->include('site_layout/site_msg'); ?>

                        <!-- Course Info -->
                        <div class="course-info-block">
                            <div class="course-info-thumb">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="course-info-text">
                                <h5><?= esc($course->course_title) ?></h5>
                                <?php if (!empty($course->short_desc)): ?>
                                    <p><?= esc($course->short_desc) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="course-price-tag">
                                <?php if ($is_free): ?>
                                    <span class="price-badge-free">
                                        <i class="fas fa-gift"></i> مجاني
                                    </span>
                                <?php else: ?>
                                    <span class="price-badge-paid">
                                        <i class="fas fa-tag"></i> <?= esc(number_format((float) $course->course_price, 2)) ?> <?= riyal_icon('13px') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($is_free): ?>
                            <!-- ============ FREE COURSE ============ -->
                            <div class="section-divider">طريقة التفعيل</div>

                            <div class="free-course-block">
                                <div class="free-icon">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <h5>هذا المقرر متاح مجاناً</h5>
                                <p>يمكنك الوصول الفوري لجميع محتويات الدورة دون أي رسوم</p>
                            </div>

                            <form action="<?= site_url('enrollments/course-checkout') ?>" method="post" id="checkoutForm">
                                <?= csrf_field() ?>
                                <input type="hidden" name="payment_method" value="free">
                                <button type="submit" class="btn-enroll-free">
                                    <i class="fas fa-check-circle"></i>
                                    تفعيل المقرر المجاني الآن
                                </button>
                            </form>

                        <?php else: ?>
                            <!-- ============ PAID COURSE ============ -->
                            <div class="section-divider">اختر وسيلة الدفع المناسبة لك</div>

                            <!-- Price Display -->
                            <div class="amount-display" id="amountDisplay">
                                <i class="fas fa-coins"></i>
                                <span>المبلغ المطلوب:</span>
                                <span id="finalAmountDisplay"><?= esc(number_format((float) $course->course_price, 2)) ?> <?= riyal_icon('18px', '#136ad5') ?></span>
                                <span class="currency" id="usdEquivalent">($<?= esc(number_format((float) $course->course_price / 3.75, 2)) ?> USD)</span>
                            </div>

                            <form action="<?= site_url('enrollments/course-checkout') ?>" method="post" id="checkoutForm" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="course_id" value="<?= esc($course->id) ?>">

                                <div class="coupon-panel">
                                    <div class="coupon-panel-header">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>هل لديك كوبون خصم؟</span>
                                    </div>
                                    <div class="coupon-row">
                                        <input
                                            type="text"
                                            class="coupon-input"
                                            id="couponCodeInput"
                                            name="coupon_code"
                                            value="<?= esc(old('coupon_code')) ?>"
                                            placeholder="اكتب كود الكوبون هنا"
                                            maxlength="50"
                                            autocomplete="off"
                                        >
                                        <button type="button" class="coupon-apply-btn" id="applyCouponBtn">تطبيق الكوبون</button>
                                    </div>
                                    <div class="coupon-help">يمكنك استخدام كوبون عام لكل الكورسات أو كوبون مخصص لهذا الكورس فقط.</div>
                                    <div class="coupon-feedback<?= session('error') && old('coupon_code') ? ' visible error' : '' ?>" id="couponFeedback">
                                        <?= session('error') && old('coupon_code') ? esc(session('error')) : '' ?>
                                    </div>
                                </div>

                                <div class="price-breakdown" id="priceBreakdown">
                                    <div class="price-breakdown-row">
                                        <span>سعر الكورس</span>
                                        <strong id="originalAmountText"><?= esc(number_format((float) $course->course_price, 2)) ?> <?= riyal_icon('14px') ?></strong>
                                    </div>
                                    <div class="price-breakdown-row discount" id="discountRow">
                                        <span>قيمة الخصم</span>
                                        <strong id="discountAmountText">-0.00 <?= riyal_icon('14px') ?></strong>
                                    </div>
                                    <div class="price-breakdown-row total">
                                        <span>المبلغ الإجمالي للدفع</span>
                                        <strong id="finalAmountText"><?= esc(number_format((float) $course->course_price, 2)) ?> <?= riyal_icon('16px', '#136ad5') ?></strong>
                                    </div>
                                </div>

                                <div class="fully-discounted-note" id="fullyDiscountedNote">
                                    تم تغطية سعر الكورس بالكامل بالكوبون. يمكنك إرسال الطلب الآن وسيتم تفعيل الكورس مباشرة بدون رفع إثبات دفع.
                                </div>

                                <div class="payment-methods-grid">

                                    <!-- ═══════════ 1. ARAB NATIONAL BANK (ANB) ═══════════ -->
                                    <label class="payment-method-card selected" id="card-anb" for="pm_anb">
                                        <input type="radio" name="payment_method" value="anb" id="pm_anb" checked>
                                        <div class="pm-header">
                                            <div class="pm-radio-circle"></div>
                                            <div class="pm-logo-wrap">
                                                <svg class="pm-logo" viewBox="0 0 120 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="120" height="42" rx="8" fill="#e8f3fc"/>
                                                    <path d="M22 28C22 21 27 15 35 15C43 15 48 21 48 28H42C42 24 39 19 35 19C31 19 28 24 28 28H22Z" fill="#005baa"/>
                                                    <text x="66" y="29" text-anchor="middle" fill="#005baa" font-family="'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif" font-size="22" font-weight="900" letter-spacing="-0.5">anb</text>
                                                </svg>
                                            </div>
                                            <div class="pm-title-area">
                                                <p class="pm-title">البنك العربي الوطني - ANB</p>
                                                <p class="pm-subtitle">تحويل بنكي مباشر (حساب / آيبان)</p>
                                            </div>
                                            <span class="pm-region-badge ksa">
                                                <i class="fas fa-university"></i> داخل السعودية
                                            </span>
                                        </div>

                                        <div class="pm-details">
                                            <div class="pm-alert-box">
                                                <i class="fas fa-info-circle"></i>
                                                <span>يمكنك التحويل من أي بنك سعودي (اضغط على أي حقل لنسخه مباشرة):</span>
                                            </div>

                                            <!-- Bank Name -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="Arab National Bank" title="اضغط للنسخ">
                                                <div class="info-icon anb-icon">
                                                    <i class="fas fa-landmark"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">اسم البنك</div>
                                                    <div class="info-value">Arab National Bank (البنك العربي الوطني)</div>
                                                </div>
                                                <button type="button" class="btn-copy" data-copy-text="Arab National Bank" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ
                                                </button>
                                            </div>

                                            <!-- Beneficiary Name -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="MOSTAFA MAHMOD FAKHRELDIN MOHAMED" title="اضغط للنسخ">
                                                <div class="info-icon anb-icon">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">اسم المستفيد (Beneficiary Name)</div>
                                                    <div class="info-value monospace">MOSTAFA MAHMOD FAKHRELDIN MOHAMED</div>
                                                </div>
                                                <button type="button" class="btn-copy" data-copy-text="MOSTAFA MAHMOD FAKHRELDIN MOHAMED" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ
                                                </button>
                                            </div>

                                            <!-- IBAN -->
                                            <div class="pm-info-row highlight-row" onclick="copyFromRow(this)" data-copy="SA2630100991106970328455" title="اضغط للنسخ">
                                                <div class="info-icon anb-icon">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">رقم الآيبان (IBAN)</div>
                                                    <div class="info-value monospace text-anb">SA2630100991106970328455</div>
                                                </div>
                                                <button type="button" class="btn-copy btn-copy-highlight" data-copy-text="SA2630100991106970328455" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ الآيبان
                                                </button>
                                            </div>

                                            <!-- Account Number -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="991106970328455" title="اضغط للنسخ">
                                                <div class="info-icon anb-icon">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">رقم الحساب (Account Number)</div>
                                                    <div class="info-value monospace">991106970328455</div>
                                                </div>
                                                <button type="button" class="btn-copy" data-copy-text="991106970328455" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ
                                                </button>
                                            </div>

                                            <!-- Amount to Transfer -->
                                            <div class="pm-info-row amount-row" onclick="copyFromRow(this)" data-copy="<?= esc(number_format((float) $course->course_price, 2, '.', '')) ?>" title="اضغط للنسخ">
                                                <div class="info-icon anb-icon">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">المبلغ المطلوب تحويله</div>
                                                    <div class="info-value"><span class="sar-amount-display"><?= esc(number_format((float) $course->course_price, 2)) ?></span> <?= riyal_icon('14px') ?></div>
                                                </div>
                                                <button type="button" class="btn-copy sar-copy-btn" data-copy-text="<?= esc(number_format((float) $course->course_price, 2, '.', '')) ?>" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ المبلغ
                                                </button>
                                            </div>

                                            <div class="pm-steps">
                                                <div class="pm-step">
                                                    <span class="step-num">1</span>
                                                    انسخ الآيبان أو الحساب
                                                </div>
                                                <div class="pm-step">
                                                    <span class="step-num">2</span>
                                                    حوّل المبلغ من بنكك
                                                </div>
                                                <div class="pm-step">
                                                    <span class="step-num">3</span>
                                                    ارفع صورة الإشعار
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- ═══════════ 2. STC BANK ═══════════ -->
                                    <label class="payment-method-card" id="card-stc" for="pm_stc_bank">
                                        <input type="radio" name="payment_method" value="stc_bank" id="pm_stc_bank">
                                        <div class="pm-header">
                                            <div class="pm-radio-circle"></div>
                                            <div class="pm-logo-wrap">
                                                <svg class="pm-logo" viewBox="0 0 130 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="130" height="42" rx="8" fill="#f7f1fc"/>
                                                    <text x="44" y="27" text-anchor="middle" fill="#4f008c" font-family="'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif" font-size="19" font-weight="900">stc</text>
                                                    <text x="88" y="27" text-anchor="middle" fill="#00c48c" font-family="'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif" font-size="16" font-weight="700">bank</text>
                                                </svg>
                                            </div>
                                            <div class="pm-title-area">
                                                <p class="pm-title">بنك إس تي سي - STC Bank</p>
                                                <p class="pm-subtitle">تحويل فوري مباشر (حساب / آيبان)</p>
                                            </div>
                                            <span class="pm-region-badge ksa">
                                                <i class="fas fa-university"></i> داخل السعودية
                                            </span>
                                        </div>

                                        <div class="pm-details">
                                            <div class="pm-alert-box">
                                                <i class="fas fa-info-circle"></i>
                                                <span>يمكنك التحويل من تطبيق STC Bank أو أي بنك سعودي (اضغط على أي حقل لنسخه مباشرة):</span>
                                            </div>

                                            <!-- Bank Name -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="STC Bank" title="اضغط للنسخ">
                                                <div class="info-icon stc-icon">
                                                    <i class="fas fa-landmark"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">اسم البنك</div>
                                                    <div class="info-value">STC Bank (بنك إس تي سي)</div>
                                                </div>
                                                <button type="button" class="btn-copy" data-copy-text="STC Bank" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ
                                                </button>
                                            </div>

                                            <!-- Beneficiary Name -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="مصطفى محمد" title="اضغط للنسخ">
                                                <div class="info-icon stc-icon">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">إسم العميل / المستفيد (Customer Name)</div>
                                                    <div class="info-value">مصطفى محمد</div>
                                                </div>
                                                <button type="button" class="btn-copy" data-copy-text="مصطفى محمد" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ
                                                </button>
                                            </div>

                                            <!-- IBAN -->
                                            <div class="pm-info-row highlight-row" onclick="copyFromRow(this)" data-copy="SA8178000000001261711229" title="اضغط للنسخ">
                                                <div class="info-icon stc-icon">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">رقم الحساب ايبان (Account IBAN)</div>
                                                    <div class="info-value monospace text-stc">SA8178000000001261711229</div>
                                                </div>
                                                <button type="button" class="btn-copy btn-copy-highlight" data-copy-text="SA8178000000001261711229" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ الآيبان
                                                </button>
                                            </div>

                                            <!-- Account Number -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="1261711229" title="اضغط للنسخ">
                                                <div class="info-icon stc-icon">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">رقم الحساب (Account Number)</div>
                                                    <div class="info-value monospace">1261711229</div>
                                                </div>
                                                <button type="button" class="btn-copy" data-copy-text="1261711229" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ
                                                </button>
                                            </div>

                                            <!-- Amount to Transfer -->
                                            <div class="pm-info-row amount-row" onclick="copyFromRow(this)" data-copy="<?= esc(number_format((float) $course->course_price, 2, '.', '')) ?>" title="اضغط للنسخ">
                                                <div class="info-icon stc-icon">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">المبلغ المطلوب تحويله</div>
                                                    <div class="info-value"><span class="sar-amount-display"><?= esc(number_format((float) $course->course_price, 2)) ?></span> <?= riyal_icon('14px') ?></div>
                                                </div>
                                                <button type="button" class="btn-copy sar-copy-btn" data-copy-text="<?= esc(number_format((float) $course->course_price, 2, '.', '')) ?>" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ المبلغ
                                                </button>
                                            </div>

                                            <div class="pm-steps">
                                                <div class="pm-step">
                                                    <span class="step-num">1</span>
                                                    انسخ الآيبان أو الحساب
                                                </div>
                                                <div class="pm-step">
                                                    <span class="step-num">2</span>
                                                    حوّل المبلغ من STC أو بنكك
                                                </div>
                                                <div class="pm-step">
                                                    <span class="step-num">3</span>
                                                    ارفع صورة الإشعار
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- ═══════════ 3. PAYPAL (International) ═══════════ -->
                                    <label class="payment-method-card" id="card-paypal" for="pm_paypal">
                                        <input type="radio" name="payment_method" value="paypal" id="pm_paypal">
                                        <div class="pm-header">
                                            <div class="pm-radio-circle"></div>
                                            <div class="pm-logo-wrap">
                                                <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-200px.png" 
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTMwIiBoZWlnaHQ9IjQyIiB2aWV3Qm94PSIwIDAgMTMwIDQyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxMzAiIGhlaWdodD0iNDIiIHJ4PSI4IiBmaWxsPSIjMDAzMDg3Ii8+PHRleHQgeD0iNjUiIHk9IjI3IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjZmZmIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTYiIGZvbnQtd2VpZ2h0PSJib2xkIj5QYXlQYWw8L3RleHQ+PC9zdmc+'" 
                                                     alt="PayPal" class="pm-logo">
                                            </div>
                                            <div class="pm-title-area">
                                                <p class="pm-title">باي بال - PayPal</p>
                                                <p class="pm-subtitle">الدفع الدولي والبطاقات الائتمانية</p>
                                            </div>
                                            <span class="pm-region-badge international">
                                                <i class="fab fa-paypal"></i> دولي
                                            </span>
                                        </div>

                                        <div class="pm-details">
                                            <!-- PayPal Link -->
                                            <div class="pm-info-row" onclick="copyFromRow(this)" data-copy="https://paypal.me/webeasystep/<?= esc(number_format((float) $course->course_price / 3.75, 2, '.', '')) ?>" title="اضغط للنسخ">
                                                <div class="info-icon paypal-icon">
                                                    <i class="fab fa-paypal"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">رابط الدفع المباشر عبر PayPal</div>
                                                    <div class="info-value monospace" id="paypalLinkDisplay">paypal.me/webeasystep/<?= esc(number_format((float) $course->course_price / 3.75, 2, '.', '')) ?></div>
                                                </div>
                                                <button type="button" class="btn-copy" id="paypalCopyBtn" data-copy-text="https://paypal.me/webeasystep/<?= esc(number_format((float) $course->course_price / 3.75, 2, '.', '')) ?>" onclick="event.stopPropagation(); copyToClipboard(this.dataset.copyText, this)">
                                                    <i class="fas fa-copy"></i> نسخ الرابط
                                                </button>
                                            </div>

                                            <!-- Pay Now Button -->
                                            <div class="text-center mb-3">
                                                <a href="https://paypal.me/webeasystep/<?= esc(number_format((float) $course->course_price / 3.75, 2, '.', '')) ?>" target="_blank" class="btn-pay-link btn-paypal-pay" id="paypalPayBtn">
                                                    <i class="fab fa-paypal"></i>
                                                    <span id="paypalPayBtnText">ادفع $<?= esc(number_format((float) $course->course_price / 3.75, 2)) ?> عبر PayPal</span>
                                                    <i class="fas fa-external-link-alt" style="font-size: 0.75em;"></i>
                                                </a>
                                            </div>

                                            <div class="pm-steps">
                                                <div class="pm-step">
                                                    <span class="step-num">1</span>
                                                    اضغط ادفع الآن
                                                </div>
                                                <div class="pm-step">
                                                    <span class="step-num">2</span>
                                                    أكمل الدفع بحسابك أو بطاقتك
                                                </div>
                                                <div class="pm-step">
                                                    <span class="step-num">3</span>
                                                    ارفع صورة التأكيد
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                </div>

                                <!-- ═══════════ UPLOAD INSTRUCTIONS ═══════════ -->
                                <div class="upload-instructions visible" id="uploadInstructions">
                                    <h6>
                                        <i class="fas fa-info-circle"></i>
                                        إرشادات مهمة بعد إتمام التحويل
                                    </h6>
                                    <ol>
                                        <li>قم بتحويل المبلغ باستخدام وسيلة الدفع المختارة أعلاه</li>
                                        <li>التقط <strong>صورة (إشعار التحويل / سكرين شوت)</strong> توضح رقم المعاملة والمبلغ</li>
                                        <li>ارفع الصورة في المكان المخصص أدناه</li>
                                        <li>اضغط على زر <strong>"إرسال طلب الاشتراك"</strong></li>
                                        <li>سيتم مراجعة طلبك وتفعيل اشتراكك في أقرب وقت</li>
                                    </ol>
                                </div>

                                <!-- ═══════════ FILE UPLOAD AREA ═══════════ -->
                                <div class="upload-area visible" id="uploadArea">
                                    <input type="file" name="payment_proof" id="paymentProofInput" accept="image/*" style="display:none;">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">اضغط هنا أو اسحب صورة إثبات الدفع (إشعار التحويل)</div>
                                    <div class="upload-hint">PNG, JPG, JPEG — الحد الأقصى 5MB</div>
                                </div>

                                <!-- Preview -->
                                <div class="upload-preview" id="uploadPreview">
                                    <div class="preview-container">
                                        <img id="previewImage" src="" alt="معاينة صورة الدفع">
                                        <button type="button" class="btn-remove-preview" id="removePreview" title="حذف الصورة">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn-submit-checkout visible" id="submitBtn" disabled>
                                    <i class="fas fa-paper-plane"></i>
                                    إرسال طلب الاشتراك
                                </button>

                            </form>
                        <?php endif; ?>

                        <!-- Trust badges -->
                        <div class="trust-badges">
                            <span class="trust-badge">
                                <i class="fas fa-shield-alt"></i> دفع آمن وموثوق
                            </span>
                            <span class="trust-badge">
                                <i class="fas fa-infinity"></i> وصول فوري للمقرر
                            </span>
                            <span class="trust-badge">
                                <i class="fas fa-headset"></i> دعم فني ومساعدة متواصلة
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    const baseAmount = <?= json_encode(number_format((float) ($course->course_price ?? 0), 2, '.', '')) ?>;
    let currentAmount = parseFloat(baseAmount);
    let isFullyDiscounted = false;

    // ── Payment method selection ──
    const cards = document.querySelectorAll('.payment-method-card');
    const radios = form.querySelectorAll('input[name="payment_method"][type="radio"]');
    const uploadInstructions = document.getElementById('uploadInstructions');
    const uploadArea = document.getElementById('uploadArea');
    const uploadPreview = document.getElementById('uploadPreview');
    const submitBtn = document.getElementById('submitBtn');
    const fileInput = document.getElementById('paymentProofInput');
    const previewImage = document.getElementById('previewImage');
    const removeBtn = document.getElementById('removePreview');
    const couponCodeInput = document.getElementById('couponCodeInput');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponFeedback = document.getElementById('couponFeedback');
    const discountRow = document.getElementById('discountRow');
    const discountAmountText = document.getElementById('discountAmountText');
    const finalAmountDisplay = document.getElementById('finalAmountDisplay');
    const usdEquivalent = document.getElementById('usdEquivalent');
    const finalAmountText = document.getElementById('finalAmountText');
    const originalAmountText = document.getElementById('originalAmountText');
    const fullyDiscountedNote = document.getElementById('fullyDiscountedNote');
    const paypalLinkDisplay = document.getElementById('paypalLinkDisplay');
    const paypalCopyBtn = document.getElementById('paypalCopyBtn');
    const paypalPayBtn = document.getElementById('paypalPayBtn');
    const paypalPayBtnText = document.getElementById('paypalPayBtnText');
    const csrfInput = form.querySelector('input[name="<?= csrf_token() ?>"]');

    const RIYAL_ICON_HTML = '<?= addslashes(riyal_icon("14px")) ?>';

    function formatSar(value) {
        return Number(value).toFixed(2) + ' ' + RIYAL_ICON_HTML;
    }

    function formatUsd(value) {
        return '$' + (Number(value) / 3.75).toFixed(2);
    }

    function updateDynamicAmounts(amount) {
        const sarValue = Number(amount).toFixed(2);
        const usdValue = (Number(amount) / 3.75).toFixed(2);
        const paypalUrl = 'https://paypal.me/webeasystep/' + usdValue;

        // Update all SAR displays in bank cards
        document.querySelectorAll('.sar-amount-display').forEach(el => {
            el.textContent = sarValue;
        });

        // Update all SAR copy buttons
        document.querySelectorAll('.sar-copy-btn').forEach(btn => {
            btn.dataset.copyText = sarValue;
            const parentRow = btn.closest('.pm-info-row');
            if (parentRow) {
                parentRow.setAttribute('data-copy', sarValue);
            }
        });

        // Update PayPal displays
        if (paypalLinkDisplay) {
            paypalLinkDisplay.textContent = 'paypal.me/webeasystep/' + usdValue;
            const paypalRow = paypalLinkDisplay.closest('.pm-info-row');
            if (paypalRow) {
                paypalRow.setAttribute('data-copy', paypalUrl);
            }
        }
        if (paypalCopyBtn) {
            paypalCopyBtn.dataset.copyText = paypalUrl;
        }
        if (paypalPayBtn) {
            paypalPayBtn.href = paypalUrl;
        }
        if (paypalPayBtnText) {
            paypalPayBtnText.textContent = 'ادفع $' + usdValue + ' عبر PayPal';
        }
        if (usdEquivalent) {
            usdEquivalent.textContent = '($' + usdValue + ' USD)';
        }
    }

    function showCouponFeedback(message, type) {
        if (!couponFeedback) return;
        couponFeedback.textContent = message;
        couponFeedback.classList.remove('success', 'error');
        couponFeedback.classList.add('visible', type);
    }

    function resetCouponPricing() {
        currentAmount = parseFloat(baseAmount);
        isFullyDiscounted = false;

        if (discountRow) discountRow.classList.remove('visible');
        if (discountAmountText) discountAmountText.innerHTML = '-0.00 ' + RIYAL_ICON_HTML;
        if (originalAmountText) originalAmountText.innerHTML = formatSar(baseAmount);
        if (finalAmountDisplay) finalAmountDisplay.innerHTML = formatSar(baseAmount);
        if (finalAmountText) finalAmountText.innerHTML = formatSar(baseAmount);
        if (fullyDiscountedNote) fullyDiscountedNote.classList.remove('visible');
        updateDynamicAmounts(baseAmount);
    }

    function applyPricingState(finalAmount, discountAmount) {
        currentAmount = Number(finalAmount);
        isFullyDiscounted = currentAmount <= 0;

        if (discountRow) {
            if (Number(discountAmount) > 0) {
                discountRow.classList.add('visible');
                discountAmountText.innerHTML = '-' + Number(discountAmount).toFixed(2) + ' ' + RIYAL_ICON_HTML;
            } else {
                discountRow.classList.remove('visible');
            }
        }

        if (finalAmountDisplay) finalAmountDisplay.innerHTML = formatSar(currentAmount);
        if (finalAmountText) finalAmountText.innerHTML = formatSar(currentAmount);
        updateDynamicAmounts(currentAmount);

        if (isFullyDiscounted) {
            if (fullyDiscountedNote) fullyDiscountedNote.classList.add('visible');
            if (uploadInstructions) uploadInstructions.classList.remove('visible');
            if (uploadArea) uploadArea.classList.remove('visible');
            if (uploadPreview) uploadPreview.classList.remove('visible');
            if (submitBtn) {
                submitBtn.classList.add('visible');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> تفعيل المقرر الآن';
            }
        } else if (submitBtn) {
            if (fullyDiscountedNote) fullyDiscountedNote.classList.remove('visible');
            if (uploadInstructions) uploadInstructions.classList.add('visible');
            if (uploadArea && (!uploadPreview || !uploadPreview.classList.contains('visible'))) {
                uploadArea.classList.add('visible');
            }
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال طلب الاشتراك';
        }

        updateSubmitState();
    }

    // Handle card click / radio change
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            cards.forEach(c => c.classList.remove('selected'));
            this.closest('.payment-method-card').classList.add('selected');
            if (!isFullyDiscounted) {
                if (uploadInstructions) uploadInstructions.classList.add('visible');
                if (uploadArea && (!uploadPreview || !uploadPreview.classList.contains('visible'))) {
                    uploadArea.classList.add('visible');
                }
            }
            if (submitBtn) submitBtn.classList.add('visible');
            updateSubmitState();
        });
    });

    if (couponCodeInput) {
        couponCodeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            if (!this.value.trim()) {
                resetCouponPricing();
                if (couponFeedback) {
                    couponFeedback.textContent = '';
                    couponFeedback.classList.remove('visible', 'success', 'error');
                }
            }
        });
    }

    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', async function() {
            const couponCode = couponCodeInput ? couponCodeInput.value.trim() : '';

            if (!couponCode) {
                showCouponFeedback('يرجى إدخال كود الكوبون أولاً.', 'error');
                resetCouponPricing();
                return;
            }

            applyCouponBtn.disabled = true;
            const originalText = applyCouponBtn.textContent;
            applyCouponBtn.textContent = 'جارٍ التحقق...';

            try {
                const formData = new FormData();
                formData.append('course_id', form.querySelector('input[name="course_id"]').value);
                formData.append('coupon_code', couponCode);
                if (csrfInput) {
                    formData.append(csrfInput.name, csrfInput.value);
                }

                const response = await fetch('<?= site_url('enrollments/validate-coupon') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (csrfInput && data.csrf_hash) {
                    csrfInput.value = data.csrf_hash;
                }

                if (!data.success) {
                    resetCouponPricing();
                    showCouponFeedback(data.message || 'تعذر التحقق من الكوبون.', 'error');
                    return;
                }

                if (couponCodeInput) {
                    couponCodeInput.value = data.coupon_code || couponCode;
                }

                applyPricingState(data.final_amount, data.discount_amount);
                showCouponFeedback(
                    `${data.message} - تم خصم ${formatSar(data.discount_amount)} وأصبح المبلغ ${formatSar(data.final_amount)}.`,
                    'success'
                );
            } catch (error) {
                resetCouponPricing();
                showCouponFeedback('حدث خطأ أثناء التحقق من الكوبون. حاول مرة أخرى.', 'error');
            } finally {
                applyCouponBtn.disabled = false;
                applyCouponBtn.textContent = originalText;
            }
        });
    }

    // ── File Upload Logic ──
    if (uploadArea && fileInput) {
        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelect(this.files[0]);
            }
        });
    }

    function handleFileSelect(file) {
        if (file.size > 5 * 1024 * 1024) {
            alert('حجم الملف كبير جداً. الحد الأقصى 5MB');
            fileInput.value = '';
            return;
        }
        if (!file.type.startsWith('image/')) {
            alert('يرجى اختيار ملف صورة فقط');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            uploadPreview.classList.add('visible');
            uploadArea.classList.remove('visible');
            updateSubmitState();
        };
        reader.readAsDataURL(file);
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileInput.value = '';
            previewImage.src = '';
            uploadPreview.classList.remove('visible');
            uploadArea.classList.add('visible');
            updateSubmitState();
        });
    }

    function updateSubmitState() {
        const methodSelected = form.querySelector('input[name="payment_method"]:checked');
        const hasFile = fileInput && fileInput.files.length > 0;
        if (submitBtn) {
            submitBtn.disabled = isFullyDiscounted ? false : !(methodSelected && hasFile);
        }
    }

    form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]:not(:disabled)');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
            btn.disabled = true;
        }
    });

    resetCouponPricing();
});

// ── Robust Copy to clipboard ──
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
    btn.innerHTML = '<i class="fas fa-check"></i> تم النسخ!';
    btn.classList.add('copied');
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('copied');
    }, 2000);
}
</script>
<?= $this->endSection(); ?>
