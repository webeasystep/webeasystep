<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>

<!--Registration Form-->
<div class="container py-4 my-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <div class="card auth-card">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Header Icon & Titles -->
                    <div class="text-center mb-4">
                        <div class="auth-card-icon mb-3">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="card-title fw-bold mb-1"><?= lang('Auth.register') ?></h3>
                        <p class="text-muted small mb-0">
                            <?= lang('Auth.haveAccount') ?> 
                            <a href="<?= url_to('login') ?>" class="text-primary fw-bold text-decoration-none"><?= lang('Auth.login') ?></a>
                        </p>
                    </div>
                    
                    <?= $this->include('site_layout/site_msg'); ?>
                    
                    <form method="post">
                        <?= csrf_field() ?>

                        <!-- Full Name -->
                        <div class="form-group mb-3">
                            <label for="full_name" class="form-label"><?= lang('Auth.fullName') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="full_name" id="full_name"
                                       placeholder="<?= lang('Auth.enterFullName') ?>" value="<?= old('full_name') ?>" required/>
                            </div>
                        </div>

                        <!-- Email & Mobile side-by-side -->
                        <div class="row g-3 mb-3">
                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label"><?= lang('Auth.email') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" id="email"
                                           placeholder="email@example.com" value="<?= old('email') ?>" dir="ltr" required/>
                                </div>
                            </div>

                            <!-- Mobile / الجوال -->
                            <div class="col-12 col-md-6">
                                <label for="mobile" class="form-label"><?= lang('Auth.mobileNumber') ?> <span class="text-danger">*</span></label>
                                <div class="input-group" dir="ltr">
                                    <span class="input-group-text fw-bold px-2" style="font-size: 0.85rem;">🇸🇦 +966</span>
                                    <input type="tel" class="form-control text-start" name="mobile" id="mobile"
                                           placeholder="0512345678" value="<?= old('mobile') ?>" 
                                           pattern="^(05|5)[0-9]{8}$"
                                           title="يرجى إدخال رقم جوال سعودي يبدأ بـ 05 ويتكون من 10 أرقام"
                                           maxlength="10" required/>
                                </div>
                                <div class="form-text text-muted small mt-1" style="font-size: 0.75rem;">مثال: 0512345678</div>
                            </div>
                        </div>

                        <!-- Password & Confirm Password side-by-side -->
                        <div class="row g-3 mb-3">
                            <!-- Password -->
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label"><?= lang('Auth.password') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" inputmode="text"
                                           autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required/>
                                    <button class="btn toggle-password" type="button" data-target="password" tabIndex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Password (Again) -->
                            <div class="col-12 col-md-6">
                                <label for="password_confirm" class="form-label"><?= lang('Auth.passwordConfirm') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" inputmode="text"
                                           autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required/>
                                    <button class="btn toggle-password" type="button" data-target="password_confirm" tabIndex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions Agreement -->
                        <div class="form-check mb-4 mt-2">
                            <input type="checkbox" class="form-check-input" id="agreeTerms"
                                   name="agree_terms" required <?php if (old('agree_terms')): ?> checked<?php endif ?>>
                            <label class="form-check-label small" for="agreeTerms">
                                أوافق على <a href="<?= site_url('terms-conditions') ?>" target="_blank" class="text-primary fw-bold text-decoration-none">الشروط والأحكام</a> <span class="text-danger">*</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2.5 fw-bold shadow-sm" style="border-radius: 10px; font-size: 1.05rem;">
                                <i class="fas fa-user-plus me-1"></i> <?= lang('Auth.register') ?>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            var targetId = this.getAttribute('data-target');
            var input = document.getElementById(targetId);
            var icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
