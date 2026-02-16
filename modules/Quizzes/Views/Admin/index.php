<?php
/**
 * Quizzes Admin Index View
 *
 * This view displays the main quiz management interface for administrators.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Quizzes.quiz_management') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Quizzes.quizzes') ?></li>
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
                            <h3 id="total-quizzes">0</h3>
                            <p><?= lang('Quizzes.total_quizzes') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="total-attempts">0</h3>
                            <p><?= lang('Quizzes.total_attempts') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="average-score">0%</h3>
                            <p><?= lang('Quizzes.average_score') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="pass-rate">0%</h3>
                            <p><?= lang('Quizzes.pass_rate') ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= lang('Quizzes.quizzes') ?></h3>
                        <div class="card-tools">
                            <a href="<?= base_url('dt_admin/quizzes/create') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> <?= lang('Quizzes.create_quiz') ?>
                            </a>
                            <a href="<?= base_url('dt_admin/quizzes/import') ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-upload"></i> <?= lang('Quizzes.import_quiz') ?>
                            </a>
                            <a href="<?= base_url('dt_admin/quizzes/attempts') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-list"></i> <?= lang('Quizzes.view_attempts') ?>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="jq-table" class="table table-bordered table-striped">
                            <thead>
                                <!-- Headers will be generated dynamically by DtTable -->
                            </thead>
                            <tbody>
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->include('admin_layout/index_js') ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Load statistics
    loadQuizStatistics();
});

function loadQuizStatistics() {
    $.get('<?= base_url('dt_admin/quizzes/statistics') ?>', function(response) {
        if (response.success) {
            $('#total-quizzes').text(response.data.total_quizzes || 0);
            $('#total-attempts').text(response.data.total_attempts || 0);
            $('#average-score').text((response.data.average_score || 0) + '%');
            $('#pass-rate').text((response.data.pass_rate || 0) + '%');
        }
    });
}

// Delete quiz function
function deleteQuiz(quizId) {
    if (confirm('<?= lang('Admin.confirm_delete') ?>')) {
        $.post('<?= base_url('dt_admin/quizzes/delete') ?>/' + quizId, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                dt_table.ajax.reload();
                loadQuizStatistics();
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        });
    }
}

// Toggle quiz status
function toggleQuizStatus(quizId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const action = newStatus === 1 ? '<?= lang('Admin.activate') ?>' : '<?= lang('Admin.deactivate') ?>';

    if (confirm('<?= lang('Admin.confirm_action') ?>')) {
        $.post('<?= base_url('dt_admin/quizzes/toggle-status') ?>/' + quizId, {
            status: newStatus,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                dt_table.ajax.reload();
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        });
    }
}
</script>
<?= $this->endSection() ?>