<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open(); ?>

            <div class="form-group row">
                <label for="course_id" class="col-sm-3 col-form-label"><?= lang('Coupons.course') ?></label>
                <div class="col-sm-9">
                    <select class="form-control" id="course_id" name="course_id">
                        <option value=""><?= lang('Coupons.all_courses') ?></option>
                        <?php foreach (($courses ?? []) as $courseOption): ?>
                            <option value="<?= esc($courseOption->id) ?>" <?= set_value('course_id', $coupon->course_id ?? '') == $courseOption->id ? 'selected' : '' ?>>
                                <?= esc($courseOption->course_title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted"><?= lang('Coupons.course_help') ?></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="coupon_code" class="col-sm-3 col-form-label"><?= lang('Coupons.coupon_code') ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="<?= set_value('coupon_code', $coupon->coupon_code ?? '') ?>" pattern="[A-Za-z0-9]+" maxlength="50" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang('Coupons.discount_type') ?></label>
                <div class="col-sm-9 pt-2">
                    <?php
                    $currentType = set_value('discount_type', $coupon->discount_type ?? 'percentage');
                    ?>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="type_percentage" name="discount_type" value="percentage" <?= $currentType === 'percentage' ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="type_percentage"><?= lang('Coupons.discount_type_percentage') ?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="type_fixed" name="discount_type" value="fixed" <?= $currentType === 'fixed' ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="type_fixed"><?= lang('Coupons.discount_type_fixed') ?></label>
                    </div>
                </div>
            </div>

            <div class="form-group row" id="row_discount_percentage">
                <label for="discount_percentage" class="col-sm-3 col-form-label"><?= lang('Coupons.discount_percentage') ?></label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="<?= set_value('discount_percentage', $coupon->discount_percentage ?? '10') ?>" min="1" max="100" step="1">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row" id="row_discount_value">
                <label for="discount_value" class="col-sm-3 col-form-label"><?= lang('Coupons.discount_value') ?></label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="discount_value" name="discount_value" value="<?= set_value('discount_value', $coupon->discount_value ?? '0') ?>" min="1" step="1">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="end_date" class="col-sm-3 col-form-label"><?= lang('Coupons.end_date') ?></label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= set_value('end_date', $coupon->end_date ?? $business_date) ?>" min="<?= esc($business_date) ?>" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="usage_limit" class="col-sm-3 col-form-label"><?= lang('Coupons.usage_limit') ?></label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="usage_limit" name="usage_limit" value="<?= set_value('usage_limit', $coupon->usage_limit ?? '1') ?>" min="1" step="1" required>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="usage_limit_per_account" class="col-sm-3 col-form-label"><?= lang('Coupons.usage_limit_per_account') ?></label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="usage_limit_per_account" name="usage_limit_per_account" value="<?= set_value('usage_limit_per_account', $coupon->usage_limit_per_account ?? '1') ?>" min="0" step="1" placeholder="0">
                    <small class="text-muted"><?= lang('Coupons.customer_limit_help') ?></small>
                    <small id="per_account_error" class="text-danger d-none"></small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang('Coupons.active') ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" <?= set_value('active', $coupon->active ?? 1) == 1 ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="active"><?= lang('Coupons.active') ?></label>
                    </div>
                </div>
            </div>

            <a type="button" class="btn btn-secondary" href="<?= ADMIN_URL . 'coupons' ?>"><?= lang('Admin.cancel') ?></a>
            <button type="submit" class="btn btn-primary"><?= lang('Admin.save') ?></button>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script>
    $(function () {
        // Enforce alphanumeric uppercase on coupon code input
        $('#coupon_code').on('input', function () {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        });

        // Toggle discount fields based on selected type
        function toggleDiscountFields() {
            var type = $('input[name="discount_type"]:checked').val();
            if (type === 'fixed') {
                $('#row_discount_percentage').hide();
                $('#row_discount_value').show();
                $('#discount_percentage').removeAttr('required').prop('disabled', true);
                $('#discount_value').attr('required', true).prop('disabled', false);
            } else {
                $('#row_discount_value').hide();
                $('#row_discount_percentage').show();
                $('#discount_value').removeAttr('required').prop('disabled', true);
                $('#discount_percentage').attr('required', true).prop('disabled', false);
            }
        }

        $('input[name="discount_type"]').on('change', toggleDiscountFields);
        toggleDiscountFields(); // run on page load

        // Cross-field hint: usage_limit_per_account must not exceed usage_limit
        function validatePerAccountLimit() {
            var total      = parseInt($('#usage_limit').val(), 10) || 0;
            var perAccount = parseInt($('#usage_limit_per_account').val(), 10) || 0;
            var $error     = $('#per_account_error');

            if (perAccount > 0 && perAccount > total) {
                $error.text('<?= lang('Coupons.usage_limit_per_account_exceeds_total') ?>').removeClass('d-none');
                $('#usage_limit_per_account').addClass('is-invalid');
            } else {
                $error.addClass('d-none');
                $('#usage_limit_per_account').removeClass('is-invalid');
            }
        }

        $('#usage_limit, #usage_limit_per_account').on('input', validatePerAccountLimit);
        validatePerAccountLimit(); // run on page load (edit mode)
    });
</script>
<?= $this->endSection(); ?>
