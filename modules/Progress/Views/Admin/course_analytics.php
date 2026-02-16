<?php
/**
 * Progress Admin Course Analytics View
 *
 * This view displays detailed analytics for a specific course's learning progress.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Progress.course_analytics') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/progress') ?>"><?= lang('Progress.progress') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Progress.course_analytics') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Course Info Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= esc($course['course_title']) ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p><strong><?= lang('Admin.description') ?>:</strong> <?= esc($course['description']) ?></p>
                                    <p><strong><?= lang('Courses.instructor') ?>:</strong> <?= esc($course['instructor']) ?></p>
                                    <p><strong><?= lang('Admin.created_at') ?>:</strong> <?= date('Y-m-d', strtotime($course['created_at'])) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= lang('Users.enrolled_students') ?></span>
                                            <span class="info-box-number"><?= $analytics['total_enrolled'] ?? 0 ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= number_format($analytics['completion_rate'] ?? 0, 1) ?>%</h3>
                            <p><?= lang('Progress.completion_rate') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $analytics['total_units'] ?? 0 ?></h3>
                            <p><?= lang('Progress.total_units') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= gmdate('H:i', $analytics['avg_completion_time'] ?? 0) ?></h3>
                            <p><?= lang('Progress.average_completion_time') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= $analytics['active_learners'] ?? 0 ?></h3>
                            <p><?= lang('Progress.active_learners') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.unit_completion_rates') ?></h3>
                        </div>
                        <div class="card-body">
                            <canvas id="unitCompletionChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.engagement_over_time') ?></h3>
                        </div>
                        <div class="card-body">
                            <canvas id="engagementChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Progress Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.student_progress') ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" onclick="exportProgress()">
                                    <i class="fas fa-download"></i> <?= lang('Admin.export') ?>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="student-progress-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?= lang('Users.student') ?></th>
                                        <th><?= lang('Users.email') ?></th>
                                        <th><?= lang('Progress.units_completed') ?></th>
                                        <th><?= lang('Progress.completion_rate') ?></th>
                                        <th><?= lang('Progress.total_learning_time') ?></th>
                                        <th><?= lang('Admin.last_activity') ?></th>
                                        <th><?= lang('Admin.status') ?></th>
                                        <th><?= lang('Admin.actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($analytics['student_progress']) && !empty($analytics['student_progress'])): ?>
                                        <?php foreach ($analytics['student_progress'] as $student): ?>
                                            <tr>
                                                <td><?= esc($student['username']) ?></td>
                                                <td><?= esc($student['email']) ?></td>
                                                <td><?= $student['completed_units'] ?>/<?= $analytics['total_units'] ?></td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                             style="width: <?= $student['completion_rate'] ?>%"
                                                             aria-valuenow="<?= $student['completion_rate'] ?>"
                                                             aria-valuemin="0" aria-valuemax="100">
                                                            <?= number_format($student['completion_rate'], 1) ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= gmdate('H:i:s', $student['total_watch_time'] ?? 0) ?></td>
                                                <td><?= $student['last_activity'] ? date('Y-m-d H:i', strtotime($student['last_activity'])) : '-' ?></td>
                                                <td>
                                                    <?php if ($student['completion_rate'] >= 100): ?>
                                                        <span class="badge badge-success"><?= lang('Progress.completed') ?></span>
                                                    <?php elseif ($student['completion_rate'] > 0): ?>
                                                        <span class="badge badge-warning"><?= lang('Progress.in_progress') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary"><?= lang('Progress.not_started') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('dt_admin/progress/user-analytics/' . $student['user_id']) ?>"
                                                       class="btn btn-sm btn-info" title="<?= lang('Progress.view_details') ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center"><?= lang('Progress.no_students_enrolled') ?></td>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('admin/plugins/chart.js/Chart.min.js') ?>"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#student-progress-table').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "order": [[3, "desc"]],
        "language": {
            "url": "<?= base_url('admin/plugins/datatables/i18n/' . (session('lang') ?? 'en') . '.json') ?>"
        }
    });

    // Unit Completion Chart
    var unitCtx = document.getElementById('unitCompletionChart').getContext('2d');
    var unitChart = new Chart(unitCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($analytics['unit_labels'] ?? []) ?>,
            datasets: [{
                label: '<?= lang('Progress.completion_rate') ?>',
                data: <?= json_encode($analytics['unit_completion_rates'] ?? []) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Engagement Chart
    var engagementCtx = document.getElementById('engagementChart').getContext('2d');
    var engagementChart = new Chart(engagementCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($analytics['engagement_labels'] ?? []) ?>,
            datasets: [{
                label: '<?= lang('Progress.active_learners') ?>',
                data: <?= json_encode($analytics['engagement_data'] ?? []) ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

function exportProgress() {
    window.location.href = '<?= base_url('dt_admin/progress/export-course/' . $course['id']) ?>';
}
</script>
<?= $this->endSection() ?>
