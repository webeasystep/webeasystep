<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>

<!--Registration Form-->
<div class="container mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-3" style="font-weight: 700;"><?= lang('Auth.register') ?></h4>
                    <p class="text-center text-muted small mb-3"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>" class="text-primary"><?= lang('Auth.login') ?></a></p>
                    
                    <?= $this->include('site_layout/site_msg'); ?>
                    
                    <form method="post">
                        <?= csrf_field() ?>

                        <!-- Full Name -->
                        <div class="form-group mb-2">
                            <label for="full_name" class="small mb-1"><?= lang('Auth.fullName') ?></label>
                            <input type="text" class="form-control form-control-sm" name="full_name"
                                   placeholder="<?= lang('Auth.enterFullName') ?>" value="<?= old('full_name') ?>" required/>
                        </div>
                        <!-- Email -->
                        <div class="form-group mb-2">
                            <label for="email" class="small mb-1"><?= lang('Auth.email') ?> <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" name="email" id="email"
                                   placeholder="email@example.com" value="<?= old('email') ?>" required/>
                        </div>
                        <!-- Mobile -->
                        <div class="form-group mb-2">
                            <label for="mobile" class="small mb-1"><?= lang('Auth.mobileNumber') ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="mobile" id="mobile"
                                   placeholder="<?= lang('Auth.mobileNumber') ?>" value="<?= old('mobile') ?>" 
                                   required/>
                        </div>
                        <!-- Password -->
                        <div class="form-group mb-2">
                            <label for="password" class="small mb-1"><?= lang('Auth.password') ?></label>
                            <input type="password" class="form-control form-control-sm" id="password" name="password" inputmode="text"
                                   autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required/>
                        </div>

                        <!-- Password (Again) -->
                        <div class="form-group mb-2">
                            <label for="password" class="small mb-1"><?= lang('Auth.passwordConfirm') ?></label>
                            <input type="password" class="form-control form-control-sm" name="password_confirm" inputmode="text"
                                   autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>"
                                   required/>
                        </div>

                        <!-- Terms and Conditions Agreement -->
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="agreeTerms"
                                   name="agree_terms" required <?php if (old('agree_terms')): ?> checked<?php endif ?>>
                            <label class="form-check-label small" for="agreeTerms">
                                أوافق على <a href="<?= site_url('terms-conditions') ?>" target="_blank" class="text-primary fw-bold">الشروط والأحكام</a> <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.register') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--form-->
<?= $this->endSection() ?>
