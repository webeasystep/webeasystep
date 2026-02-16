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
            <h5 class="card-title mb-5 text-center"><?= lang('Auth.login') ?></h5>
            <h5 class="card-title mb-5"><?= lang('Auth.useMagicLink') ?></h5>

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

            <form action="<?= url_to('dt_admin/forget_password') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Email -->
                <div class="mb-2">
                    <input type="email" class="form-control" name="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>"
                           value="<?= old('email', auth()->user()->email ?? null) ?>" required />
                </div>

                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                </div>

            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
</body>
</html>


