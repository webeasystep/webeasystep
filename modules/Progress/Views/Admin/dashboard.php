<?php
/**
 * Progress Admin Dashboard View
 *
 * This view displays the main dashboard for progress analytics and overview.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Progress.dashboard') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Progress.progress') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $stats['total_students'] ?? 0 ?></h3>
                            <p><?= lang('Users.total_students') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="<?= base_url('dt_admin/users') ?>" class="small-box-footer">
                            <?= lang('Admin.more_info') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $stats['total_courses'] ?? 0 ?></h3>
                            <p><?= lang('Courses.total_courses') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="<?= base_url('dt_admin/courses') ?>" class="small-box-footer">
                            <?= lang('Admin.more_info') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= number_format($stats['avg_completion_rate'] ?? 0, 1) ?>%</h3>
                            <p><?= lang('Progress.average_completion') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= $stats['active_today'] ?? 0 ?></h3>
                            <p><?= lang('Progress.active_today') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.learning_activity') ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="activityChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.completion_status') ?></h3>
                        </div>
                        <div class="card-body">
                            <canvas id="completionChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity and Top Courses -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.recent_activity') ?></h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php if (isset($recent_activity) && !empty($recent_activity)): ?>
                                    <?php foreach ($recent_activity as $activity): ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= esc($activity['username']) ?></strong>
                                                    <small class="text-muted d-block"><?= esc($activity['course_title']) ?></small>
                                                </div>
                                                <div class="text-right">
                                                    <span class="badge badge-<?= $activity['status'] == 'completed' ? 'success' : 'info' ?>">
                                                        <?= lang('Progress.' . $activity['status']) ?>
                                                    </span>
                                                    <small class="text-muted d-block"><?= date('M j, H:i', strtotime($activity['created_at'])) ?></small>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="list-group-item text-center text-muted">
                                        <?= lang('Progress.no_recent_activity') ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?= base_url('dt_admin/progress') ?>" class="btn btn-sm btn-primary">
                                <?= lang('Progress.view_all_progress') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Courses.top_courses') ?></h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= lang('Courses.course') ?></th>
                                        <th><?= lang('Users.students') ?></th>
                                        <th><?= lang('Progress.completion') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($top_courses) && !empty($top_courses)): ?>
                                        <?php foreach ($top_courses as $course): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url('dt_admin/progress/course-analytics/' . $course['id']) ?>">
                                                        <?= esc($course['course_title']) ?>
                                                    </a>
                                                </td>
                                                <td><?= $course['enrolled_count'] ?></td>
                                                <td>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                             style="width: <?= $course['avg_completion'] ?>%"
                                                             aria-valuenow="<?= $course['avg_completion'] ?>"
                                                             aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small><?= number_format($course['avg_completion'], 1) ?>%</small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                <?= lang('Courses.no_courses_available') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Admin.quick_actions') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?= base_url('dt_admin/progress') ?>" class="btn btn-block btn-outline-primary">
                                        <i class="fas fa-list"></i> <?= lang('Progress.view_all_progress') ?>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= base_url('dt_admin/courses') ?>" class="btn btn-block btn-outline-success">
                                        <i class="fas fa-book"></i> <?= lang('Courses.manage_courses') ?>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= base_url('dt_admin/users') ?>" class="btn btn-block btn-outline-info">
                                        <i class="fas fa-users"></i> <?= lang('Users.manage_users') ?>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-block btn-outline-warning" onclick="exportReport()">
                                        <i class="fas fa-download"></i> <?= lang('Progress.export_report') ?>
                                    </button>
                                </div>
                            </div>
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
    // Activity Chart
    var activityCtx = document.getElementById('activityChart').getContext('2d');
    var activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($charts['activity_labels'] ?? []) ?>,
            datasets: [{
                label: '<?= lang('Progress.daily_active_users') ?>',
                data: <?= json_encode($charts['activity_data'] ?? []) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
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

    // Completion Status Chart
    var completionCtx = document.getElementById('completionChart').getContext('2d');
    var completionChart = new Chart(completionCtx, {
        type: 'doughnut',
        data: {
            labels: [
                '<?= lang('Progress.completed') ?>',
                '<?= lang('Progress.in_progress') ?>',
                '<?= lang('Progress.not_started') ?>'
            ],
            datasets: [{
                data: <?= json_encode($charts['completion_data'] ?? [0, 0, 0]) ?>,
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
});

function exportReport() {
    window.location.href = '<?= base_url('dt_admin/progress/export-dashboard-report') ?>';
}
</script>
<?= $this->endSection() ?>
