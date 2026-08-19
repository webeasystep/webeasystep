<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $stats['total'] ?? 0 ?></h3>
                    <p><?= lang('CourseRequests.total_requests') ?: 'إجمالي الطلبات' ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $stats['pending'] ?? 0 ?></h3>
                    <p><?= lang('CourseRequests.pending_requests') ?: 'طلبات قيد الانتظار' ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $stats['completed'] ?? 0 ?></h3>
                    <p><?= lang('CourseRequests.completed_requests') ?: 'طلبات تم توفيرها' ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3><?= $stats['notify'] ?? 0 ?></h3>
                    <p><?= lang('CourseRequests.notify_requests') ?: 'طلبات مع إشعار' ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list ml-1"></i> <?= lang('CourseRequests.all_requests') ?: 'جميع طلبات الكورسات' ?>
            </h6>
        </div>
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="jq-table" width="100%">
                    <thead></thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Show Modal -->
    <?= $this->include('Modules\CourseRequests\Views\Admin\show'); ?>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/index_js'); ?>
<script type="text/javascript">
    $(document).on('click', '.change-status-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var status = $(this).data('status');
        
        $.ajax({
            url: '<?= base_url('dt_admin/course_requests/update_status') ?>',
            type: 'POST',
            data: {
                id: id,
                status: status,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'تم تحديث الحالة بنجاح', 'تم بنجاح');
                    }
                    if (typeof dt_table !== 'undefined') {
                        dt_table.ajax.reload(null, false);
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'حدث خطأ', 'خطأ');
                    }
                }
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('حدث خطأ في الاتصال بالخادم', 'خطأ');
                }
            }
        });
    });
</script>
<?= $this->endSection(); ?>
