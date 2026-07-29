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
            <h5 class="card-title mb-5 text-center"><?= lang('Auth.resetPassword') ?></h5>

            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= $error ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session('errors') ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
                <div class="alert alert-success" role="alert"><?= session('message') ?></div>
            <?php endif ?>

            <p class="text-muted mb-4 text-center">أدخل الرمز الذي وصلك عبر البريد الإلكتروني وكلمة المرور الجديدة.</p>

            <form action="<?= url_to('dt_admin/reset_password') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <input type="email" class="form-control" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email', auth()->user()->email ?? null) ?>" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" name="token" inputmode="numeric" placeholder="الرمز المكون من 6 أرقام" value="<?= old('token', $token ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <input type="password" class="form-control" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required>
                </div>

                <div class="mb-4">
                    <input type="password" class="form-control" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-block">تغيير كلمة المرور</button>
                    <a href="<?= url_to('dt_admin/login') ?>" class="btn btn-light btn-block border">العودة لتسجيل الدخول</a>
                </div>

            </form>
        </div>
    </div>
</div>
</body>
</html>
