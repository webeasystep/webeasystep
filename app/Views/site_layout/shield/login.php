<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>

<!--Contact Info-->
<div class="container mt-5">
    <div class="row">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-5"><?= lang('Auth.login') ?></h5>
                <?= $this->include('admin_layout/admin_msg'); ?>

                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                        <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                        <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                    </div>

                    <!-- Remember me -->
                    <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <?= lang('Auth.rememberMe') ?>
                            </label>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid col-12 col-md-8 mx-auto m-3">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.login') ?></button>
                    </div>


                    <?php
                    if (setting('Auth.allowRegistration')) : ?>
                        <p class="text-center"><?= lang('Auth.needAccount') ?> <a href="<?= base_url('register') ?>"><?= lang('Auth.register') ?></a></p>
                    <?php endif ?>

                </form>
            </div>
        </div>
    </div>
</div>
<!--form-->
<?= $this->endSection() ?>
