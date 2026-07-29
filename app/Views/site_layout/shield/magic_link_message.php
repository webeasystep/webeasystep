<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4 p-md-5 text-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                          style="width: 64px; height: 64px; background: rgba(19,106,213,0.08); color: #136ad5; font-size: 28px;">✉️</span>

                    <h4 class="mb-2" style="font-weight: 700; color: #136ad5;"><?= lang('Auth.useMagicLink') ?></h4>

                    <p class="text-muted mb-1"><strong><?= lang('Auth.checkYourEmail') ?></strong></p>
                    <p class="text-muted"><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>

                    <a href="<?= url_to('login') ?>" class="btn btn-outline-primary mt-3"><?= lang('Auth.backToLogin') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

