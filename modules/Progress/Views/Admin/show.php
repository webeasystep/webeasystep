<?php
/**
 * Progress Admin Show View
 *
 * This view displays detailed progress information for a specific user/unit.
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
                    <h1 class="m-0"><?= lang('Progress.progress_details') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/progress') ?>"><?= lang('Progress.progress') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Progress.details') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- User Information -->
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.user_information') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="<?= base_url('admin/dist/img/user-default.png') ?>"
                                     alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center"><?= esc($progress->username ?? '') ?></h3>
                <p class="text-muted text-center"><?= esc($progress->email ?? '') ?></p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b><?= lang('Progress.user_id') ?></b>
                                    <a class="float-right"><?= esc($progress->user_id ?? '') ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b><?= lang('Progress.registration_date') ?></b>
                                    <a class="float-right"><?= date('Y-m-d', strtotime($progress->created_at ?? '')) ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b><?= lang('Progress.total_courses') ?></b>
                                    <a class="float-right"><?= $user_stats['total_courses'] ?? 0 ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b><?= lang('Progress.completed_units') ?></b>
                                    <a class="float-right"><?= $user_stats['completed_units'] ?? 0 ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Progress Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.unit_progress_details') ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-warning btn-sm" onclick="resetProgress(<?= $progress->id ?>)">
                                    <i class="fas fa-redo"></i> <?= lang('Progress.reset_progress') ?>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><?= lang('Progress.course') ?>:</strong>
                                    <p class="text-muted"><?= esc($progress->course_title ?? '') ?></p>

                                    <strong><?= lang('Progress.unit') ?>:</strong>
                                    <p class="text-muted"><?= esc($progress->unit_title ?? '') ?></p>

                                    <strong><?= lang('Progress.status') ?>:</strong>
                                    <p class="text-muted">
                                        <?php if ($progress->is_completed == 1): ?>
                                            <span class="badge badge-success"><?= lang('Progress.completed') ?></span>
                                        <?php elseif ($progress->progress_percentage > 0): ?>
                                            <span class="badge badge-warning"><?= lang('Progress.in_progress') ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?= lang('Progress.not_started') ?></span>
                                        <?php endif; ?>
                                    </p>

                                    <strong><?= lang('Progress.progress_percentage') ?>:</strong>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-primary"
                                             style="width: <?= $progress->progress_percentage ?? 0 ?>%">
                         <?= $progress->progress_percentage ?? 0 ?>%
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <strong><?= lang('Progress.started_at') ?>:</strong>
                                    <p class="text-muted">
                                        <?= $progress->started_at ? date('Y-m-d H:i:s', strtotime($progress->started_at)) : lang('Progress.not_started') ?>
                                    </p>

                                    <strong><?= lang('Progress.completed_at') ?>:</strong>
                                    <p class="text-muted">
                                        <?= $progress->completed_at ? date('Y-m-d H:i:s', strtotime($progress->completed_at)) : lang('Progress.not_completed') ?>
                                    </p>

                                    <strong><?= lang('Progress.last_accessed') ?>:</strong>
                                    <p class="text-muted">
                                        <?= $progress->last_accessed ? date('Y-m-d H:i:s', strtotime($progress->last_accessed)) : lang('Progress.never_accessed') ?>
                                    </p>

                                    <strong><?= lang('Progress.total_time_spent') ?>:</strong>
                                    <p class="text-muted">
                                        <?= gmdate('H:i:s', $progress->total_time_spent ?? 0) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Video Progress Details -->
                            <?php if (!empty($progress->video_progress)): ?>
                                <hr>
                                <h5><?= lang('Progress.video_progress_details') ?></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><?= lang('Progress.video_duration') ?>:</strong>
                <p class="text-muted"><?= gmdate('H:i:s', ($progress->duration_hours ?? 0) * 3600) ?></p>

                                        <strong><?= lang('Progress.watch_time') ?>:</strong>
                                        <p class="text-muted"><?= gmdate('H:i:s', $progress->watch_time ?? 0) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><?= lang('Progress.last_position') ?>:</strong>
                                        <p class="text-muted"><?= gmdate('H:i:s', $progress->last_position ?? 0) ?></p>

                                        <strong><?= lang('Progress.video_completion') ?>:</strong>
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-info"
                                                 style="width: <?= $progress->video_completion_percentage ?? 0 ?>%">
                             <?= number_format($progress->video_completion_percentage ?? 0, 1) ?>%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Progress History -->
                            <?php if (!empty($progress_history)): ?>
                                <hr>
                                <h5><?= lang('Progress.progress_history') ?></h5>
                                <div class="timeline">
                                    <?php foreach ($progress_history as $history): ?>
                                        <div class="time-label">
                                            <span class="bg-primary"><?= date('Y-m-d', strtotime($history['created_at'])) ?></span>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    <i class="fas fa-clock"></i> <?= date('H:i', strtotime($history['created_at'])) ?>
                                                </span>
                                                <h3 class="timeline-header"><?= esc($history['action']) ?></h3>
                                                <div class="timeline-body">
                                                    <?= esc($history['description']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Progress -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.user_course_progress') ?></h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?= lang('Progress.unit') ?></th>
                                        <th><?= lang('Progress.progress_percentage') ?></th>
                                        <th><?= lang('Progress.status') ?></th>
                                        <th><?= lang('Progress.last_accessed') ?></th>
                                        <th><?= lang('Admin.actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($course_progress)): ?>
                                        <?php foreach ($course_progress as $unit_progress): ?>
                                            <tr>
                                                <td><?= esc($unit_progress['unit_title']) ?></td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar"
                                                             style="width: <?= $unit_progress['progress_percentage'] ?>%">
                                                            <?= $unit_progress['progress_percentage'] ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($unit_progress['is_completed'] == 1): ?>
                                                        <span class="badge badge-success"><?= lang('Progress.completed') ?></span>
                                                    <?php elseif ($unit_progress['progress_percentage'] > 0): ?>
                                                        <span class="badge badge-warning"><?= lang('Progress.in_progress') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary"><?= lang('Progress.not_started') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $unit_progress['last_accessed'] ? date('Y-m-d H:i', strtotime($unit_progress['last_accessed'])) : '-' ?></td>
                                                <td>
                                                    <a href="<?= base_url('admin/progress/show/' . $unit_progress['id']) ?>"
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <?= lang('Progress.no_progress_found') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function resetProgress(id) {
    if (confirm('<?= lang('Progress.confirm_reset_message') ?>')) {
        $.post('<?= base_url('admin/progress/reset/') ?>' + id, function(response) {
            if (response.success) {
                location.reload();
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        });
    }
}
</script>
<?= $this->endSection() ?>
