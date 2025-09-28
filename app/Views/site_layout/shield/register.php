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
                                    href="<?= url_to('login') ?>"><?= lang('Auth.login') ?></a></p>

                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="full_name"><?= lang('Auth.fullName') ?></label>
                            <input type="text" class="form-control" name="full_name"
                                   placeholder="<?= lang('Auth.enterFullName') ?>" value="<?= old('full_name') ?>" required/>
                        </div>

                        <!-- Mobile -->
                        <div class="form-group">
                            <label for="mobile"><?= lang('Auth.mobileNumber') ?></label>
                            <input type="text" class="form-control" name="mobile"
                                   placeholder="01xxxxxxxxx" value="<?= old('mobile') ?>" required/>
                            <small class="form-text text-muted">يجب أن يكون الرقم مصري ويبدأ بـ 010 أو 011 أو 012 أو 015 ويتكون من 11 رقم</small>
                        </div>

                        <!-- Note about parent information -->
                        <div class="form-group">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>ملاحظة:</strong> يمكنك إضافة معلومات ولي الأمر والبريد الإلكتروني لاحقاً من صفحة الإعدادات بعد التسجيل.
                            </div>
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
                                        href="<?= site_url('users/register') ?>"><?= lang('Auth.register') ?></a></p>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--form-->
<?= $this->endSection() ?>
