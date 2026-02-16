<?php
/**
 * Progress Admin User Analytics View
 *
 * This view displays detailed analytics for a specific user's learning progress.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Progress.user_analytics') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/progress') ?>"><?= lang('Progress.progress') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Progress.user_analytics') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- User Info Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-widget widget-user">
                        <div class="widget-user-header bg-info">
                            <h3 class="widget-user-username"><?= esc($user['username']) ?></h3>
                            <h5 class="widget-user-desc"><?= esc($user['email']) ?></h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="<?= base_url('admin/dist/img/user-default.png') ?>" alt="User Avatar">
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-3 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= $stats['total_units'] ?? 0 ?></h5>
                                        <span class="description-text"><?= lang('Progress.total_units') ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= $stats['completed_units'] ?? 0 ?></h5>
                                        <span class="description-text"><?= lang('Progress.completed_units') ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= number_format($stats['completion_rate'] ?? 0, 1) ?>%</h5>
                                        <span class="description-text"><?= lang('Progress.completion_rate') ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= gmdate('H:i:s', $stats['total_watch_time'] ?? 0) ?></h5>
                                        <span class="description-text"><?= lang('Progress.total_learning_time') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Charts -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.course_progress') ?></h3>
                        </div>
                        <div class="card-body">
                            <canvas id="courseProgressChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.learning_activity') ?></h3>
                        </div>
                        <div class="card-body">
                            <canvas id="activityChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Progress Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Progress.recent_progress') ?></h3>
                        </div>
                        <div class="card-body">
                            <table id="progress-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?= lang('Courses.course') ?></th>
                                        <th><?= lang('Courses.unit') ?></th>
                                        <th><?= lang('Progress.progress_percentage') ?></th>
                                        <th><?= lang('Progress.watch_time') ?></th>
                                        <th><?= lang('Admin.status') ?></th>
                                        <th><?= lang('Admin.last_updated') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($stats['recent_progress']) && !empty($stats['recent_progress'])): ?>
                                        <?php foreach ($stats['recent_progress'] as $progress): ?>
                                            <tr>
                                                <td><?= esc($progress['course_title']) ?></td>
                                                <td><?= esc($progress['unit_title']) ?></td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                             style="width: <?= $progress['progress_percentage'] ?>%"
                                                             aria-valuenow="<?= $progress['progress_percentage'] ?>"
                                                             aria-valuemin="0" aria-valuemax="100">
                                                            <?= number_format($progress['progress_percentage'], 1) ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= gmdate('H:i:s', $progress['watch_time'] ?? 0) ?></td>
                                                <td>
                                                    <?php if ($progress['is_completed']): ?>
                                                        <span class="badge badge-success"><?= lang('Progress.completed') ?></span>
                                                    <?php elseif ($progress['progress_percentage'] > 0): ?>
                                                        <span class="badge badge-warning"><?= lang('Progress.in_progress') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary"><?= lang('Progress.not_started') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('Y-m-d H:i', strtotime($progress['updated_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center"><?= lang('Progress.no_progress_found') ?></td>
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
    $('#progress-table').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "order": [[5, "desc"]],
        "language": {
            "url": "<?= base_url('admin/plugins/datatables/i18n/' . (session('lang') ?? 'en') . '.json') ?>"
        }
    });

    // Course Progress Chart
    var courseCtx = document.getElementById('courseProgressChart').getContext('2d');
    var courseChart = new Chart(courseCtx, {
        type: 'doughnut',
        data: {
            labels: ['<?= lang('Progress.completed') ?>', '<?= lang('Progress.in_progress') ?>', '<?= lang('Progress.not_started') ?>'],
            datasets: [{
                data: [
                    <?= $stats['completed_units'] ?? 0 ?>,
                    <?= ($stats['in_progress_units'] ?? 0) ?>,
                    <?= ($stats['total_units'] ?? 0) - ($stats['completed_units'] ?? 0) - ($stats['in_progress_units'] ?? 0) ?>
                ],
                backgroundColor: ['#28a745', '#ffc107', '#6c757d']
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

    // Activity Chart (placeholder - would need actual data)
    var activityCtx = document.getElementById('activityChart').getContext('2d');
    var activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: '<?= lang('Progress.learning_time') ?>',
                data: [0, 0, 0, 0, 0, 0, 0], // Placeholder data
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
});
</script>
<?= $this->endSection() ?>
