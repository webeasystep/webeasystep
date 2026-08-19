<?php
    $defaultSiteTitle = 'منصة فخر CS | كورسات وشروحات الجامعة السعودية الإلكترونية SEU';
    $rawSiteTitle = setting('App.title') ?: $defaultSiteTitle;
    $rawPageTitle = $meta_title ?? ($title ?? null);

    if (empty($rawPageTitle) || $rawPageTitle === $rawSiteTitle) {
        $finalTitle = $rawSiteTitle;
    } elseif (
        mb_strpos($rawPageTitle, 'فخر CS') !== false ||
        mb_strpos($rawPageTitle, 'SEU') !== false ||
        mb_strpos($rawPageTitle, 'الجامعة السعودية الإلكترونية') !== false
    ) {
        $finalTitle = $rawPageTitle;
    } else {
        $finalTitle = $rawPageTitle . ' | ' . $rawSiteTitle;
    }

    $defaultDesc = 'المنصة الأولى المتخصصة لطلاب الجامعة السعودية الإلكترونية SEU. شروحات مقررات كلية الحوسبة والمعلوماتية والسنة الأولى المشتركة، تجميعات اختبارات، ملخصات وحلول واجبات للتفوق بـ A+.';
    $finalDescription = $meta_description ?? (setting('App.site_description_ar') ?: $defaultDesc);

    $defaultKeywords = 'الجامعة السعودية الإلكترونية, SEU, كورسات الجامعة السعودية الإلكترونية, شرح مواد SEU, فخر CS, كلية الحوسبة والمعلوماتية SEU, السنة الأولى المشتركة SEU, تجميعات SEU, حل واجبات SEU, ملخصات SEU, IT232, CS240, IT244, CS350, MATH 001, CS 001, ENG 001, FakhrCS';
    $finalKeywords = $meta_keywords ?? (setting('App.keywords_ar') ?: $defaultKeywords);

    $finalImage = $meta_image ?? (base_url('site/images/feature_logo.png'));
    $pageUrl = current_url();
