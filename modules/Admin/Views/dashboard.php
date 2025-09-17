<?= $this->extend('admin_layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
        <div>
            <a href="<?= base_url('admin/system-health') ?>" class="btn btn-sm btn-outline-info me-2" id="systemHealthBtn">
                <i class="fas fa-heartbeat"></i> System Health
            </a>
            <a href="<?= base_url('admin/export-analytics') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-download"></i> Export Analytics
            </a>
        </div>
    </div>

    <!-- Overview Statistics Row -->
    <div class="row">
        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_users']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Courses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_courses']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Unit Enrollments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Unit Enrollments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_enrollments']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_revenue']) ?> Credits
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics Row -->
    <div class="row">
        <!-- Active Learners -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Learners (7 days)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['active_learners']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Course Completion Rate
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?= $stats['completion_rate'] ?>%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: <?= $stats['completion_rate'] ?>%"
                                             aria-valuenow="<?= $stats['completion_rate'] ?>" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Attempts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Quiz Attempts
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_quiz_attempts']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pending Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['pending_payments']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Enrollment Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Unit Enrollment Revenue (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="enrollmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Courses -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Most Popular Courses</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($course_analytics['popular_courses'])): ?>
                        <?php foreach (array_slice($course_analytics['popular_courses'], 0, 5) as $course): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= esc($course['course_title']) ?></h6>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: <?= ($course['enrollment_count'] / max(array_column($course_analytics['popular_courses'], 'enrollment_count'))) * 100 ?>%"></div>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <span class="badge badge-primary"><?= $course['enrollment_count'] ?> units</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No unit enrollment data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Row -->
    <div class="row">
        <!-- Revenue Trends -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Trends</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Registration Trends -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Registration Trends</h6>
                </div>
                <div class="card-body">
                    <div class="chart-line">
                        <canvas id="registrationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Top Users Row -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    <div class="activity-feed" style="max-height: 400px; overflow-y: auto;">
                        <?php if (!empty($recent_activities)): ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="activity-icon bg-<?= $activity['color'] ?>">
                                            <i class="<?= $activity['icon'] ?> text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ml-3">
                                        <p class="mb-1"><?= esc($activity['message']) ?></p>
                                        <small class="text-muted"><?= date('M j, Y g:i A', strtotime($activity['timestamp'])) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">No recent activities</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Active Users -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Most Active Users</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($user_analytics['active_users'])): ?>
                        <?php foreach (array_slice($user_analytics['active_users'], 0, 5) as $user): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <span class="text-white font-weight-bold">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ml-3">
                                    <h6 class="mb-1"><?= esc($user['username']) ?></h6>
                                    <p class="text-muted mb-0 small">
                                        <?= $user['completed_units'] ?> units completed •
                                        <?php
                                        $hours = floor($user['total_watch_time'] / 3600);
                                        $minutes = floor(($user['total_watch_time'] % 3600) / 60);
                                        echo $hours . 'h ' . $minutes . 'm watched';
                                        ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No user activity data</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health Modal -->
    <div class="modal fade" id="systemHealthModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">System Health Status</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="systemHealthContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
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

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.progress-sm {
    height: 0.5rem;
}

.chart-area, .chart-bar, .chart-line {
    position: relative;
    height: 300px;
}

