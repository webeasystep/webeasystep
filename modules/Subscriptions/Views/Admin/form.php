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
                <label for="user_id" class="col-sm-3 col-form-label"><?= lang("Subscriptions.user_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('user_id', $users, set_value('user_id', $subscription->user_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <!-- Switch for 'plan_id' -->
            <div class="form-group row">
                <label for="plan_id" class="col-sm-3 col-form-label"><?= lang("Subscriptions.plan_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('plan_id', $plans, set_value('plan_id', $subscription->plan_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="start_date" class="col-sm-3 col-form-label"><?= lang("Subscriptions.start_date") ?></label>
                <div class="col-sm-9">
                    <input type="datetime-local" name="start_date" value="<?= set_value('start_date', $subscription->start_date ?? "") ?>" id="start_date" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="end_date" class="col-sm-3 col-form-label"><?= lang("Subscriptions.end_date") ?></label>
                <div class="col-sm-9">
                    <input type="datetime-local" name="end_date" value="<?= set_value('end_date', $subscription->end_date ?? "") ?>" id="end_date" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="status" class="col-sm-3 col-form-label"><?= lang("Subscriptions.status") ?></label>
                <div class="col-sm-9">
                    <select name="status" id="status" class="form-control">
                        <option value="active" <?= set_select('status', 'active', ($subscription->status ?? "") == 'active') ?>>Active</option>
                        <option value="expired" <?= set_select('status', 'expired', ($subscription->status ?? "") == 'expired') ?>>Expired</option>
                        <option value="cancelled" <?= set_select('status', 'cancelled', ($subscription->status ?? "") == 'cancelled') ?>>Cancelled</option>
                    </select>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <a class="btn btn-secondary" href="<?= ADMIN_URL . 'subscriptions' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<?php $this->endSection(); ?>
