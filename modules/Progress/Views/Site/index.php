<?php
/**
 * Progress Site Index View
 * 
 * This view displays the user's progress dashboard on the frontend.
 * 
 * @package    MSARLink
 * @subpackage Progress
 * @category   Views
 * @author     MSARLink Team
 * @since      1.0.0
 */
?>

<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0"><?= lang('Progress.my_progress') ?></h5>
                            <p class="text-sm mb-0"><?= lang('Progress.track_your_learning_journey') ?></p>
                        </div>
                        <div class="ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <button class="btn btn-outline-primary btn-sm mb-0" onclick="exportProgress()">
                                    <i class="fas fa-download"></i> <?= lang('Progress.export_progress') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-0">
                    <!-- Progress Statistics -->
                    <div class="row px-4 mb-4">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-plain h-100">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.total_courses') ?></p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <?= $stats['total_courses'] ?? 0 ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="fas fa-book text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-plain h-100">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.completed_units') ?></p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <?= $stats['completed_units'] ?? 0 ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="fas fa-check-circle text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-plain h-100">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.total_time_spent') ?></p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <?= gmdate('H:i', $stats['total_time_spent'] ?? 0) ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="fas fa-clock text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-plain h-100">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.average_progress') ?></p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <?= number_format($stats['average_progress'] ?? 0, 1) ?>%
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-chart-pie text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Chart -->
                    <div class="row px-4 mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6><?= lang('Progress.progress_over_time') ?></h6>
                                </div>
                                <div class="card-body p-3">
                                    <canvas id="progressChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Progress List -->
                    <div class="row px-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6><?= lang('Progress.course_progress') ?></h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        <?= lang('Progress.course') ?>
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        <?= lang('Progress.progress') ?>
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        <?= lang('Progress.status') ?>
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        <?= lang('Progress.last_accessed') ?>
                                                    </th>
                                                    <th class="text-secondary opacity-7"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($course_progress)): ?>
                                                    <?php foreach ($course_progress as $course): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm"><?= esc($course['course_title']) ?></h6>
                                                                        <p class="text-xs text-secondary mb-0">
                                                                            <?= $course['completed_units'] ?>/<?= $course['total_units'] ?> <?= lang('Progress.units_completed') ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="progress-wrapper w-75 mx-auto">
                                                                    <div class="progress-info">
                                                                        <div class="progress-percentage">
                                                                            <span class="text-xs font-weight-bold"><?= number_format($course['progress_percentage'], 1) ?>%</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-gradient-info" 
                                                                             style="width: <?= $course['progress_percentage'] ?>%"></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle text-center text-sm">
                                                                <?php if ($course['progress_percentage'] >= 100): ?>
                                                                    <span class="badge badge-sm bg-gradient-success"><?= lang('Progress.completed') ?></span>
                                                                <?php elseif ($course['progress_percentage'] > 0): ?>
                                                                    <span class="badge badge-sm bg-gradient-warning"><?= lang('Progress.in_progress') ?></span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-sm bg-gradient-secondary"><?= lang('Progress.not_started') ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                <span class="text-secondary text-xs font-weight-bold">
                                                                    <?= $course['last_accessed'] ? date('M d, Y', strtotime($course['last_accessed'])) : '-' ?>
                                                                </span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="<?= base_url('progress/course/' . $course['course_id']) ?>" 
                                                                   class="btn btn-link text-dark px-3 mb-0">
                                                                    <i class="fas fa-eye text-dark me-2"></i><?= lang('Progress.view_details') ?>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">
                                                            <div class="text-center">
                                                                <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                                                                <h6 class="text-muted mt-3"><?= lang('Progress.no_courses_enrolled') ?></h6>
                                                                <p class="text-sm text-muted"><?= lang('Progress.start_learning_message') ?></p>
                                                                <a href="<?= base_url('courses') ?>" class="btn btn-primary btn-sm">
                                                                    <?= lang('Progress.browse_courses') ?>
                                                                </a>
                                                            </div>
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

                    <!-- Recent Activity -->
                    <?php if (!empty($recent_activity)): ?>
                        <div class="row px-4 mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h6><?= lang('Progress.recent_activity') ?></h6>
                                    </div>
                                    <div class="card-body pt-4 p-3">
                                        <ul class="list-group">
                                            <?php foreach ($recent_activity as $activity): ?>
                                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-3 text-sm"><?= esc($activity['course_title']) ?></h6>
                                                        <span class="mb-2 text-xs">
                                                            <?= lang('Progress.unit') ?>: <span class="text-dark font-weight-bold ms-sm-2"><?= esc($activity['unit_title']) ?></span>
                                                        </span>
                                                        <span class="mb-2 text-xs">
                                                            <?= lang('Progress.progress') ?>: <span class="text-dark ms-sm-2 font-weight-bold"><?= $activity['progress_percentage'] ?>%</span>
                                                        </span>
                                                        <span class="text-xs">
                                                            <?= lang('Progress.last_accessed') ?>: <span class="text-dark ms-sm-2 font-weight-bold"><?= date('M d, Y H:i', strtotime($activity['last_accessed'])) ?></span>
                                                        </span>
                                                    </div>
                                                    <div class="ms-auto text-end">
                                                        <?php if ($activity['is_completed']): ?>
                                                            <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center border-radius-md">
                                                                <i class="fas fa-check text-white opacity-10"></i>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="icon icon-shape icon-sm bg-gradient-warning shadow text-center border-radius-md">
                                                                <i class="fas fa-play text-white opacity-10"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Progress Chart
const ctx = document.getElementById('progressChart').getContext('2d');
const progressChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_data['labels'] ?? []) ?>,
        datasets: [{
            label: '<?= lang('Progress.progress_percentage') ?>',
            data: <?= json_encode($chart_data['data'] ?? []) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Export Progress Function
function exportProgress() {
    window.location.href = '<?= base_url('progress/export') ?>';
}

// Auto-refresh every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>
<?= $this->endSection() ?>