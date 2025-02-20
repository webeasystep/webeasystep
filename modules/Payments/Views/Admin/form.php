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
                <label for="user_id" class="col-sm-3 col-form-label"><?= lang("Payments.user_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('user_id', $users, set_value('user_id', $payment->user_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Switch for 'course_id' -->
            <div class="form-group row">
                <label for="course_id" class="col-sm-3 col-form-label"><?= lang("Payments.course_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('course_id', $courses, set_value('course_id', $payment->course_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="amount" class="col-sm-3 col-form-label"><?= lang("Payments.amount") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="amount" value="<?= set_value('amount', $payment->amount ?? "") ?>" id="amount" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_method" class="col-sm-3 col-form-label"><?= lang("Payments.payment_method") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_method" value="<?= set_value('payment_method', $payment->payment_method ?? "") ?>" id="payment_method" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_status" class="col-sm-3 col-form-label"><?= lang("Payments.payment_status") ?></label>
                <div class="col-sm-9">
                    <select name="payment_status" id="payment_status" class="form-control">
                        <option value="pending" <?= set_select('payment_status', 'pending', ($payment->payment_status ?? "") == 'pending') ?>>Pending</option>
                        <option value="completed" <?= set_select('payment_status', 'completed', ($payment->payment_status ?? "") == 'completed') ?>>Completed</option>
                        <option value="failed" <?= set_select('payment_status', 'failed', ($payment->payment_status ?? "") == 'failed') ?>>Failed</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'payments' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<?php $this->endSection(); ?>
