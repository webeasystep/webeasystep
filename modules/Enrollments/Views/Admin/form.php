<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <!-- Hidden coupon fields -->
            <input type="hidden" name="coupon_id" id="coupon_id" value="<?= set_value('coupon_id', $enrollment->coupon_id ?? '') ?>">
            <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="<?= set_value('coupon_code', $enrollment->coupon_code ?? '') ?>">
            <input type="hidden" name="coupon_discount_amount" id="coupon_discount_amount" value="<?= set_value('coupon_discount_amount', $enrollment->coupon_discount_amount ?? '') ?>">

            <!-- Switch for 'user_id' -->
            <div class="form-group row">
                <label for="user_id" class="col-sm-3 col-form-label"><?= lang("Enrollments.user_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('user_id', $users, set_value('user_id', $enrollment->user_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Course Selection -->
            <div class="form-group row">
                <label for="course_id" class="col-sm-3 col-form-label"><?= lang("Enrollments.course_title") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('course_id', $courses, set_value('course_id', $enrollment->course_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id' => 'course_id']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="paid_amount" class="col-sm-3 col-form-label"><?= lang("Enrollments.paid_amount") ?></label>
                <div class="col-sm-9">
                    <input type="number" step="0.01" name="paid_amount" value="<?= set_value('paid_amount', $enrollment->paid_amount ?? "") ?>" id="paid_amount" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Coupon Code Section -->
            <div class="form-group row">
                <label for="coupon_code_input" class="col-sm-3 col-form-label"><?= lang("Enrollments.coupon_code") ?></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" id="coupon_code_input" class="form-control" placeholder="أدخل كود الكوبون..." value="<?= esc($enrollment->coupon_code ?? '') ?>">
                        <div class="input-group-append">
                            <button type="button" id="btn_apply_coupon" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> تطبيق
                            </button>
                            <button type="button" id="btn_remove_coupon" class="btn btn-danger" style="display:none;">
                                <i class="fas fa-times-circle"></i> إزالة
                            </button>
                        </div>
                    </div>
                    <div id="coupon_feedback" class="mt-2"></div>
                    <?php if (!empty($enrollment->coupon_code)): ?>
                        <small class="text-info mt-1 d-block">
                            <i class="fas fa-tag"></i>
                            كوبون مطبق: <strong><?= esc($enrollment->coupon_code) ?></strong>
                            <?php if (!empty($enrollment->coupon_discount_amount)): ?>
                                — خصم: <strong><?= number_format($enrollment->coupon_discount_amount, 2) ?></strong>
                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_method" class="col-sm-3 col-form-label"><?= lang("Enrollments.payment_method") ?></label>
                <div class="col-sm-9">
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="instapay" <?= set_select('payment_method', 'instapay', ($enrollment->payment_method ?? 'instapay') == 'instapay') ?>>انستاباي</option>
                        <option value="vodafone_cash" <?= set_select('payment_method', 'vodafone_cash', ($enrollment->payment_method ?? "") == 'vodafone_cash') ?>>فودافون كاش</option>
                        <option value="usdt" <?= set_select('payment_method', 'usdt', ($enrollment->payment_method ?? "") == 'usdt') ?>>USDT</option>
                        <option value="paypal" <?= set_select('payment_method', 'paypal', ($enrollment->payment_method ?? "") == 'paypal') ?>>باي بال (PayPal)</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_proof" class="col-sm-3 col-form-label"><?= lang("Enrollments.payment_proof") ?></label>
                <div class="col-sm-9">
                    <?php if (!empty($enrollment->payment_proof)): ?>
                        <div class="mb-2">
                            <a href="<?= base_url($enrollment->payment_proof) ?>" target="_blank" class="btn btn-info">
                                <i class="fas fa-image"></i> عرض الإثبات
                            </a>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-secondary mt-2">لا يوجد إثبات</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="refund_proof" class="col-sm-3 col-form-label"><?= lang("Enrollments.refund_proof") ?></label>
                <div class="col-sm-9">
                    <?php if (!empty($enrollment->refund_proof ?? null)): ?>
                        <div class="mb-2">
                            <a href="<?= base_url($enrollment->refund_proof) ?>" target="_blank" class="btn btn-warning">
                                <i class="fas fa-image"></i> عرض إثبات الاسترجاع
                            </a>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-secondary mt-2">لا يوجد إثبات</span>
                    <?php endif; ?>
                    <input type="file" name="refund_proof" id="refund_proof" class="form-control mt-2" accept="image/*">
                    <small class="form-text text-muted"><?= lang("Enrollments.attach_refund_proof") ?></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="status" class="col-sm-3 col-form-label"><?= lang("Enrollments.status") ?></label>
                <div class="col-sm-9">
                    <select name="status" id="status" class="form-control">
                        <option value="pending" <?= set_select('status', 'pending', ($enrollment->status ?? 'pending') == 'pending') ?>>Pending</option>
                        <option value="approved" <?= set_select('status', 'approved', ($enrollment->status ?? "") == 'approved') ?>>Approved</option>
                        <option value="rejected" <?= set_select('status', 'rejected', ($enrollment->status ?? "") == 'rejected') ?>>Rejected</option>
                        <option value="refunded" <?= set_select('status', 'refunded', ($enrollment->status ?? "") == 'refunded') ?>>Refunded</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="notes" class="col-sm-3 col-form-label"><?= lang("Enrollments.admin_notes") ?></label>
                <div class="col-sm-9">
                    <textarea name="notes" id="notes" class="form-control" rows="3"><?= set_value('notes', $enrollment->notes ?? "") ?></textarea>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'enrollments' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
$(document).ready(function() {
    var couponApplied = <?= !empty($enrollment->coupon_id ?? null) ? 'true' : 'false' ?>;

    // Show remove button if coupon already applied
    if (couponApplied) {
        $('#btn_apply_coupon').hide();
        $('#btn_remove_coupon').show();
        $('#coupon_code_input').prop('readonly', true);
    }

    // Apply Coupon
    $('#btn_apply_coupon').on('click', function() {
        var couponCode = $('#coupon_code_input').val().trim();
        var courseId = $('#course_id').val();

        if (!couponCode) {
            showCouponFeedback('يرجى إدخال كود الكوبون.', 'danger');
            return;
        }
        if (!courseId) {
            showCouponFeedback('يرجى اختيار الدورة أولاً.', 'danger');
            return;
        }

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري التحقق...');

        $.ajax({
            url: '<?= ADMIN_URL ?>enrollments/validate-coupon',
            type: 'POST',
            data: {
                coupon_code: couponCode,
                course_id: courseId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.valid) {
                    // Set hidden fields
                    $('#coupon_id').val(response.coupon_id);
                    $('#coupon_code_hidden').val(response.coupon_code);
                    $('#coupon_discount_amount').val(response.discount_amount);
                    $('#paid_amount').val(response.final_price);

                    showCouponFeedback(response.message, 'success');

                    // Toggle buttons
                    btn.hide();
                    $('#btn_remove_coupon').show();
                    $('#coupon_code_input').prop('readonly', true);
                    couponApplied = true;
                } else {
                    showCouponFeedback(response.message, 'danger');
                }
            },
            error: function() {
                showCouponFeedback('حدث خطأ أثناء التحقق من الكوبون.', 'danger');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> تطبيق');
            }
        });
    });

    // Remove Coupon
    $('#btn_remove_coupon').on('click', function() {
        $('#coupon_id').val('');
        $('#coupon_code_hidden').val('');
        $('#coupon_discount_amount').val('');
        $('#coupon_code_input').val('').prop('readonly', false);
        $('#paid_amount').val('');

        $(this).hide();
        $('#btn_apply_coupon').show();
        $('#coupon_feedback').html('');
        couponApplied = false;
    });

    // Reset coupon when course changes
    $('#course_id').on('change', function() {
        if (couponApplied) {
            $('#btn_remove_coupon').trigger('click');
        }
    });

    function showCouponFeedback(message, type) {
        $('#coupon_feedback').html(
            '<div class="alert alert-' + type + ' py-1 px-2 mb-0 small">' +
            '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-1"></i> ' +
            message + '</div>'
        );
    }
});
</script>
<?= $this->endSection(); ?>

