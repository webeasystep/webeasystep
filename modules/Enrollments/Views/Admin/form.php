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

            <!-- Switch for 'course_id' -->
            <div class="form-group row">
                <label for="course_id" class="col-sm-3 col-form-label"><?= lang("Enrollments.course_name") ?></label>
                <div class="col-sm-9">
                    <?= form_dropdown('course_id', $courses, set_value('course_id', $enrollment->course_id ?? ""), ['class' => 'form-control select2', 'style' => 'width: 100%;']) ?>
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="amount" class="col-sm-3 col-form-label"><?= lang("Enrollments.amount") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="amount" value="<?= set_value('amount', $enrollment->amount ?? "") ?>" id="amount" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="enrollment_method" class="col-sm-3 col-form-label"><?= lang("Enrollments.enrollment_method") ?></label>
                <div class="col-sm-9">
                    <input type="text" name="enrollment_method" value="<?= set_value('enrollment_method', $enrollment->enrollment_method ?? "") ?>" id="enrollment_method" class="form-control">
                    <small class="invalid-feedback"></small>
                </div>
            </div>

            <div class="form-group row">
                <label for="enrollment_status" class="col-sm-3 col-form-label"><?= lang("Enrollments.enrollment_status") ?></label>
                <div class="col-sm-9">
                    <select name="enrollment_status" id="enrollment_status" class="form-control">
                        <option value="pending" <?= set_select('enrollment_status', 'pending', ($enrollment->enrollment_status ?? "") == 'pending') ?>>Pending</option>
                        <option value="completed" <?= set_select('enrollment_status', 'completed', ($enrollment->enrollment_status ?? "") == 'completed') ?>>Completed</option>
                        <option value="failed" <?= set_select('enrollment_status', 'failed', ($enrollment->enrollment_status ?? "") == 'failed') ?>>Failed</option>
                    </select>
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
