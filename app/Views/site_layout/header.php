<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "-------"; ?> | <?= setting('App.siteName'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= setting('App.site_description'); ?>">
    <meta name="keywords" content="<?= setting('App.site_keywords'); ?>">
    <meta name="author" content="msarlink.com" />

    <!-- Facebook and Twitter integration -->
    <meta property="og:url" content="<?= current_url(); ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="<?= setting('App.siteName'); ?>"/>
    <meta property="og:site_name" content="<?= setting('App.siteName'); ?>"/>
    <meta property="og:description" content="<?= setting('App.site_description'); ?>"/>
    <link rel="icon" type="image/png" href="<?= base_url(); ?>site/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= base_url(); ?>site/favicon-16x16.png" sizes="16x16" />

    <link href="https://fonts.googleapis.com/css2?family=Display+Playfair:wght@400;700&family=Inter:wght@400;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <link rel="stylesheet" href="<?= base_url() ?>site/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css"
          integrity="sha384-DOXMLfHhQkvFFp+rWTZwVlPVqdIhpDVYT9csOnHSgWQWPX0v5MCGtjCJbY6ERspU" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/animate.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>site/fonts/icomoon/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>site/fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/aos.css">
    <link rel="stylesheet" href="<?= base_url(); ?>site/css/style_rtl.css">
    <script src="<?= base_url() ?>site/js/jquery-3.4.1.min.js"></script>
</head>
<?php
/*echo password_hash('01123303370', PASSWORD_DEFAULT)
*/?>

