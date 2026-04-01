<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">إدارة الوحدات</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?= $this->include('admin_layout/admin_msg'); ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total-units">-</h3>
                            <p>إجمالي الوحدات</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="active-units">-</h3>
                            <p>الوحدات النشطة</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="preview-units">-</h3>
                            <p>وحدات المعاينة</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="units-with-quizzes">-</h3>
                            <p>وحدات مع اختبارات</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?= $selected_course_title ? 'وحدات الكورس: ' . esc($selected_course_title) : 'قائمة الوحدات' ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?= ADMIN_URL . 'units/add' . ($selected_course_id ? '?course_id=' . (int) $selected_course_id : '') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة وحدة جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($selected_course_title): ?>
                        <div class="alert alert-info">
                            يتم الآن عرض الوحدات التابعة للكورس: <strong><?= esc($selected_course_title) ?></strong>
                            (<?= (int) ($selected_course_units_count ?? 0) ?> وحدة)
                        </div>
                        <?php if ((int) ($selected_course_units_count ?? 0) === 0): ?>
                            <div class="alert alert-warning">
                                لا توجد وحدات مضافة لهذا الكورس حالياً. يمكنك إضافة أول وحدة من الزر بالأعلى.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <input type="hidden" name="course_id" class="custom-filter" value="<?= esc((string) ($selected_course_id ?? '')) ?>">

                    <!-- Units Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="jq-table" width="100%">
                            <thead></thead>
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
        loadStatistics();
    });

    function loadStatistics() {
        $.ajax({
            url: '<?= ADMIN_URL ?>units/statistics',
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            },
            success: function(stats) {
                $('#total-units').text(stats.total || 0);
                $('#active-units').text(stats.active || 0);
                $('#preview-units').text(stats.preview || 0);
                $('#units-with-quizzes').text(stats.with_quizzes || 0);
            },
            error: function(xhr, status, error) {
                console.error('فشل في تحميل الإحصائيات:', error);
            }
        });
    }
</script>
<?= $this->endSection() ?>
