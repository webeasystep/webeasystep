<?= $this->extend('site_layout/template') ?>
<?= $this->section('content') ?>

<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-5"><?= lang('Auth.resetPassword') ?></h5>

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

            <form action="<?= site_url('reset-password') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                    <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingTokenInput" name="token" inputmode="numeric" placeholder="الرمز المكون من 6 أرقام" value="<?= old('token') ?>" required>
                    <label for="floatingTokenInput">رمز التحقق</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required>
                    <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                    <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-block">تغيير كلمة المرور</button>
                    <a href="<?= site_url('login') ?>" class="btn btn-light btn-block border">العودة لتسجيل الدخول</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
