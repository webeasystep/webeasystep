<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

            <!-- Switch for 'user_id' -->
            <div class="form-group row">
                <label for="user_id" class="col-sm-3 col-form-label"><?= lang("Enrollments.user_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('user_id', $users, set_value('user_id', $enrollment->user_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Multiple Units Selection -->
            <div class="form-group row">
                <label for="unit_ids" class="col-sm-3 col-form-label"><?= lang("Enrollments.units") ?></label>
                <div class="col-sm-9">
                    <select name="unit_ids[]" id="unit_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                        <?php if (isset($units) && is_array($units)): ?>
                            <?php foreach ($units as $unitId => $unitName): ?>
                                <option value="<?= $unitId ?>" 
                                    <?= (isset($selected_units) && in_array($unitId, $selected_units)) ? 'selected' : '' ?>>
                                    <?= esc($unitName) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="total_amount" class="col-sm-3 col-form-label"><?= lang("Enrollments.total_amount") ?></label>
                <div class="col-sm-9">
                    <input type="number" step="0.01" name="total_amount" value="<?= set_value('total_amount', $enrollment->total_amount ?? "") ?>" id="total_amount" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_method" class="col-sm-3 col-form-label"><?= lang("Enrollments.payment_method") ?></label>
                <div class="col-sm-9">
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="bank_transfer" <?= set_select('payment_method', 'bank_transfer', ($enrollment->payment_method ?? 'bank_transfer') == 'bank_transfer') ?>>Bank Transfer</option>
                        <option value="credit_card" <?= set_select('payment_method', 'credit_card', ($enrollment->payment_method ?? "") == 'credit_card') ?>>Credit Card</option>
                        <option value="paypal" <?= set_select('payment_method', 'paypal', ($enrollment->payment_method ?? "") == 'paypal') ?>>PayPal</option>
                        <option value="cash" <?= set_select('payment_method', 'cash', ($enrollment->payment_method ?? "") == 'cash') ?>>Cash</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_proof" class="col-sm-3 col-form-label"><?= lang("Enrollments.payment_proof") ?></label>
                <div class="col-sm-9">
                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*,.pdf">
                    <?php if (isset($enrollment->payment_proof) && !empty($enrollment->payment_proof)): ?>
                        <small class="form-text text-muted">
                            Current file: <a href="<?= base_url('uploads/' . $enrollment->payment_proof) ?>" target="_blank">View</a>
                        </small>
                    <?php endif; ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="status" class="col-sm-3 col-form-label"><?= lang("Enrollments.status") ?></label>
                <div class="col-sm-9">
                    <select name="status" id="status" class="form-control">
                        <option value="pending" <?= set_select('status', 'pending', ($enrollment->status ?? 'pending') == 'pending') ?>>Pending</option>
                        <option value="approved" <?= set_select('status', 'approved', ($enrollment->status ?? "") == 'approved') ?>>Approved</option>
                        <option value="rejected" <?= set_select('status', 'rejected', ($enrollment->status ?? "") == 'rejected') ?>>Rejected</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="admin_notes" class="col-sm-3 col-form-label"><?= lang("Enrollments.admin_notes") ?></label>
                <div class="col-sm-9">
                    <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3"><?= set_value('admin_notes', $enrollment->admin_notes ?? "") ?></textarea>
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
<?php $this->endSection(); ?>
