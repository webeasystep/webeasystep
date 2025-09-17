<?php
/**
 * Progress Admin Form View
 * 
 * This view provides a form for creating and editing progress records.
 * 
 * @package    MSARLink
 * @subpackage Progress
 * @category   Views
 * @author     MSARLink Team
 * @since      1.0.0
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?= isset($progress) ? lang('Progress.edit_progress') : lang('Progress.add_progress') ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/progress') ?>"><?= lang('Progress.progress') ?></a></li>
                        <li class="breadcrumb-item active">
                            <?= isset($progress) ? lang('Progress.edit') : lang('Progress.add') ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?= isset($progress) ? lang('Progress.edit_progress') : lang('Progress.add_progress') ?>
                            </h3>
                        </div>
                        
                        <?= form_open_multipart(isset($progress) ? 'admin/progress/update/' . $progress->id : 'admin/progress/store', ['class' => 'form-horizontal']) ?>
                        <div class="card-body">
                            <?php if (session()->getFlashdata('errors')): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- User Selection -->
                            <div class="form-group row">
                                <label for="user_id" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.user') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="user_id" name="user_id" required>
                                        <option value=""><?= lang('Progress.select_user') ?></option>
                                        <?php if (!empty($users)): ?>
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?= $user->id ?>" 
                                    <?= (isset($progress) && $progress->user_id == $user->id) ? 'selected' : '' ?>>
                                    <?= esc($user->username) ?> (<?= esc($user->email) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted"><?= lang('Progress.user_help') ?></small>
                                </div>
                            </div>

                            <!-- Course Selection -->
                            <div class="form-group row">
                                <label for="course_id" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.course') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="course_id" name="course_id" required>
                                        <option value=""><?= lang('Progress.select_course') ?></option>
                                        <?php if (!empty($courses)): ?>
                                            <?php foreach ($courses as $course): ?>
                                                <option value="<?= $course->id ?>" 
                                    <?= (isset($progress) && $progress->course_id == $course->id) ? 'selected' : '' ?>>
                                    <?= esc($course->title) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted"><?= lang('Progress.course_help') ?></small>
                                </div>
                            </div>

                            <!-- Unit Selection -->
                            <div class="form-group row">
                                <label for="unit_id" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.unit') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="unit_id" name="unit_id" required>
                                        <option value=""><?= lang('Progress.select_unit') ?></option>
                                        <?php if (!empty($units)): ?>
                                            <?php foreach ($units as $unit): ?>
                                                <option value="<?= $unit->id ?>" 
                                    data-course="<?= $unit->course_id ?>"
                                    <?= (isset($progress) && $progress->unit_id == $unit->id) ? 'selected' : '' ?>>
                                    <?= esc($unit->title) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted"><?= lang('Progress.unit_help') ?></small>
                                </div>
                            </div>

                            <!-- Progress Percentage -->
                            <div class="form-group row">
                                <label for="progress_percentage" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.progress_percentage') ?>
                                </label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="progress_percentage" 
                                               name="progress_percentage" 
                                               min="0" 
                                               max="100" 
                                               step="0.1"
                                               value="<?= isset($progress) ? esc($progress->progress_percentage) : '0' ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted"><?= lang('Progress.progress_percentage_help') ?></small>
                                </div>
                            </div>

                            <!-- Completion Status -->
                            <div class="form-group row">
                                <label for="is_completed" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.completion_status') ?>
                                </label>
                                <div class="col-sm-10">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_completed" 
                                               name="is_completed" 
                                               value="1"
                                               <?= (isset($progress) && $progress->is_completed == 1) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="is_completed">
                                            <?= lang('Progress.mark_as_completed') ?>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted"><?= lang('Progress.completion_status_help') ?></small>
                                </div>
                            </div>

                            <!-- Video Progress Section -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h5><?= lang('Progress.video_progress') ?></h5>
                                    <hr>
                                </div>
                            </div>

                            <!-- Video Duration -->
                            <div class="form-group row">
                                <label for="duration_hours" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.video_duration') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" 
                                           class="form-control" 
                                           id="duration_hours" 
                                           name="duration_hours" 
                                           min="0"
                                           step="0.01"
                                           value="<?= isset($progress) ? esc($progress->duration_hours) : '0' ?>">
                                    <small class="form-text text-muted"><?= lang('Progress.video_duration_help') ?></small>
                                </div>
                            </div>

                            <!-- Watch Time -->
                            <div class="form-group row">
                                <label for="watch_time" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.watch_time') ?>
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" 
                                           class="form-control" 
                                           id="watch_time" 
                                           name="watch_time" 
                                           min="0"
                                           value="<?= isset($progress) ? esc($progress->watch_time) : '0' ?>">
                                    <small class="form-text text-muted"><?= lang('Progress.watch_time_help') ?></small>
                                </div>
                            </div>

                            <!-- Last Position -->
                            <div class="form-group row">
                                <label for="last_position" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.last_position') ?>
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" 
                                           class="form-control" 
                                           id="last_position" 
                                           name="last_position" 
                                           min="0"
                                           value="<?= isset($progress) ? esc($progress->last_position) : '0' ?>">
                                    <small class="form-text text-muted"><?= lang('Progress.last_position_help') ?></small>
                                </div>
                            </div>

                            <!-- Time Tracking Section -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h5><?= lang('Progress.time_tracking') ?></h5>
                                    <hr>
                                </div>
                            </div>

                            <!-- Started At -->
                            <div class="form-group row">
                                <label for="started_at" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.started_at') ?>
                                </label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="started_at" 
                                           name="started_at" 
                                           value="<?= isset($progress) && $progress->started_at ? date('Y-m-d\TH:i', strtotime($progress->started_at)) : '' ?>">
                                    <small class="form-text text-muted"><?= lang('Progress.started_at_help') ?></small>
                                </div>
                            </div>

                            <!-- Completed At -->
                            <div class="form-group row">
                                <label for="completed_at" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.completed_at') ?>
                                </label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="completed_at" 
                                           name="completed_at" 
                                           value="<?= isset($progress) && $progress->completed_at ? date('Y-m-d\TH:i', strtotime($progress->completed_at)) : '' ?>">
                                    <small class="form-text text-muted"><?= lang('Progress.completed_at_help') ?></small>
                                </div>
                            </div>

                            <!-- Total Time Spent -->
                            <div class="form-group row">
                                <label for="total_time_spent" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.total_time_spent') ?>
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" 
                                           class="form-control" 
                                           id="total_time_spent" 
                                           name="total_time_spent" 
                                           min="0"
                                           value="<?= isset($progress) ? esc($progress->total_time_spent) : '0' ?>">
                                    <small class="form-text text-muted"><?= lang('Progress.total_time_spent_help') ?></small>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="form-group row">
                                <label for="notes" class="col-sm-2 col-form-label">
                                    <?= lang('Progress.notes') ?>
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="<?= lang('Progress.notes_placeholder') ?>"><?= isset($progress) ? esc($progress->notes) : '' ?></textarea>
                                    <small class="form-text text-muted"><?= lang('Progress.notes_help') ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 
                                <?= isset($progress) ? lang('Admin.update') : lang('Admin.save') ?>
                            </button>
                            <a href="<?= base_url('admin/progress') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> <?= lang('Admin.cancel') ?>
                            </a>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Filter units based on selected course
    $('#course_id').change(function() {
        var courseId = $(this).val();
        var unitSelect = $('#unit_id');
        
        unitSelect.find('option').hide();
        unitSelect.find('option[value=""]').show();
        
        if (courseId) {
            unitSelect.find('option[data-course="' + courseId + '"]').show();
        } else {
            unitSelect.find('option').show();
        }
        
        unitSelect.val('').trigger('change');
    });

    // Auto-complete when progress reaches 100%
    $('#progress_percentage').change(function() {
        var percentage = parseFloat($(this).val());
        var completedCheckbox = $('#is_completed');
        
        if (percentage >= 100) {
            completedCheckbox.prop('checked', true);
            if (!$('#completed_at').val()) {
                var now = new Date();
                var dateString = now.getFullYear() + '-' + 
                    String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(now.getDate()).padStart(2, '0') + 'T' + 
                    String(now.getHours()).padStart(2, '0') + ':' + 
                    String(now.getMinutes()).padStart(2, '0');
                $('#completed_at').val(dateString);
            }
        }
    });

    // Clear completed_at when unchecking completion
    $('#is_completed').change(function() {
        if (!$(this).is(':checked')) {
            $('#completed_at').val('');
        }
    });

    // Auto-set started_at if not set and progress > 0
    $('#progress_percentage, #watch_time').change(function() {
        var value = parseFloat($(this).val());
        if (value > 0 && !$('#started_at').val()) {
            var now = new Date();
            var dateString = now.getFullYear() + '-' + 
                String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                String(now.getDate()).padStart(2, '0') + 'T' + 
                String(now.getHours()).padStart(2, '0') + ':' + 
                String(now.getMinutes()).padStart(2, '0');
            $('#started_at').val(dateString);
        }
    });

    // Validate watch time doesn't exceed video duration
    $('#watch_time').change(function() {
        var watchTime = parseFloat($(this).val());
        var videoDuration = parseFloat($('#duration_hours').val());
        
        if (watchTime > videoDuration && videoDuration > 0) {
            alert('<?= lang('Progress.watch_time_exceeds_duration') ?>');
            $(this).val(videoDuration);
        }
    });

    // Validate last position doesn't exceed video duration
    $('#last_position').change(function() {
        var lastPosition = parseFloat($(this).val());
        var videoDuration = parseFloat($('#duration_hours').val());
        
        if (lastPosition > videoDuration && videoDuration > 0) {
            alert('<?= lang('Progress.last_position_exceeds_duration') ?>');
            $(this).val(videoDuration);
        }
    });
});
</script>
<?= $this->endSection() ?>