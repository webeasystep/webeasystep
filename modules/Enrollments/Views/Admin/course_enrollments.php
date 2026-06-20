<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4><i class="fas fa-graduation-cap me-2"></i>طلبات شراء الدورات</h4>
        </div>
        <div class="col-sm-6 text-end">
            <a href="<?= ADMIN_URL . 'enrollments' ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> العودة للاشتراكات
            </a>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="jq-table" width="100%">
                    <thead></thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<?= $this->include('admin_layout/index_js'); ?>
<script>
$(document).ready(function() {
    // Add action buttons column
    $('#jq-table').on('draw.dt', function() {
        $('#jq-table tbody tr').each(function() {
            var row = $(this);
            var status = row.find('td:contains("pending"), td:contains("approved"), td:contains("rejected"), td:contains("refunded")').text().trim();
            var id = row.data('id') || row.find('td:first').text();
            
            // Status badge styling
            row.find('td').each(function() {
                var text = $(this).text().trim();
                if (text === 'pending') {
                    $(this).html('<span class="badge bg-warning">قيد المراجعة</span>');
                } else if (text === 'approved') {
                    $(this).html('<span class="badge bg-success">مفعّل</span>');
                } else if (text === 'refunded') {
                    $(this).html('<span class="badge bg-dark">تم الاسترجاع</span>');
                } else if (text === 'rejected') {
                    $(this).html('<span class="badge bg-danger">مرفوض</span>');
                }
            });
        });
    });
});
</script>
<?php $this->endSection(); ?>
