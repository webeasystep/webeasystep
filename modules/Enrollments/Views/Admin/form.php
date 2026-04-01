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

            <div class="form-group row">
                <label for="payment_method" class="col-sm-3 col-form-label"><?= lang("Enrollments.payment_method") ?></label>
                <div class="col-sm-9">
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="instapay" <?= set_select('payment_method', 'instapay', ($enrollment->payment_method ?? 'instapay') == 'instapay') ?>>انستاباي</option>
                        <option value="vodafone_cash" <?= set_select('payment_method', 'vodafone_cash', ($enrollment->payment_method ?? "") == 'vodafone_cash') ?>>فودافون كاش</option>
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
<?= $this->endSection(); ?>