.card {
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Unit Enrollment Revenue Chart
const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
const enrollmentData = <?= json_encode($course_analytics['enrollment_trends']) ?>;

new Chart(enrollmentCtx, {
    type: 'line',
    data: {
        labels: enrollmentData.map(item => item.month),
        datasets: [{
            label: 'Revenue (SAR)',
            data: enrollmentData.map(item => item.enrollments),
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderWidth: 2,
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

// Revenue Trends Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueData = <?= json_encode($financial_analytics['revenue_trends']) ?>;

new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: revenueData.map(item => item.month),
        datasets: [{
            label: 'Revenue',
            data: revenueData.map(item => item.revenue),
            backgroundColor: '#1cc88a',
            borderColor: '#1cc88a',
            borderWidth: 1
        }, {
            label: 'Spending',
            data: revenueData.map(item => item.spending),
            backgroundColor: '#e74a3b',
            borderColor: '#e74a3b',
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

// Registration Trends Chart
const registrationCtx = document.getElementById('registrationChart').getContext('2d');
const registrationData = <?= json_encode($user_analytics['registration_trends']) ?>;

new Chart(registrationCtx, {
    type: 'line',
    data: {
        labels: registrationData.map(item => item.month),
        datasets: [{
            label: 'New Users',
            data: registrationData.map(item => item.registrations),
            borderColor: '#36b9cc',
            backgroundColor: 'rgba(54, 185, 204, 0.1)',
            borderWidth: 2,
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

// System Health Check
document.getElementById('systemHealthBtn').addEventListener('click', function(e) {
    e.preventDefault();
    $('#systemHealthModal').modal('show');

    fetch('/admin/system-health')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const health = data.health;
                let content = '<div class="row">';

                // Database Health
                content += '<div class="col-md-4"><div class="card mb-3">';
                content += '<div class="card-header"><h6 class="mb-0">Database Health</h6></div>';
                content += '<div class="card-body">';
                content += `<span class="badge badge-${health.database.status === 'healthy' ? 'success' : 'danger'} mb-2">${health.database.status.toUpperCase()}</span>`;
                if (health.database.table_sizes) {
                    content += '<h6>Table Sizes:</h6><ul class="list-unstyled">';
                    Object.entries(health.database.table_sizes).forEach(([table, count]) => {
                        content += `<li>${table}: ${count.toLocaleString()}</li>`;
                    });
                    content += '</ul>';
                }
                if (health.database.issues.length > 0) {
                    content += '<h6>Issues:</h6><ul>';
                    health.database.issues.forEach(issue => {
                        content += `<li class="text-warning">${issue}</li>`;
                    });
                    content += '</ul>';
                }
                content += '</div></div></div>';

                // Storage Health
                content += '<div class="col-md-4"><div class="card mb-3">';
                content += '<div class="card-header"><h6 class="mb-0">Storage Health</h6></div>';
                content += '<div class="card-body">';
                content += `<span class="badge badge-${health.storage.status === 'healthy' ? 'success' : (health.storage.status === 'warning' ? 'warning' : 'danger')} mb-2">${health.storage.status.toUpperCase()}</span>`;
                if (health.storage.disk_usage) {
                    const usage = health.storage.disk_usage;
                    content += `<p><strong>Disk Usage:</strong> ${usage.used_percentage}%</p>`;
                    content += `<p><strong>Free Space:</strong> ${usage.free_space}</p>`;
                    content += `<p><strong>Total Space:</strong> ${usage.total_space}</p>`;
                }
                if (health.storage.issues.length > 0) {
                    content += '<h6>Issues:</h6><ul>';
                    health.storage.issues.forEach(issue => {
                        content += `<li class="text-warning">${issue}</li>`;
                    });
                    content += '</ul>';
                }
                content += '</div></div></div>';

                // Performance Metrics
                content += '<div class="col-md-4"><div class="card mb-3">';
                content += '<div class="card-header"><h6 class="mb-0">Performance</h6></div>';
                content += '<div class="card-body">';
                const perf = health.performance;
                content += `<p><strong>DB Query Time:</strong> ${perf.database_query_time}</p>`;
                content += `<p><strong>Memory Usage:</strong> ${perf.memory_usage}</p>`;
                content += `<p><strong>Memory Peak:</strong> ${perf.memory_peak}</p>`;
                content += `<p><strong>PHP Version:</strong> ${perf.php_version}</p>`;
                content += `<p><strong>Server Load:</strong> ${perf.server_load}</p>`;
                content += '</div></div></div>';

                content += '</div>';
                document.getElementById('systemHealthContent').innerHTML = content;
            } else {
                document.getElementById('systemHealthContent').innerHTML = '<div class="alert alert-danger">Failed to load system health data</div>';
            }
        })
        .catch(error => {
            document.getElementById('systemHealthContent').innerHTML = '<div class="alert alert-danger">Error loading system health data</div>';
        });
});
</script>
<?= $this->endSection() ?>
