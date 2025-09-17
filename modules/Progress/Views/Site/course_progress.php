<?php
/**
 * Progress Site Course Progress View
 * 
 * This view displays detailed progress for a specific course.
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
            <!-- Course Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3 class="mb-0"><?= esc($course['title']) ?></h3>
                            <p class="text-muted mb-0"><?= esc($course['description']) ?></p>
                        </div>
                        <div class="col-lg-4 text-end">
                            <div class="progress-wrapper">
                                <div class="progress-info">
                                    <div class="progress-percentage">
                                        <span class="text-lg font-weight-bold"><?= number_format($course_stats['overall_progress'], 1) ?>%</span>
                                    </div>
                                </div>
                                <div class="progress progress-lg">
                                    <div class="progress-bar bg-gradient-success" 
                                         style="width: <?= $course_stats['overall_progress'] ?>%"></div>
                                </div>
                                <p class="text-sm text-muted mb-0 mt-2">
                                    <?= $course_stats['completed_units'] ?>/<?= $course_stats['total_units'] ?> <?= lang('Progress.units_completed') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Statistics -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.total_units') ?></p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?= $course_stats['total_units'] ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="fas fa-list text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.completed_units') ?></p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?= $course_stats['completed_units'] ?>
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
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.time_spent') ?></p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?= gmdate('H:i', $course_stats['total_time_spent']) ?>
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
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= lang('Progress.estimated_completion') ?></p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?= $course_stats['estimated_days'] ?> <?= lang('Progress.days') ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-calendar text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Units Progress -->
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0"><?= lang('Progress.units_progress') ?></h5>
                            <p class="text-sm mb-0"><?= lang('Progress.track_unit_completion') ?></p>
                        </div>
                        <div class="ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm mb-0 dropdown-toggle" 
                                            type="button" id="filterDropdown" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter"></i> <?= lang('Progress.filter') ?>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                        <li><a class="dropdown-item" href="#" onclick="filterUnits('all')"><?= lang('Progress.all_units') ?></a></li>
                                        <li><a class="dropdown-item" href="#" onclick="filterUnits('completed')"><?= lang('Progress.completed') ?></a></li>
                                        <li><a class="dropdown-item" href="#" onclick="filterUnits('in_progress')"><?= lang('Progress.in_progress') ?></a></li>
                                        <li><a class="dropdown-item" href="#" onclick="filterUnits('not_started')"><?= lang('Progress.not_started') ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="unitsTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?= lang('Progress.unit') ?>
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        <?= lang('Progress.progress') ?>
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?= lang('Progress.status') ?>
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?= lang('Progress.time_spent') ?>
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?= lang('Progress.last_accessed') ?>
                                    </th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($units_progress)): ?>
                                    <?php foreach ($units_progress as $unit): ?>
                                        <?php 
                                            $status_class = 'not_started';
                                            $status_text = lang('Progress.not_started');
                                            $status_badge = 'bg-gradient-secondary';
                                            
                                            if ($unit['is_completed']) {
                                                $status_class = 'completed';
                                                $status_text = lang('Progress.completed');
                                                $status_badge = 'bg-gradient-success';
                                            } elseif ($unit['progress_percentage'] > 0) {
                                                $status_class = 'in_progress';
                                                $status_text = lang('Progress.in_progress');
                                                $status_badge = 'bg-gradient-warning';
                                            }
                                        ?>
                                        <tr class="unit-row" data-status="<?= $status_class ?>">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?= esc($unit['title']) ?></h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            <?= lang('Progress.unit') ?> <?= $unit['unit_order'] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress-wrapper w-75 mx-auto">
                                                    <div class="progress-info">
                                                        <div class="progress-percentage">
                                                            <span class="text-xs font-weight-bold"><?= number_format($unit['progress_percentage'], 1) ?>%</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-info" 
                                                             style="width: <?= $unit['progress_percentage'] ?>%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm <?= $status_badge ?>"><?= $status_text ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    <?= gmdate('H:i:s', $unit['total_time_spent'] ?? 0) ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    <?= $unit['last_accessed'] ? date('M d, Y', strtotime($unit['last_accessed'])) : '-' ?>
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-secondary mb-0 dropdown-toggle" 
                                                            type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('units/view/' . $unit['id']) ?>">
                                                                <i class="fas fa-play me-2"></i><?= lang('Progress.continue_learning') ?>
                                                            </a>
                                                        </li>
                                                        <?php if ($unit['progress_percentage'] > 0): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="resetUnitProgress(<?= $unit['id'] ?>)">
                                                                    <i class="fas fa-redo me-2"></i><?= lang('Progress.reset_progress') ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url('progress/unit/' . $unit['id']) ?>">
                                                                <i class="fas fa-chart-line me-2"></i><?= lang('Progress.view_analytics') ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-center">
                                                <i class="fas fa-list text-muted" style="font-size: 3rem;"></i>
                                                <h6 class="text-muted mt-3"><?= lang('Progress.no_units_found') ?></h6>
                                                <p class="text-sm text-muted"><?= lang('Progress.course_has_no_units') ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Video Progress Analytics -->
            <?php if (!empty($video_analytics)): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6><?= lang('Progress.video_analytics') ?></h6>
                            </div>
                            <div class="card-body p-3">
                                <canvas id="videoAnalyticsChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Learning Path -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6><?= lang('Progress.learning_path') ?></h6>
                        </div>
                        <div class="card-body pt-4 p-3">
                            <div class="timeline timeline-one-side">
                                <?php if (!empty($units_progress)): ?>
                                    <?php foreach ($units_progress as $index => $unit): ?>
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <?php if ($unit['is_completed']): ?>
                                                    <i class="fas fa-check text-success"></i>
                                                <?php elseif ($unit['progress_percentage'] > 0): ?>
                                                    <i class="fas fa-play text-warning"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-circle text-secondary"></i>
                                                <?php endif; ?>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= esc($unit['title']) ?></h6>
                                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                                    <?= number_format($unit['progress_percentage'], 1) ?>% <?= lang('Progress.completed') ?>
                                                </p>
                                                <?php if ($unit['last_accessed']): ?>
                                                    <p class="text-muted text-xs mt-1 mb-0">
                                                        <?= lang('Progress.last_accessed') ?>: <?= date('M d, Y H:i', strtotime($unit['last_accessed'])) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Filter Units Function
function filterUnits(status) {
    const rows = document.querySelectorAll('.unit-row');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Reset Unit Progress Function
function resetUnitProgress(unitId) {
    if (confirm('<?= lang('Progress.confirm_reset_unit_message') ?>')) {
        fetch('<?= base_url('progress/reset-unit/') ?>' + unitId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?= lang('Progress.error_occurred') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= lang('Progress.error_occurred') ?>');
        });
    }
}

// Video Analytics Chart
<?php if (!empty($video_analytics)): ?>
const videoCtx = document.getElementById('videoAnalyticsChart').getContext('2d');
const videoAnalyticsChart = new Chart(videoCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($video_analytics, 'unit_title')) ?>,
        datasets: [{
            label: '<?= lang('Progress.watch_time') ?> (<?= lang('Progress.minutes') ?>)',
            data: <?= json_encode(array_map(function($item) { return round($item['watch_time'] / 60, 2); }, $video_analytics)) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: '<?= lang('Progress.video_duration') ?> (<?= lang('Progress.minutes') ?>)',
            data: <?= json_encode(array_map(function($item) { return round($item['video_duration'] / 60, 2); }, $video_analytics)) ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
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
<?php endif; ?>
</script>
<?= $this->endSection() ?>