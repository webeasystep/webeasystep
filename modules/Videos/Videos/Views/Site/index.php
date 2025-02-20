<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<main id="containt_page">
    <!--Start Banner-->
    <section class="main_banner">
        <img src="<?= base_url('site/imgs/galary_banner.png'); ?>" alt="Gallery Banner" />
    </section>

    <div class="main_banner_data">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="pageTitle text-center">
                        <h6><?= lang('Videos.ourProduct'); ?></h6>
                        <h4><?= lang('Videos.title'); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Banner-->

    <!--Start Gallery-->
    <section class="galary">
        <div class="container">
            <div class="row">
                <?php if (!empty($videos)): ?>
                    <?php foreach ($videos as $video): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <!-- Video Container: initially shows the thumbnail + play icon -->
                                <div id="videoContainer<?= $video['id'] ?>" class="position-relative">
                                    <!-- Thumbnail image -->
                                    <img src="<?= base_url($video['image']) ?>"
                                         class="card-img-top"
                                         alt="<?= esc($video['title_en']) ?>">

                                    <!-- Play icon overlay -->
                                    <div class="play-button"
                                         onclick="playVideo('<?= esc($video['code']) ?>', 'videoContainer<?= $video['id'] ?>')"
                                         style="
                                            position: absolute;
                                            top: 50%; left: 50%;
                                            transform: translate(-50%, -50%);
                                            cursor: pointer;">
                                        <!-- Use your own icon image or a FontAwesome icon -->
                                        <img src="<?= base_url('site/imgs/play.png') ?>"
                                             alt="Play"
                                             style="width: 64px; height: 64px;">
                                    </div>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($video['title_en']) ?></h5>
                                    <p class="card-text"><?= esc($video['desc_en']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No videos available.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!--End Gallery-->

    <!--Start Contact Us-->
    <section class="contact_us">
        <div class="contact_us_article">
            <div>
                <h2><?= lang('Site.contact_us'); ?></h2>
                <p><?= lang('Site.contact_desc'); ?></p>
                <div>
                    <input type="text" name="name" value="" placeholder="<?= lang('Site.enter_email'); ?>" />
                    <button><?= lang('Site.send'); ?></button>
                </div>
            </div>
        </div>
        <div class="hidden-xs hidden-sm hidden-md">
            <img src="<?= base_url('site/imgs/contactus_img.png'); ?>" alt="<?= lang('Videos.contactImageAlt'); ?>" />
        </div>
    </section>
    <!--End Contact Us-->
</main>

<!-- Inline JavaScript to handle play on click -->
<script>
    function playVideo(code, containerId) {
        const container = document.getElementById(containerId);
        // Replace the thumbnail + icon with an autoplaying iframe
        container.innerHTML = `
    <iframe width="100%" height="315"
            src="https://www.youtube.com/embed/${code}?autoplay=1"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen>
    </iframe>
  `;
    }
</script>

<?= $this->endSection(); ?>
