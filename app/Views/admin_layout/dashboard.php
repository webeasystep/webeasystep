<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div id="message" data-message="<?= session()->getFlashdata('message') ?>"></div>
    <div class="row mb-2">

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-orange">
                <div class="inner">
                    <h3><?= esc($articles) ?></h3>
                    <p><?= lang("Admin.articles") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-file"></i>
                </div>
                <a href="<?= ADMIN_URL . 'articles' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-green">
                <div class="inner">
                    <h3><?= esc($tb_courses) ?></h3>
                    <p><?= lang("Admin.courses") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-book"></i>
                </div>
                <a href="<?= ADMIN_URL . 'courses' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-cyan">
                <div class="inner">
                    <h3><?= esc($tb_plans) ?></h3>
                    <p><?= lang("Admin.plans") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-list"></i>
                </div>
                <a href="<?= ADMIN_URL . 'plans' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-blue">
                <div class="inner">
                    <h3><?= esc($tb_payments) ?></h3>
                    <p><?= lang("Admin.payments") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-money-bill"></i>
                </div>
                <a href="<?= ADMIN_URL . 'payments' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-gray">
                <div class="inner">
                    <h3><?= esc($tb_subscriptions) ?></h3>
                    <p><?= lang("Admin.subscriptions") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-user-plus"></i>
                </div>
                <a href="<?= ADMIN_URL . 'subscriptions' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-yellow">
                <div class="inner">
                    <h3><?= esc($users) ?></h3>
                    <p><?= lang("Admin.users") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-users"></i>
                </div>
                <a href="<?= ADMIN_URL . 'users' ?>" class="small-box-footer">
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
