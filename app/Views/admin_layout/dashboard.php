<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div id="message" data-message="<?= session()->getFlashdata('message') ?>"></div>
    
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">نظرة عامة على المنصة</h4>
        </div>

        <!-- 1. Members -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3><?= esc($users) ?></h3>
                    <p>إجمالي الأعضاء</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="<?= ADMIN_URL . 'users' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- 2. Enrollments -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3><?= esc($tb_course_enrollments) ?></h3>
                    <p>الاشتراكات (Enrollments)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="<?= ADMIN_URL . 'enrollments' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- 3. Videos -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3><?= esc($videos_count) ?></h3>
                    <p>الفيديوهات التعليمية</p>
                </div>
                <div class="icon">
                    <i class="fas fa-video"></i>
                </div>
                <a href="<?= ADMIN_URL . 'courses' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- 4. Total Revenue -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>$<?= esc(number_format($total_revenue ?? 0, 2)) ?></h3>
                    <p>إجمالي الأرباح</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="<?= ADMIN_URL . 'units/payments' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12">
            <h4 class="mb-3">إحصائيات إضافية هامة</h4>
        </div>

        <!-- Pending Payments -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-secondary">
                <div class="inner">
                    <h3><?= esc($pending_payments ?? 0) ?></h3>
                    <p>طلبات دفع معلقة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <a href="<?= ADMIN_URL . 'units/payments' ?>" class="small-box-footer">
                    مراجعة الطلبات <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-primary">
                <div class="inner">
                    <h3><?= esc($tb_courses) ?></h3>
                    <p>إجمالي الكورسات</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="<?= ADMIN_URL . 'courses' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Articles -->
        <div class="col-lg-4 col-12">
            <div class="small-box bg-gradient-dark">
                <div class="inner">
                    <h3><?= esc($articles) ?></h3>
                    <p>المقالات المنشورة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="<?= ADMIN_URL . 'articles' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
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
