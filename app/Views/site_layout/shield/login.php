<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>

<div class="container mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-3 w-100" style="font-weight: 700; text-align: center !important;"><?= lang('Auth.login') ?></h4>

                    <?= $this->include('site_layout/site_msg'); ?>
                    
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

                    <form action="<?= url_to('login') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Email -->
                        <div class="form-group mb-2">
                            <label for="email" class="small mb-1"><?= lang('Auth.email') ?></label>
                            <input type="email" class="form-control form-control-sm" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required />
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-2">
                            <label for="password" class="small mb-1"><?= lang('Auth.password') ?></label>
                            <input type="password" class="form-control form-control-sm" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required />
                        </div>

                        <!-- Remember me -->
                        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <label class="form-check-label small" for="remember"><?= lang('Auth.rememberMe') ?></label>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.login') ?></button>
                             <?php if (setting('Auth.allowRegistration')) : ?>
                                <a href="<?= url_to('register') ?>" class="btn btn-light btn-block border"><?= lang('Auth.register') ?></a>
                            <?php endif ?>
                        </div>

                        <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                            <p class="text-center text-muted small mt-3"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
                        <?php else: ?>
                            <p class="text-center text-muted small mt-3"><a href="<?= site_url('forgot-password') ?>"><?= lang('Auth.forgotPassword') ?></a></p>
                        <?php endif ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
