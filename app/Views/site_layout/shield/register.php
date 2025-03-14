
<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>

<!--Contact Info-->
<div class="container mt-5">
    <div class="row">
        <div class="card col-12 col-md-5 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-5"><?= lang('Auth.register') ?></h5>
                <?= $this->include('site_layout/site_msg'); ?>
                <form method="post">
                    <div class="contact-form">
                        <h1><?= lang('Auth.register') ?></h1>

                        <?= csrf_field() ?>

                        <p class="text-center"><?= lang('Auth.haveAccount') ?> <a
                                    href="<?= url_to('site/login') ?>"><?= lang('Auth.login') ?></a></p>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email"><?= lang('Auth.email') ?></label>
                            <input type="email" class="form-control" name="email" inputmode="email" autocomplete="email"
                                   value="<?= old('email') ?>" required/>
                        </div>

                        <!-- Username -->
                        <div class="form-group">
                            <label for="username"><?= lang('Auth.username') ?></label>
                            <input type="text" class="form-control" name="username" inputmode="text"
                                   autocomplete="username" placeholder="<?= lang('Auth.username') ?>"
                                   value="<?= old('username') ?>" required/>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password"><?= lang('Auth.password') ?></label>
                            <input type="password" class="form-control" id="password" name="password" inputmode="text"
                                   autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required/>
                        </div>

                        <!-- Password (Again) -->
                        <div class="form-group">
                            <label for="password"><?= lang('Auth.passwordConfirm') ?></label>
                            <input type="password" class="form-control" name="password_confirm" inputmode="text"
                                   autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>"
                                   required/>
                        </div>

                        <!-- Remember me -->
                        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe"
                                       name="remember" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <label class="form-check-label" for="rememberMe">
                                    <?= lang('Auth.rememberMe') ?>
                                </label>
                            </div>
                        <?php endif; ?>


                        <div class="d-grid col-12 col-md-8 mx-auto m-3">
                            <button type="submit"
                                    class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                        </div>

                        <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                            <p class="text-center mt-3"><?= lang('Auth.forgotPassword') ?> <a
                                        href="<?= site_url('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
                        <?php endif ?>

                        <?php if (setting('Auth.allowRegistration')) : ?>
                            <p class="text-center"><?= lang('Auth.needAccount') ?> <a
                                        href="<?= site_url('register') ?>"><?= lang('Auth.register') ?></a></p>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--form-->
<?= $this->endSection() ?>
