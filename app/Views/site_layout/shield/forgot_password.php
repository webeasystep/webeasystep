<?= $this->extend('site_layout/template') ?>
<?= $this->section('content') ?>

<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-center mb-4">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="img-fluid" style="max-height: 80px;">
            </div>
            <h4 class="card-title text-center mb-4 w-100" style="font-weight: 700;"><?= lang('Auth.forgotPassword') ?></h4>

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
                <div class="alert alert-success text-center" role="alert"><?= session('message') ?></div>
            <?php endif ?>

            <p class="text-muted mb-4 text-center">أدخل بريدك الإلكتروني المسجل لدينا وسنرسل لك رمزاً لإعادة تعيين كلمة المرور.</p>

            <form action="<?= site_url('forgot-password') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                    <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                    <a href="<?= site_url('login') ?>" class="btn btn-light btn-block border">العودة لتسجيل الدخول</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
