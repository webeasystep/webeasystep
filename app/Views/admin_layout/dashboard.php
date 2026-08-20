<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div id="message" data-message="<?= session()->getFlashdata('message') ?>"></div>
    <div class="row mb-2">

        <!-- 1. المدفوعات المكتملة -->
        <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="small-box bg-gradient-success shadow-sm">
                <div class="inner">
                    <h3><?= esc($paid_count) ?></h3>
                    <p class="font-weight-bold" style="font-size: 16px;">المدفوعات (المكتملة)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="<?= ADMIN_URL . 'enrollments' ?>" class="small-box-footer">
                    عرض الاشتراكات <i class="fas fa-arrow-circle-left mr-1"></i>
                </a>
            </div>
        </div>

        <!-- 2. مدفوعات معلقة -->
        <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="small-box bg-gradient-warning shadow-sm">
                <div class="inner">
                    <h3><?= esc($pending_count) ?></h3>
                    <p class="font-weight-bold text-dark" style="font-size: 16px;">مدفوعات معلقة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="<?= ADMIN_URL . 'enrollments' ?>" class="small-box-footer" style="color: #1f2d3d !important;">
                    مراجعة الطلبات <i class="fas fa-arrow-circle-left mr-1"></i>
                </a>
            </div>
        </div>

        <!-- 3. الطلبة -->
        <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="small-box bg-gradient-info shadow-sm">
                <div class="inner">
                    <h3><?= esc($students_count) ?></h3>
                    <p class="font-weight-bold" style="font-size: 16px;">الطلبة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="<?= ADMIN_URL . 'students' ?>" class="small-box-footer">
                    إدارة الطلاب <i class="fas fa-arrow-circle-left mr-1"></i>
                </a>
            </div>
        </div>

        <!-- 4. المحاضرين -->
        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <div class="small-box bg-gradient-primary shadow-sm">
                <div class="inner">
                    <h3><?= esc($instructors_count) ?></h3>
                    <p class="font-weight-bold" style="font-size: 16px;">المحاضرين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="<?= ADMIN_URL . 'instructors' ?>" class="small-box-footer">
                    إدارة المحاضرين <i class="fas fa-arrow-circle-left mr-1"></i>
                </a>
            </div>
        </div>

        <!-- 5. طلبات المقررات -->
        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <div class="small-box bg-gradient-secondary shadow-sm">
                <div class="inner">
                    <h3><?= esc($course_requests_count) ?></h3>
                    <p class="font-weight-bold" style="font-size: 16px;">طلبات المقررات</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <a href="<?= ADMIN_URL . 'course_requests' ?>" class="small-box-footer">
                    عرض الطلبات <i class="fas fa-arrow-circle-left mr-1"></i>
                </a>
            </div>
        </div>

    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
<?= $this->endSection(); ?>

<?= $this->section('admin_layout/js'); ?>
<script src="<?= base_url('js/dashboard.js') ?>"></script>
<?= $this->endSection(); ?>
