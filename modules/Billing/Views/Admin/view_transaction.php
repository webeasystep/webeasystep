<?php
/**
 * Billing Admin View Transaction Details
 * 
 * This view displays detailed information about a specific credit transaction.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= lang('Billing.transaction_details') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin/billing') ?>"><?= lang('Billing.billing') ?></a></li>
                        <li class="breadcrumb-item active"><?= lang('Billing.transaction_details') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Billing.transaction') ?> #<?= $transaction['id'] ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><?= lang('Admin.id') ?>:</strong></td>
                                            <td><?= $transaction['id'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Users.user') ?>:</strong></td>
                                            <td>
                                                <a href="<?= base_url('dt_admin/users/view/' . $transaction['user_id']) ?>">
                                                    <?= esc($transaction['username']) ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Users.email') ?>:</strong></td>
                                            <td><?= esc($transaction['email']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Billing.amount') ?>:</strong></td>
                                            <td>
                                                <span class="badge badge-lg <?= $transaction['transaction_type'] == 'spend' ? 'badge-danger' : 'badge-success' ?>">
                                                    <?= $transaction['transaction_type'] == 'spend' ? '-' : '+' ?><?= number_format($transaction['amount'], 2) ?> <?= lang('Billing.credits') ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Billing.transaction_type') ?>:</strong></td>
                                            <td>
                                                <?php
                                                $badges = [
                                                    'purchase' => 'success',
                                                    'spend' => 'warning',
                                                    'refund' => 'info'
                                                ];
                                                $class = $badges[$transaction['transaction_type']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $class ?>"><?= ucfirst($transaction['transaction_type']) ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Admin.status') ?>:</strong></td>
                                            <td>
                                                <?php
                                                $statusBadges = [
                                                    'completed' => 'success',
                                                    'pending' => 'warning',
                                                    'failed' => 'danger',
                                                    'cancelled' => 'secondary'
                                                ];
                                                $statusClass = $statusBadges[$transaction['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $statusClass ?>"><?= ucfirst($transaction['status']) ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><?= lang('Admin.created_at') ?>:</strong></td>
                                            <td><?= date('Y-m-d H:i:s', strtotime($transaction['created_at'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Admin.updated_at') ?>:</strong></td>
                                            <td><?= $transaction['updated_at'] ? date('Y-m-d H:i:s', strtotime($transaction['updated_at'])) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Billing.reference_type') ?>:</strong></td>
                                            <td><?= $transaction['reference_type'] ? ucfirst(str_replace('_', ' ', $transaction['reference_type'])) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= lang('Billing.reference_id') ?>:</strong></td>
                                            <td><?= $transaction['reference_id'] ?? '-' ?></td>
                                        </tr>
                                        <?php if (isset($transaction['created_by']) && $transaction['created_by']): ?>
                                        <tr>
                                            <td><strong><?= lang('Admin.created_by') ?>:</strong></td>
                                            <td><?= lang('Admin.admin_user') ?> #<?= $transaction['created_by'] ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5><?= lang('Admin.description') ?>:</h5>
                                    <div class="alert alert-info">
                                        <?= nl2br(esc($transaction['description'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <a href="<?= base_url('dt_admin/billing') ?>" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> <?= lang('Admin.back_to_list') ?>
                            </a>
                            
                            <?php if ($transaction['status'] == 'pending'): ?>
                                <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                                    <i class="fas fa-check"></i> <?= lang('Admin.mark_completed') ?>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="updateStatus('failed')">
                                    <i class="fas fa-times"></i> <?= lang('Admin.mark_failed') ?>
                                </button>
                            <?php endif; ?>
                            
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-info" onclick="printTransaction()">
                                    <i class="fas fa-print"></i> <?= lang('Admin.print') ?>
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="exportTransaction()">
                                    <i class="fas fa-download"></i> <?= lang('Admin.export') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- User Information Card -->
                    <div class="card card-widget widget-user">
                        <div class="widget-user-header bg-info">
                            <h3 class="widget-user-username"><?= esc($transaction['username']) ?></h3>
                            <h5 class="widget-user-desc"><?= esc($transaction['email']) ?></h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="<?= base_url('admin/dist/img/user-default.png') ?>" alt="User Avatar">
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header" id="user-balance">0</h5>
                                        <span class="description-text"><?= lang('Billing.current_balance') ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="description-block">
                                        <h5 class="description-header" id="user-transactions">0</h5>
                                        <span class="description-text"><?= lang('Billing.total_transactions') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transaction Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= lang('Billing.transaction_timeline') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-inverse">
                                <div class="time-label">
                                    <span class="bg-success"><?= date('M d, Y', strtotime($transaction['created_at'])) ?></span>
                                </div>
                                <div>
                                    <i class="fas fa-plus bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> <?= date('H:i', strtotime($transaction['created_at'])) ?></span>
                                        <h3 class="timeline-header"><?= lang('Billing.transaction_created') ?></h3>
                                        <div class="timeline-body">
                                            <?= lang('Billing.transaction_type') ?>: <?= ucfirst($transaction['transaction_type']) ?><br>
                                            <?= lang('Billing.amount') ?>: <?= number_format($transaction['amount'], 2) ?> <?= lang('Billing.credits') ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($transaction['updated_at'] && $transaction['updated_at'] != $transaction['created_at']): ?>
                                <div>
                                    <i class="fas fa-edit bg-yellow"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> <?= date('H:i', strtotime($transaction['updated_at'])) ?></span>
                                        <h3 class="timeline-header"><?= lang('Admin.status_updated') ?></h3>
                                        <div class="timeline-body">
                                            <?= lang('Admin.status') ?>: <?= ucfirst($transaction['status']) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
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
<script>
$(document).ready(function() {
    // Load user statistics
    loadUserStats();
    
    function loadUserStats() {
        $.ajax({
            url: '<?= base_url('dt_admin/billing/user-stats/' . $transaction['user_id']) ?>',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#user-balance').text(response.data.balance + ' Credits');
                    $('#user-transactions').text(response.data.total_transactions);
                }
            }
        });
    }
});

function updateStatus(status) {
    if (confirm('<?= lang('Admin.confirm_status_change') ?>')) {
        $.ajax({
            url: '<?= base_url('dt_admin/billing/update-status') ?>',
            type: 'POST',
            data: {
                transaction_id: <?= $transaction['id'] ?>,
                status: status,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('<?= lang('Admin.error_occurred') ?>');
            }
        });
    }
}

function printTransaction() {
    window.print();
}

function exportTransaction() {
    window.location.href = '<?= base_url('dt_admin/billing/export-transaction/' . $transaction['id']) ?>';
}
</script>

<style>
@media print {
    .content-wrapper {
        margin: 0 !important;
    }
    .card-footer, .breadcrumb, .btn {
        display: none !important;
    }
}
</style>
<?= $this->endSection() ?>