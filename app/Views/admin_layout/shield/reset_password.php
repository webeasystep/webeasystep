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
            <h5 class="card-title mb-4 text-center"><?= lang('Auth.resetYourPassword') ?></h5>
            <p class="text-muted text-center mb-4"><?= lang('Auth.enterCodeEmailPassword') ?></p>

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

            <form action="<?= url_to('dt_admin/reset_password') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc(old('token', $token ?? '')) ?>">

                <!-- Email -->
                <div class="mb-2">
                    <input type="email" class="form-control" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= esc(old('email', $email ?? '')) ?>" required />
                </div>

                <!-- Reset Token -->
                <div class="mb-4">
                    <input type="text" class="form-control" value="<?= esc($token ?? '') ?>" placeholder="<?= lang('Auth.token') ?>" readonly />
                </div>

                <!-- New Password -->
                <div class="mb-2">
                    <input type="password" class="form-control" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.newPassword') ?>" required />
                </div>

                <!-- New Password (Again) -->
                <div class="mb-5">
                    <input type="password" class="form-control" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.newPasswordRepeat') ?>" required />
                </div>

                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.resetPassword') ?></button>
                </div>

                <p class="text-center"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('dt_admin/login') ?>"><?= lang('Auth.login') ?></a></p>

            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
</body>
</html>


