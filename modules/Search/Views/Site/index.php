<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<!--Banner-->
<div class="article-banner" style="background-color: #700036 ">
    <div class="container">
        <a href="<?= site_url() ?>"> <img src="<?= base_url(); ?>site/imgs/emptyLogo.png"/></a>
        <h1>تواصل معنا</h1>
    </div>
</div>
<!--Banner-->
<!--form-->
<div class="container">

    <div class="contact-info">
        <h1>للتواصل</h1>

        <div class="row">
            <div class="col-sm-12">
                <h2><i class="fa-solid fa-location-dot"></i>&nbsp;<?= setting('App.contactPhones'); ?></h2>
                <h2><i class="fa-solid fa-phone"></i>&nbsp; <?= setting('App.contactAddress'); ?></h2>
                <h2><i class="fa-solid fa-envelope"></i>&nbsp;<?= setting('App.contactEmail'); ?></h2>
            </div>
        </div>
    </div>

    <div class="contact-form">
        <h1><?= lang("Search.Search") ?></h1>
        <?= $this->include('site_layout/site_msg'); ?>
        <form method="post" enctype="multipart/form-data" dir="<?= lang("Site.dir"); ?>">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>
            <div class="form-group">
                <label for="name"><?= lang("Search.name"); ?></label>
                <input type="text" value="<?= set_value('name'); ?>" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="email"><?= lang("Search.email"); ?></label>
                <input type="email" value="<?= set_value('email'); ?>" name="email" class="form-control" placeholder="">
            </div>
            <div class="form-group">
                <label for="phone">رقم <?= lang("Search.phone"); ?></label>
                <input type="text" value="<?= set_value('phone'); ?>" name="phone" class="form-control" placeholder="">
            </div>


            <div class="form-group">
                <label for="title"><?= lang("Search.subject"); ?></label>
                <input type="text" value="<?= set_value('subject'); ?>" name="subject" class="form-control"
                       placeholder="">
            </div>
            <div class="form-group">
                    <textarea class="form-control" name="message" rows="4"
                              placeholder="<?= lang("Search.message"); ?>"><?= set_value('message'); ?></textarea>
            </div>

<!--
            <div class="form-group">
                <script src='https://www.google.com/recaptcha/api.js?hl=<?php /*echo lang('lang'); */?>'></script>
                <div class="g-recaptcha" data-sitekey="6Lf-PxgTAAAAAMmHv6fUTQ7-oiE_lKgNzaqSOXQ8"></div>
            </div>
-->
            <div class="form-group">
                <button type="reset" class="btn btn-default"><?= lang("Admin.reset"); ?></button>
                <button type="submit" class="btn btn-primary"><?= lang("Admin.send_msg"); ?></button>
            </div>

        </form>
    </div>
</div>


<?= $this->endSection(); ?>
