<?php
/**
 * Progress Admin Index View
 * 
 * This view displays the main progress management interface for administrators.
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
                    <h1 class="m-0"><?= lang('Progress.progress_management') ?></h1>
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
        <!-- Statistics Cards -->
        <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total-users"><?= $stats['total_users'] ?? 0 ?></h3>
                            <p>الطلاب النشطين</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?= ADMIN_URL . 'users' ?>" class="small-box-footer">
                            <?= lang('Admin.more_info') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="total-enrollments"><?= $stats['total_enrollments'] ?? 0 ?></h3>
                            <p>الاشتراكات المفعلة بالكورسات</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <a href="<?= ADMIN_URL . 'enrollments' ?>" class="small-box-footer">
                            <?= lang('Admin.more_info') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="completed-units"><?= $stats['completed_units'] ?? 0 ?></h3>
                            <p><?= lang('Progress.completed_units') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            <?= lang('Admin.more_info') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="completion-rate"><?= number_format($stats['completion_rate'] ?? 0, 1) ?>%</h3>
                            <p>متوسط الإنجاز العام</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            <?= lang('Admin.more_info') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        <!-- Progress Analytics Chart -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= lang('Progress.progress_analytics') ?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="progressChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= lang('Progress.recent_activities') ?></h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($recent_activities)): ?>
                                <?php foreach ($recent_activities as $activity): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?= esc($activity['username']) ?></strong><br>
                                                <small class="text-muted"><?= esc($activity['unit_title']) ?></small>
                                            </div>
                                            <span class="badge badge-success"><?= lang('Progress.completed') ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="list-group-item text-center text-muted">
                                    <?= lang('Progress.no_recent_activities') ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= lang('Progress.progress_tracking') ?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" onclick="exportProgress()">
                                <i class="fas fa-download"></i> <?= lang('Progress.export_progress') ?>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="jq-table" class="table table-bordered table-striped">
                            <thead>
                                <!-- Headers will be generated dynamically by DtTable -->
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->include('admin_layout/index_js') ?>

<!-- Progress Chart Script -->
<script>
$(document).ready(function() {
    // Custom column rendering for progress-specific columns
    // Note: These would need to be implemented in the backend controller
    // using DtTable::changeColumn() method for proper integration

    // Initialize Progress Chart
    initProgressChart();
});

function initProgressChart() {
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_data['labels'] ?? []) ?>,
            datasets: [{
                label: '<?= lang('Progress.completion_rate') ?>',
                data: <?= json_encode($chart_data['completion_rates'] ?? []) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
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
}

function viewProgress(id) {
    window.location.href = '<?= base_url('admin/progress/view/') ?>' + id;
}

function resetProgress(id) {
    if (confirm('<?= lang('Progress.confirm_reset') ?>')) {
        $.post('<?= base_url('admin/progress/reset/') ?>' + id, function(response) {
            if (response.success) {
                dt_table.ajax.reload();
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        });
    }
}

function exportProgress() {
    window.location.href = '<?= base_url('admin/progress/export') ?>';
}
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('admin/plugins/chart.js/Chart.min.js') ?>"></script>
<?= $this->endSection() ?>