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
                        <span class="d-none d-lg-inline-block">Have a question?</span>
                    </a>
                    <!-- Phone -->
                    <a href="tel://1020123456" class="small mr-3" dir="ltr">
                        <span class="icon-phone mr-2"></span>
                        <span class="d-none d-lg-inline-block">10 20 123 456</span>
                    </a>
                    <!-- Email -->
                    <a href="mailto:info@mydomain.com" class="small mr-3" dir="ltr">
                        <span class="icon-envelope mr-2"></span>
                        <span class="d-none d-lg-inline-block">info@mydomain.com</span>
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
                        <a href="<?= site_url('logout') ?>" class="small">
                            <span class="icon-lock"></span> Log Out
                        </a>
                    <?php else: ?>
                        <!-- If not logged in -->
                        <a href="<?= site_url('login') ?>" class="small mr-3">
                            <span class="icon-lock"></span> Log In
                        </a>
                        <a href="<?= site_url('register') ?>" class="small">
                            <span class="icon-person"></span> Register
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
                        <a href="<?= site_url() ?>">Home</a>
                    </li>
                    <li <?= current_url() == site_url('about') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('about') ?>">About</a>
                    </li>
                    <li class="has-children <?= current_url() == site_url('dropdown') ? 'active' : '' ?>">
                        <a href="#">Dropdown</a>
                        <ul class="dropdown">
                            <li><a href="<?= site_url('elements') ?>">Elements</a></li>
                            <li class="has-children">
                                <a href="#">Menu Two</a>
                                <ul class="dropdown">
                                    <li><a href="#">Sub Menu One</a></li>
                                    <li><a href="#">Sub Menu Two</a></li>
                                    <li><a href="#">Sub Menu Three</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Menu Three</a></li>
                        </ul>
                    </li>
                    <li <?= current_url() == site_url('staff') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('staff') ?>">Our Staff</a>
                    </li>
                    <li <?= current_url() == site_url('news') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('news') ?>">News</a>
                    </li>
                    <li <?= current_url() == site_url('gallery') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('gallery') ?>">Gallery</a>
                    </li>
                    <li <?= current_url() == site_url('contact') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('contact') ?>">Contact</a>
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
