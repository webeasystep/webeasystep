<div class="site-mobile-menu">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
            <span class="icofont-close js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>


<!-- navbar -->
<!-- app/Views/layouts/navbar.php -->

<nav class="site-nav mb-5">
    <?php
    // If you want to show a logo only on homepage:
    if (current_url() == site_url()) {
        echo '<a href="'.site_url().'" class="logo menu-absolute m-0 d-inline-block">
              <img src="'.base_url('site/images/logo.png').'" alt="Site Logo" class="rounded-circle logo-image" style="object-fit:contain;">
            </a>';
    }
    ?>

    <!-- Top Bar -->
    <div class="pb-2 top-bar mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-lg-9">
                    <!-- “Have a question?” -->
                    <a href="#" class="small mr-3">
                        <span class="icon-question-circle-o mr-2"></span>
                        <span class="d-none d-lg-inline-block">هل لديك استفسار ؟</span>
                    </a>
                    <!-- Phone -->
                    <a href="tel://201032863861" class="small mr-3" dir="ltr">
                        <span class="icon-phone mr-2"></span>
                        <span class="d-none d-lg-inline-block">+201032863861</span>
                    </a>
                    <!-- Email -->
                    <a href="mailto:info@mydomain.com" class="small mr-3" dir="ltr">
                        <span class="icon-envelope mr-2"></span>
                        <span class="d-none d-lg-inline-block">info@webeasystep.com</span>
                    </a>
                </div>

                <!-- Top Right: Logged in or not -->
                <div class="col-6 col-lg-3 text-right">
                    <?php if (isset(auth()->user()->username)): ?>
                        <!-- Example: If user is logged in -->
                        <a href="<?= site_url('courses/my_courses') ?>" class="small mr-3">
                            <span class="icon-user"></span>
                            <?= esc(auth()->user()->username) ?>
                        </a>
                        <a href="<?= site_url('site/logout') ?>" class="small">
                            <span class="icon-lock"></span> تسجيل الخروج
                        </a>
                    <?php else: ?>
                        <!-- If not logged in -->
                        <a href="<?= site_url('site/login') ?>" class="small mr-3">
                            <span class="icon-lock"></span>تسجيل الدخول
                        </a>
                        <a href="<?= site_url('site/register') ?>" class="small">
                            <span class="icon-person"></span>حساب جديد
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <div class="sticky-nav js-sticky-header">
        <div class="container position-relative">
            <div class="site-navigation text-center">
                <!-- Main Menu -->
                <ul class="js-clone-nav d-none d-lg-inline-block site-menu">
                    <li <?= current_url() == site_url() ? 'class="active"' : '' ?>>
                        <a href="<?= site_url() ?>">الرئيسية</a>
                    </li>
                    <li <?= current_url() == site_url('about') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('pages/about') ?>">عن الموقع</a>
                    </li>
                    <li <?= current_url() == site_url('staff') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('courses') ?>">الكورسات</a>
                    </li>
                    <li <?= current_url() == site_url('news') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('articles') ?>">المدونة</a>
                    </li>
                </ul>

                <!-- Example: Show a special button if on homepage -->
                <?php if (current_url() == site_url()): ?>
                    <a href="#bookNow" class="btn-book btn btn-secondary btn-sm menu-absolute">Book a Free Lesson</a>
                <?php endif; ?>

                <!-- Mobile Toggle -->
                <a href="#"
                   class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light"
                   data-toggle="collapse" data-target="#main-navbar">
                    <span></span>
                </a>
            </div>
        </div>
    </div>
</nav>
<a href="https://wa.me/201032863861" class="whatsapp-float" target="_blank" rel="noopener">
    <i class="fab fa-whatsapp"></i> <span style="padding: .2rem;">لديك استفسار؟  تحدث</span>
</a>
<?php if (current_url() != site_url()): ?>
    <style>
        /* ===== NAV & TOP BAR ===== */
        .site-nav {
            position: relative !important;
            background: #136ad5 !important; /* brand color */
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding-bottom: 60px; /* more bottom padding for a balanced header */
        }
        .site-nav a {
            color: #fff !important;
        }
        .top-bar {
            background-color: #136ad5;
            border-bottom: none;
            padding: 10px 0;
        }
        .top-bar a {
            color: #fff !important;
        }

        /* ===== MAIN SECTION ===== */
        .untree_co-section.bg-light {
            padding-top: 60px;
            padding-bottom: 60px;
        }
    </style>
<?php endif; ?>
