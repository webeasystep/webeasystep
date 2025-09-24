<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .payment-section {
        padding: 60px 0;
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .selected-units-block {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .unit-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    .unit-name {
        font-weight: 600;
        color: #343a40;
        flex: 1;
    }
    .unit-price {
        font-weight: 700;
        color: #ec661f;
        font-size: 1.1rem;
    }
    .total-section {
        background: #e3f2fd;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin: 20px 0;
    }
    .total-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1976d2;
    }
    .payment-method-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
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
    .payment-method-card input[type="radio"] {
        margin-left: 10px;
    }
    .payment-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
    .bank-details {
        background: #e8f5e8;
        border: 1px solid #c3e6c3;
        border-radius: 6px;
        padding: 15px;
        margin: 15px 0;
    }
    .btn-purchase {
        background: linear-gradient(135deg, #ec661f 0%, #d4541a 100%);
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        color: white;
        width: 100%;
        margin-top: 20px;
    }
    .btn-purchase:hover {
        background: linear-gradient(135deg, #d4541a 0%, #c04717 100%);
        color: white;
    }
.whatsapp-contact-section {
                margin: 20px 0;
            }

            .whatsapp-icon svg {
                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            }

            .alert-info {
                background-color: #e8f5e8;
                border-color: #25D366;
                color: #155724;
            }

            .btn-success {
                background-color: #25D366;
                border-color: #25D366;
                transition: all 0.3s ease;
            }

            .btn-success:hover {
                background-color: #128C7E;
                border-color: #128C7E;
                transform: translateY(-1px);
            }
        </style>

<section class="payment-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">إتمام شراء الوحدات الدراسية</h4>
                    </div>
                    <div class="card-body">

                        <!-- Selected Units Display -->
                        <div class="selected-units-block">
                            <h5 class="mb-3">الوحدات المحددة:</h5>
                            <?php if (!empty($selected_units)): ?>
                                <?php foreach ($selected_units as $unit): ?>
                                    <div class="unit-item">
                                        <div class="unit-name"><?= esc($unit->unit_name) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Payment Form -->
                        <form action="<?= site_url('enrollments/purchase-units') ?>" method="post" enctype="multipart/form-data" id="purchaseForm">
                            <?= csrf_field() ?>

                            <!-- Hidden unit IDs -->
                            <?php if (!empty($unit_ids)): ?>
                                <?php foreach ($unit_ids as $unitId): ?>
                                    <input type="hidden" name="unit_ids[]" value="<?= esc($unitId) ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <h5 class="mb-3">طريقة الدفع:</h5>

                                <div class="payment-method-card" onclick="selectPaymentMethod('vodafone_cash')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment_method" value="vodafone_cash" id="vodafone_cash" checked>
                                        <label for="vodafone_cash" class="mb-0 ms-2">
                                            <strong>فودافون كاش</strong>
                                        </label>
                                    </div>
                                </div>

                                <div class="payment-method-card" onclick="selectPaymentMethod('instapay')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment_method" value="instapay" id="instapay">
                                        <label for="instapay" class="mb-0 ms-2">
                                            <strong>انستاباي</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp Contact Section -->
                            <div class="whatsapp-contact-section">
                                <div class="alert alert-info d-flex align-items-center">
                                    <div class="whatsapp-icon me-3">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.386" fill="#25D366"/>
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">تأكيد عملية الشراء</h6>
                                        <p class="mb-2">للمتابعة وتأكيد عملية التحويل، يرجى التواصل معنا عبر واتساب:</p>
                                        <a href="https://wa.me/201032863861" class="btn btn-success btn-sm" target="_blank">
                                            <i class="fab fa-whatsapp me-2"></i>
                                            تواصل عبر واتساب: 201032863861
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="payment-info">
                                <h5 class="mb-3">معلومات الدفع:</h5>

                                <div id="vodafone_cash_info" class="payment-details" style="display: none;">
                                    <div class="bank-details">
                                        <h6>بيانات فودافون كاش:</h6>
                                        <p><strong>رقم المحفظة:</strong> 01032863861</p>
                                        <p><strong>اسم صاحب المحفظة:</strong>احمد **م**ف**ال</p>
                                    </div>
                                </div>

                                <div id="instapay_info" class="payment-details" style="display: none;">
                                    <div class="bank-details">
                                        <h6>بيانات انستاباي:</h6>
                                        <p><strong>الحساب:</strong> fakhr@instapay</p>
                                        <p><strong> الاسم:</strong> احمد **م**ف**ال</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Proof Upload -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <strong>إرفاق إثبات الدفع: <span class="text-danger">*</span></strong>
                                </label>
                                <div class="fireupload" id="dropzone1"></div>
                                <div class="form-text">يرجى إرفاق صورة أو ملف PDF لإثبات عملية الدفع</div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-purchase">
                                إتمام عملية الشراء
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>
<?= $this->endSection(); ?>
<!-- Script -->
<!-- .javascript section -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    // Initialize FireUploader
    var uploader1 = new FireUploader({
        dropzoneId: 'dropzone1',
        inputName: "payment_proof[]",
        multipleFiles: false,
        allowedExtensions: ["jpg", "jpeg", "png", "pdf"],
        files: <?= json_encode($files ?? []) ?>
    });

    function selectPaymentMethod(method) {
        // Update radio button
        document.getElementById(method).checked = true;

        // Update visual selection
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');

        // Show/hide payment details
        document.querySelectorAll('.payment-details').forEach(detail => {
            detail.style.display = 'none';
        });
        document.getElementById(method + '_info').style.display = 'block';
    }

    // Form validation
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = 'جاري المعالجة...';
        submitBtn.disabled = true;
    });

    // Initialize default selection
    document.addEventListener('DOMContentLoaded', function() {
        selectPaymentMethod('vodafone_cash');
    });
</script>


<?= $this->endSection(); ?>
