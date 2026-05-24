<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.emailActivateTitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php $showActivationDetails = ! empty($showActivationDetails); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: rgba(19, 106, 213, 0.08); color: #136ad5; font-size: 28px;">@</span>
                        <h4 class="mb-2" style="font-weight: 700; color: #136ad5;"><?= lang('Auth.emailActivateTitle') ?></h4>
                        <p class="text-muted mb-0" style="line-height: 1.8;">
                            تم إرسال رسالة التفعيل إلى بريدك الإلكتروني. Please check your inbox to continue.
                        </p>
                    </div>

                    <?php if (session('error')) : ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif ?>

                    <div class="alert border-0 mb-4" style="background: rgba(19, 106, 213, 0.06); color: #1f2937; border-radius: 12px;">
                        <strong>تنبيه مهم | Important:</strong><br>
                        إذا لم تجد الرسالة في <strong>Inbox</strong>، برجاء فحص <strong>Spam / Junk</strong> ثم قم بوضع الرسالة في القائمة الآمنة.<br>
                        If the email is not in your <strong>Inbox</strong>, please check <strong>Spam / Junk</strong> and mark it as safe.
                    </div>

                    <?php if ($showActivationDetails) : ?>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="small text-primary fw-bold mb-2">Activation Code | كود التفعيل</div>
                                    <div class="fw-bold" style="direction: ltr; text-align: center; word-break: break-all; font-size: 1rem;">
                                        <?= esc($token ?? '') ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (! empty($activation_url)) : ?>
                                <div class="col-12">
                                    <div class="p-3 border rounded-3 bg-white">
                                        <div class="small text-primary fw-bold mb-2">Activation Link | رابط التفعيل</div>
                                        <div style="direction: ltr; text-align: left; word-break: break-all; font-size: 0.9rem;">
                                            <a href="<?= esc($activation_url) ?>" class="text-primary"><?= esc($activation_url) ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>

                        <p class="text-muted mb-3" style="line-height: 1.8;">
                            تعذر إرسال الرسالة حاليا، لذلك أظهرنا لك كود ورابط التفعيل هنا كحل بديل.<br>
                            The email could not be sent right now, so the activation code and link are shown here as a fallback.
                        </p>
                    <?php else : ?>
                        <p class="text-muted mb-3" style="line-height: 1.8;">
                            أدخل كود التفعيل فقط إذا وصلك داخل البريد الإلكتروني، أو استخدم الرابط الموجود داخل الرسالة.<br>
                            Enter the activation code only if you received it in the email, or use the activation link inside the message.
                        </p>
                    <?php endif ?>

                    <form action="<?= site_url('auth/a/verify') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   class="form-control"
                                   id="floatingTokenInput"
                                   name="token"
                                   placeholder="Activation Code"
                                   autocomplete="one-time-code"
                                   value="<?= old('token') ?>"
                                   required>
                            <label for="floatingTokenInput">Activation Code / <?= lang('Auth.token') ?></label>
                        </div>

                        <div class="d-grid gap-2 col-12 col-md-8 mx-auto">
                            <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                            <?php if ($showActivationDetails && ! empty($activation_url)) : ?>
                                <a href="<?= esc($activation_url) ?>" class="btn btn-outline-primary">Open Activation Link</a>
                            <?php endif ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
