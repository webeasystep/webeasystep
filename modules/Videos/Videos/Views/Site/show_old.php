<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<!--Banner-->
<main>
    <div class="container">
        <p class="video_details_path">
            <img src="<?= base_url() ?>site/img/li_arrow-right.png" />
            <?= lang('Site.our_videos') ?>:
            <span><?= $video['title_'.lang('Site.lang')] ?></span>
        </p>

        <div class="row">
            <div class="col-md-6">
                <div class="video_description_images">
                    <div class="owl-carousel owl-theme" id="video_details_banner">
                        <?php foreach ($video_images as $image) {
                            ?>
                            <div class="item">
                                <a href="<?= base_url($image['full_path']) ?>" title="My Caption">
                                    <img src="<?= base_url($image['full_path']) ?>" alt="Alternate Text" class="img-reponsive" />
                                </a>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="video_description">
                    <span><?= lang("Videos.desc") ?></span>
                    <p><?= $video['desc_'.lang('Site.lang')] ?> </p>

                    <span><?= lang("Videos.features") ?></span>

                    <ul>
                        <?php if (!empty($video_features)) {
                            foreach ($video_features as $feature): ?>
                                <li>
                                    <img src="<?= base_url('site/img/true.png') ?>" alt="Feature Icon" />
                                    <?= $feature['feature_'.lang('Site.lang')]; ?>
                                </li>
                            <?php endforeach;
                        } ?>
                    </ul>
                    <br>
                    <a style="background-color: #f49146 ; color: #Fff;" class="btn btn-warning"
                       href="<?= base_url('contact_us') ?>"><?= lang("Site.request_quote") ?></a>
                </div>
            </div>
        </div>

        <!--Video-->
        <section>
            <iframe src="https://www.youtube.com/embed/kLuqCtnKr_8?si=bPDVO67zu3xVeCVj" style="width:100%;height:500px" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </section>
        <!--Video-->

    </div>
</main>

<?= $this->endSection(); ?>

<!-- LightGallery-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/zoom/lg-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/thumbnail/lg-thumbnail.min.js"></script>
<!-- LightGallery-->

<link rel="stylesheet" href="<?= base_url('admin/plugins/fireuploader/fireupload.css') ?>">

<script>
    // Initialize LightGallery
    lightGallery(document.getElementById('video_details_banner'), {
        plugins: [lgZoom, lgThumbnail],
        speed: 500,
        selector: 'a',
    });

    let imgs = $('#video_details_banner .item img');
    /*video details banner */
    $('#video_details_banner').owlCarousel({
        margin: 0,
        navText: ['<img src="<?= base_url('site/img/li_arrow-left.png') ?>"></img>', '<img src="<?= base_url('site/img/li_arrow-right.png') ?>"></img>'],
        nav: true,
        loop: true,
        dots: true,
        rtl: ($('body').hasClass('rtl') ? true : false),
        responsive: {
            0: {
                items: 1
            }
        }
    })

    let dots = $('#video_details_banner .owl-dots button');

    for (var i = 0; i < dots.length; i++) {
        dots[i].innerHTML = (imgs[i].cloneNode().outerHTML)
    }
</script>
