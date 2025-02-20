<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/img-school-2-min.jpg');">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12">
                <div class="row justify-content-center ">
                    <div class="col-lg-6 text-center ">
                        <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">Contact Us</h1>
                        <div class="mb-5 text-white desc mx-auto" data-aos="fade-up" data-aos-delay="200">
                            <p>Another free template by <a href="https://untree.co/" target="_blank"
                                                           class="link-highlight">Untree.co</a>. Far far away, behind
                                the word mountains, far from the countries Vokalia and Consonantia, there live.</p>
                        </div>

                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300"><a href="#" class="btn btn-secondary">Explore
                                courses</a></p>

                    </div>


                </div>

            </div>

        </div> <!-- /.row -->
    </div> <!-- /.container -->

</div> <!-- /.untree_co-hero -->

<div class="untree_co-section">
    <div class="container">

        <div class="row mb-5">
            <div class="col-lg-4 mb-5 order-2 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-info">

                    <div class="address mt-4">
                        <i class="icon-room"></i>
                        <h4 class="mb-2">Location:</h4>
                        <p><?= setting('App.contactAddress'); ?></p>
                    </div>

                    <div class="open-hours mt-4">
                        <i class="icon-clock-o"></i>
                        <h4 class="mb-2">Open Hours:</h4>
                        <p>
                            Sunday-Friday:<br>
                            11:00 AM - 2300 PM
                        </p>
                    </div>

                    <div class="email mt-4">
                        <i class="icon-envelope"></i>
                        <h4 class="mb-2">Email:</h4>
                        <p><?= setting('App.contactEmail'); ?></p>
                    </div>

                    <div class="phone mt-4">
                        <i class="icon-phone"></i>
                        <h4 class="mb-2">Call:</h4>
                        <p><?= setting('App.contactPhones'); ?></p>
                    </div>

                </div>
            </div>
            <div class="col-lg-7 mr-auto order-1" data-aos="fade-up" data-aos-delay="200">
                <h1><?= lang("ContactUs.ContactUs") ?></h1>
                <?= $this->include('site_layout/site_msg'); ?>
                <form method="post" enctype="multipart/form-data" dir="<?= lang("Site.dir"); ?>">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <input type="text" value="<?= set_value('name'); ?>" name="name" class="form-control"
                                   placeholder="Your Name">

                        </div>
                        <div class="col-6 mb-3">
                            <input type="email" value="<?= set_value('email'); ?>" name="email" class="form-control"
                                   placeholder="Your Email">
                        </div>
                        <div class="col-12 mb-3">
                            <input type="text" value="<?= set_value('subject'); ?>" name="subject" class="form-control"
                                   placeholder="Your Subject">
                        </div>
                        <div class="col-12 mb-3">
                                      <textarea class="form-control" name="message" cols="30" rows="7"
                                                placeholder="<?= lang("ContactUs.message"); ?>"><?= set_value('message'); ?></textarea>
                        </div>
                        <!--
            <div class="form-group">
                <script src='https://www.google.com/recaptcha/api.js?hl=<?php /*echo lang('lang'); */ ?>'></script>
                <div class="g-recaptcha" data-sitekey="6Lf-PxgTAAAAAMmHv6fUTQ7-oiE_lKgNzaqSOXQ8"></div>
            </div>
-->
                        <div class="col-12">
                            <button type="reset" class="btn btn-default"><?= lang("reset"); ?></button>
                            <button type="submit" class="btn btn-primary"><?= lang("send_msg"); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div> <!-- /.untree_co-section -->

<?= $this->endSection(); ?>
