<head>
    <meta charset="UTF-8">
    <title><?= $meta_title ?? ($title ?? "WebEasyStep"); ?> | <?= setting('App.title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $meta_description ?? setting('App.site_description_ar'); ?>">
    <meta name="keywords" content="<?= $meta_keywords ?? setting('App.keywords_ar'); ?>">
    <meta name="author" content="webeasystep.com" />
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta property="og:url" content="<?= current_url(); ?>"/>
    <meta property="og:type" content="<?= $og_type ?? 'website'; ?>"/>
    <meta property="og:title" content="<?= $meta_title ?? ($title ?? setting('App.title')); ?>"/>
    <meta property="og:description" content="<?= $meta_description ?? setting('App.site_description_ar'); ?>"/>
    <meta property="og:image" content="<?= $meta_image ?? base_url() . 'site/images/feature_logo.png'; ?>"/>
    <meta property="og:image:width"   content="500"/>
    <meta property="og:image:height"  content="500"/>
    <meta property="og:site_name" content="webeasystep.com"/>

    <!-- Twitter Card meta tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $meta_title ?? ($title ?? setting('App.title')); ?>">
    <meta name="twitter:description" content="<?= $meta_description ?? setting('App.site_description_ar'); ?>">
    <meta name="twitter:image" content="<?= $meta_image ?? base_url() . 'site/images/feature_logo.png'; ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url(); ?>">

    <!-- Preconnect to external resources for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url(); ?>site/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= base_url(); ?>site/favicon-16x16.png" sizes="16x16" />

    <!-- Critical CSS - Inline for fastest FCP/LCP -->
    <style>
        /* Critical Above-the-fold styles */
        *,*::before,*::after{box-sizing:border-box}
        body{margin:0;font-family:'Alexandria-Regular',system-ui,-apple-system,sans-serif;font-size:1rem;line-height:1.6;color:#666;background-color:#fff;direction:rtl;text-align:right}
        .container{width:100%;max-width:1140px;margin:0 auto;padding:0 15px}
        .row{display:flex;flex-wrap:wrap;margin:0 -15px}
        .col-12{flex:0 0 100%;max-width:100%;padding:0 15px}
        .text-center{text-align:center!important}
        .text-white{color:#fff!important}
        .mb-3{margin-bottom:1rem!important}
        .mb-4{margin-bottom:1.5rem!important}
        .py-5{padding-top:3rem!important;padding-bottom:3rem!important}
        .btn{display:inline-block;font-weight:400;text-align:center;vertical-align:middle;cursor:pointer;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem;text-decoration:none;transition:.15s}
        .btn-primary{color:#fff;background:#136ad5;border:1px solid #136ad5}
        .btn-lg{padding:.5rem 1rem;font-size:1.25rem}
        /* Hero section */
        .untree_co-hero{background-size:cover;background-position:center;position:relative;min-height:550px;display:flex;align-items:center}
        .untree_co-hero.compact-hero, .untree_co-hero.compact-hero > .container > .row{min-height:auto!important;height:auto!important}
        .untree_co-hero.compact-hero{padding-top:130px!important;padding-bottom:40px!important}
        .untree_co-hero.overlay::before{content:"";position:absolute;inset:0;background:rgba(19,106,213,.85)}
        .untree_co-hero>.container{position:relative;z-index:2}
        .heading{font-size:2.5rem;font-weight:700;line-height:1.4;color:#fff}
        /* Navbar placeholder */
        .site-nav{background:linear-gradient(135deg,#136ad5,#0d5bbd)}
        .top-bar{background:#136ad5;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.2)}
        .top-bar a{color:#fff!important;text-decoration:none}
        /* Loader */
        #overlayer{position:fixed;inset:0;background:#fff;z-index:9999}
        .loader{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000}
        .spinner-border{display:inline-block;width:2rem;height:2rem;border:.25em solid currentColor;border-right-color:transparent;border-radius:50%;animation:spinner .75s linear infinite}
        @keyframes spinner{to{transform:rotate(360deg)}}
        /* Theme toggle */
        /* Theme toggle styles moved to style_rtl.css */
        /* Responsive */
        @media(max-width:991px){.heading{font-size:1.8rem}.untree_co-hero{min-height:400px}}

        /* FORCE TOGGLE VISIBILITY (Inline to bypass cache) */
        body.dark-mode .theme-toggle-btn {
            background-color: #ffffff !important; /* Pure White background */
            border: 2px solid #cbd5e1 !important; /* Light Gray border */
        }
        body.dark-mode .theme-toggle-btn::after {
            background-color: #f59e0b !important; /* Amber knob */
            box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
            left: 4px !important; /* Adjust position slightly */
        }
        body.dark-mode .theme-toggle-btn .theme-icon-light {
            color: #d97706 !important; /* Dark amber icon */
            display: inline-block !important;
            margin-right: -4px !important; /* Adjust icon spacing */
        }
        body.dark-mode .theme-toggle-btn .theme-icon-dark {
            display: none !important;
        }
    </style>

    <!-- Main CSS - Load async to prevent render blocking -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" integrity="sha384-DOXMLfHhQkvFFp+rWTZwVlPVqdIhpDVYT9csOnHSgWQWPX0v5MCGtjCJbY6ERspU" crossorigin="anonymous">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css"></noscript>

    <link rel="preload" href="<?= base_url() ?>site/css/style_rtl.css?v=2.10" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= base_url() ?>site/css/style_rtl.css?v=2.10"></noscript>

    <link rel="stylesheet" href="<?= base_url() ?>site/css/dark-mode-overrides.css?v=1.8">

    <!-- Fonts - with display swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- Icons - Load asynchronously -->
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <link rel="stylesheet" href="<?= base_url() ?>site/fonts/icomoon/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/fonts/flaticon/font/flaticon.css" media="print" onload="this.media='all'">

    <!-- Non-critical CSS - Deferred loading -->
    <link rel="stylesheet" href="<?= base_url() ?>site/css/animate.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/owl.carousel.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/owl.theme.default.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/jquery.fancybox.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/aos.css" media="print" onload="this.media='all'">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6CXJYVPP4B"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-6CXJYVPP4B');
    </script>

    <!-- Device Key Generator -->
    <script>
    (function() {
        function getOrGenerateDeviceKey() {
            let key = localStorage.getItem('fk_device_key');
            if (!key) {
                var rawStr = (navigator.userAgent || '') + (screen.width || '') + 'x' + (screen.height || '') + (navigator.language || '') + Math.random();
                var hash = 0;
                for (var i = 0; i < rawStr.length; i++) {
                    hash = ((hash << 5) - hash) + rawStr.charCodeAt(i);
                    hash |= 0;
                }
                key = 'dev_' + Math.abs(hash).toString(36) + '_' + Math.random().toString(36).substring(2, 9);
                localStorage.setItem('fk_device_key', key);
            }
            document.cookie = "fk_device_key=" + key + "; path=/; max-age=31536000; SameSite=Lax";
            return key;
        }
        getOrGenerateDeviceKey();
    })();
    </script>
</head>
<?php
/*echo password_hash('01123303370', PASSWORD_DEFAULT)
*/?>

