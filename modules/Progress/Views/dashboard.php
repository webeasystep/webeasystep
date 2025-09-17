<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Learning Dashboard</h1>
                    <p class="text-muted mb-0">Track your learning progress and achievements</p>
                </div>
                <div>
                    <a href="<?= base_url('progress/export') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i> Export Progress
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Learning Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Watch Time
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['total_watch_time_formatted'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Units
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['completed_units'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Units in Progress
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['in_progress_units'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Learning Streak
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['learning_streak_days'] ?> days
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Course Progress -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Course Progress</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($enrolled_courses)): ?>
                        <?php foreach ($enrolled_courses as $course): ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">
                                        <a href="<?= base_url('courses/view/' . $course->slug) ?>" class="text-decoration-none">
                                            <?= esc($course->course_title) ?>
                                        </a>
                                    </h6>
                                    <span class="badge bg-primary"><?= number_format($course->completion_percentage, 1) ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: <?= $course->completion_percentage ?>%"
                                         aria-valuenow="<?= $course->completion_percentage ?>"
                                         aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Enrolled: <?= date('M j, Y', strtotime($course->enrolled_at)) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-graduation-cap fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">You haven't enrolled in any courses yet.</p>
                            <a href="<?= base_url('courses') ?>" class="btn btn-primary">Browse Courses</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Units Needing Attention -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Units Needing Attention</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($units_needing_attention)): ?>
                        <?php foreach ($units_needing_attention as $unit): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="progress-circle" style="width: 40px; height: 40px; position: relative;">
                                        <svg width="40" height="40" class="progress-ring">
                                            <circle cx="20" cy="20" r="15" fill="transparent"
                                                    stroke="#e3e6f0" stroke-width="3"/>
                                            <circle cx="20" cy="20" r="15" fill="transparent"
                                                    stroke="#f6c23e" stroke-width="3"
                                                    stroke-dasharray="<?= 2 * pi() * 15 ?>"
                                                    stroke-dashoffset="<?= 2 * pi() * 15 * (1 - $unit['progress_percentage'] / 100) ?>"
                                                    transform="rotate(-90 20 20)"/>
                                        </svg>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <small class="text-muted" style="font-size: 10px;"><?= round($unit['progress_percentage']) ?>%</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">
                                        <a href="<?= base_url('courses/unit/' . $unit['unit_id']) ?>" class="text-decoration-none">
                                            <?= esc($unit['unit_title']) ?>
                                        </a>
                                    </h6>
                                    <p class="text-muted mb-0 small"><?= esc($unit['course_title']) ?></p>
                                    <small class="text-muted">
                                        Last accessed: <?= date('M j', strtotime($unit['updated_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted mb-0">Great job! No units need attention.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Learning Activity</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_activity)): ?>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Course</th>
                                        <th>Progress</th>
                                        <th>Watch Time</th>
                                        <th>Last Activity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_activity as $activity): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('courses/unit/' . $activity['unit_id']) ?>" class="text-decoration-none">
                                                    <?= esc($activity['unit_title']) ?>
                                                </a>
                                            </td>
                                            <td><?= esc($activity['course_title']) ?></td>
                                            <td>
                                                <div class="progress" style="height: 6px; width: 80px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: <?= $activity['progress_percentage'] ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?= round($activity['progress_percentage']) ?>%</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php
                                                    $minutes = floor($activity['watch_time'] / 60);
                $seconds = $activity['watch_time'] % 60;
                                                    echo $minutes > 0 ? $minutes . 'm ' . $seconds . 's' : $seconds . 's';
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y g:i A', strtotime($activity['updated_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($activity['is_completed']): ?>
                                                    <span class="badge bg-success">Completed</span>
                                                <?php elseif ($activity['progress_percentage'] > 0): ?>
                                                    <span class="badge bg-warning">In Progress</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Not Started</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No recent learning activity found.</p>
                            <a href="<?= base_url('courses') ?>" class="btn btn-primary">Start Learning</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.progress-ring {
    transform: rotate(-90deg);
}

.card {
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.progress {
    background-color: #f8f9fc;
}

.progress-bar {
    background: linear-gradient(45deg, #4e73df, #36b9cc);
}

.badge {
    font-size: 0.75em;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    font-size: 0.85rem;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}
</style>
<?= $this->endSection() ?>
