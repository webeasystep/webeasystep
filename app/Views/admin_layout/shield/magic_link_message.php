<!DOCTYPE html>
<html lang="ar" dir="rtl">
<?= $this->include('admin_layout/header'); ?>
<!-- render Head here -->
<?= $this->renderSection('header') ?>
<!-- Head -->
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <?= setting('App.siteName'); ?>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <h5 class="card-title mb-5"><?= lang('Auth.useMagicLink') ?></h5>

            <p><b><?= lang('Auth.checkYourEmail') ?></b></p>

            <p><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
</body>
</html>