?>
<head>

    <meta charset="UTF-8">
    <title><?= esc($finalTitle); ?></title>
    <meta name="title" content="<?= esc($finalTitle); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($finalDescription); ?>">
    <meta name="keywords" content="<?= esc($finalKeywords); ?>">
    <meta name="author" content="فخر CS - FakhrCS" />
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="language" content="Arabic">
    <meta name="geo.region" content="SA">
    <meta name="geo.placename" content="Saudi Arabia">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= esc($pageUrl); ?>">

    <!-- Open Graph / Facebook / WhatsApp / Telegram -->
    <meta property="og:type" content="<?= esc($og_type ?? 'website'); ?>" />
    <meta property="og:url" content="<?= esc($pageUrl); ?>" />
    <meta property="og:title" content="<?= esc($finalTitle); ?>" />
    <meta property="og:description" content="<?= esc($finalDescription); ?>" />
    <meta property="og:image" content="<?= esc($finalImage); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="<?= esc($finalTitle); ?>" />
    <meta property="og:site_name" content="منصة فخر CS التعليمية" />
    <meta property="og:locale" content="ar_SA" />

    <!-- Twitter Card meta tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= esc($pageUrl); ?>">
    <meta name="twitter:title" content="<?= esc($finalTitle); ?>">
    <meta name="twitter:description" content="<?= esc($finalDescription); ?>">
    <meta name="twitter:image" content="<?= esc($finalImage); ?>">
    <meta name="twitter:image:alt" content="<?= esc($finalTitle); ?>">

    <!-- Structured Data (JSON-LD Schema) for Google Rich Results -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "EducationalOrganization",
          "@id": "<?= base_url('#organization') ?>",
          "name": "فخر CS | FakhrCS",
          "alternateName": ["منصة فخر التعليمية", "Fakhr CS", "فخر لطلاب الجامعة السعودية الإلكترونية"],
          "url": "<?= base_url() ?>",
          "logo": {
            "@type": "ImageObject",
            "url": "<?= base_url('site/images/feature_logo.png') ?>"
          },
          "description": "منصة تعليمية متخصصة في تقديم شروحات ومقررات كلية الحوسبة والمعلوماتية والسنة الأولى المشتركة لطلاب الجامعة السعودية الإلكترونية SEU."
        },
        {
          "@type": "WebSite",
          "@id": "<?= base_url('#website') ?>",
          "url": "<?= base_url() ?>",
          "name": "منصة فخر CS",
          "publisher": {
            "@id": "<?= base_url('#organization') ?>"
          },
          "inLanguage": "ar-SA",
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "<?= base_url('courses/search?q={search_term_string}') ?>"
            },
            "query-input": "required name=search_term_string"
          }
        }
        <?php if (isset($course) && is_object($course) && !empty($course->course_title)): ?>
        ,{
          "@type": "Course",
          "@id": "<?= current_url() ?>#course",
          "name": <?= json_encode($course->course_title, JSON_UNESCAPED_UNICODE) ?>,
          "description": <?= json_encode(strip_tags($course->short_desc ?? $course->course_desc ?? ''), JSON_UNESCAPED_UNICODE) ?>,
          "provider": {
            "@type": "Organization",
            "name": "منصة فخر CS",
            "sameAs": "<?= base_url() ?>"
          },
          "inLanguage": "ar",
          "offers": {
            "@type": "Offer",
            "price": "<?= isset($course->course_price) ? (float)$course->course_price : 0 ?>",
            "priceCurrency": "SAR",
            "availability": "https://schema.org/InStock",
            "category": "Educational"
          }
        }
        <?php endif; ?>
      ]
    }
    </script>

    <!-- Preconnect to external resources for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Favicon & Mobile Device Compatibility (iOS / iPhone / iPad / Android) -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico'); ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon-16x16.png'); ?>" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon-32x32.png'); ?>" />
    <link rel="icon" type="image/png" sizes="48x48" href="<?= base_url('favicon-48x48.png'); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png'); ?>" />
    <link rel="apple-touch-icon-precomposed" href="<?= base_url('apple-touch-icon-precomposed.png'); ?>" />
    <meta name="apple-mobile-web-app-title" content="فخر CS" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <link rel="manifest" href="<?= base_url('site.webmanifest'); ?>" />
    <meta name="theme-color" content="#136ad5" />
    <meta name="msapplication-TileColor" content="#136ad5" />
    <meta name="application-name" content="فخر CS" />

    <!-- Critical CSS - Inline for fastest FCP/LCP -->
    <style>
        /* Critical Above-the-fold styles */
        *,
        *::before,
        *::after {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: 'Alexandria-Regular', system-ui, -apple-system, sans-serif;
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
            background-color: #fff;
            direction: rtl;
            text-align: right
        }

        .container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 15px
        }

        .text-center {
            text-align: center !important
        }

        .text-white {
            color: #fff !important
        }

        .mb-3 {
            margin-bottom: 1rem !important
        }

        .mb-4 {
            margin-bottom: 1.5rem !important
        }

        .py-5 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            text-decoration: none;
            transition: .15s
        }

        .btn-primary {
            color: #fff;
            background: #136ad5;
            border: 1px solid #136ad5
        }

        .btn-lg {
            padding: .5rem 1rem;
            font-size: 1.25rem
        }

        /* Hero section */
        .untree_co-hero {
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 550px;
            display: flex;
            align-items: center
        }

        .untree_co-hero.compact-hero,
        .untree_co-hero.compact-hero>.container>.row {
            min-height: auto !important;
            height: auto !important
        }

        .untree_co-hero.compact-hero {
            padding-top: 130px !important;
            padding-bottom: 40px !important
        }

        .untree_co-hero.overlay::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(19, 106, 213, .85)
        }

        .untree_co-hero>.container {
            position: relative;
            z-index: 2
        }

        .heading {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.4;
            color: #fff
        }

        /* Navbar placeholder */
        .site-nav {
            background: linear-gradient(135deg, #136ad5, #0d5bbd)
        }

        .top-bar {
            background: #136ad5;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .2)
        }

        .top-bar a {
            color: #fff !important;
            text-decoration: none
        }

        /* Loader */
        #overlayer {
            position: fixed;
            inset: 0;
            background: #fff;
            z-index: 9999
        }

        .loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10000
        }

        .spinner-border {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: .25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner .75s linear infinite
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg)
            }
        }

        /* Theme toggle */
        /* Theme toggle styles moved to style_rtl.css */
        /* Responsive */
        @media(max-width:991px) {
            .heading {
                font-size: 1.8rem
            }

            .untree_co-hero {
                min-height: 400px
            }
        }

        /* FORCE TOGGLE VISIBILITY (Inline to bypass cache) */
        body.dark-mode .theme-toggle-btn {
            background-color: #ffffff !important;
            /* Pure White background */
            border: 2px solid #cbd5e1 !important;
            /* Light Gray border */
        }

        body.dark-mode .theme-toggle-btn::after {
            background-color: #f59e0b !important;
            /* Amber knob */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
            left: 4px !important;
            /* Adjust position slightly */
        }

        body.dark-mode .theme-toggle-btn .theme-icon-light {
            color: #d97706 !important;
            /* Dark amber icon */
            display: inline-block !important;
            margin-right: -4px !important;
            /* Adjust icon spacing */
        }

        body.dark-mode .theme-toggle-btn .theme-icon-dark {
            display: none !important;
        }
    </style>

    <!-- Main CSS - Load async to prevent render blocking -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'"
        integrity="sha384-DOXMLfHhQkvFFp+rWTZwVlPVqdIhpDVYT9csOnHSgWQWPX0v5MCGtjCJbY6ERspU" crossorigin="anonymous">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css">
    </noscript>

    <link rel="preload" href="<?= base_url() ?>site/css/style_rtl.css?v=2.11" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?= base_url() ?>site/css/style_rtl.css?v=2.11">
    </noscript>

    <link rel="stylesheet" href="<?= base_url() ?>site/css/dark-mode-overrides.css?v=1.9">

    <!-- Fonts - with display swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" media="print"
        onload="this.media='all'">

    <!-- Icons - Load asynchronously -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <link rel="stylesheet" href="<?= base_url() ?>site/fonts/icomoon/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/fonts/flaticon/font/flaticon.css" media="print"
        onload="this.media='all'">

    <!-- Non-critical CSS - Deferred loading -->
    <link rel="stylesheet" href="<?= base_url() ?>site/css/animate.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/owl.carousel.min.css" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/owl.theme.default.min.css" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/jquery.fancybox.min.css" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" href="<?= base_url() ?>site/css/aos.css" media="print" onload="this.media='all'">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PDV5ZX7Z');</script>
    <!-- End Google Tag Manager -->
</head>
<?php
/*echo password_hash('01123303370', PASSWORD_DEFAULT)
 */ ?>
