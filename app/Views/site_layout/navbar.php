<div class="site-mobile-menu">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
            <span class="icofont-close js-menu-toggle" role="button" aria-label="Close mobile menu" tabindex="0"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>


<!-- navbar -->
<!-- app/Views/layouts/navbar.php -->

<nav class="site-nav mb-5" role="navigation" aria-label="Main navigation">
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
                        <span class="d-none d-lg-inline-block">info@msarlink.com</span>
                    </a>
                </div>

                <!-- Top Right: User Welcome Section or Login/Register -->
                <div class="col-6 col-lg-3 text-right">
                    <?php if (isset(auth()->user()->full_name)): ?>
                        <!-- User Welcome Section -->
                        <div class="user-welcome-section d-flex align-items-center justify-content-end">
                            <div class="user-info text-right mr-3">
                                <div class="welcome-message small text-light">
                                    <span class="d-none d-md-inline">مرحباً، </span>
                                    <strong><?= esc(auth()->user()->full_name ?? auth()->user()->username) ?></strong>
                                </div>
                            </div>
                            <div class="user-avatar">
                                <?php if (!empty(auth()->user()->avatar)): ?>
                                    <img src="<?= base_url('writable/uploads/profile/' . esc(auth()->user()->avatar)) ?>"
                                         alt="Profile Picture" class="rounded-circle"
                                         style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #fff;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 32px; height: 32px; border: 2px solid #fff;">
                                        <i class="icon-user text-primary" style="font-size: 16px;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- If not logged in -->
                        <a href="<?= site_url('login') ?>" class="small mr-3">
                            <span class="icon-lock"></span>تسجيل الدخول
                        </a>
                        <a href="<?= site_url('users/register') ?>" class="small">
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

                    <?php if (isset(auth()->user()->full_name)): ?>
                        <!-- User Navigation Links -->
                        <li <?= str_contains(current_url(), 'courses/my_courses') ? 'class="active"' : '' ?>>
                            <a href="<?= site_url('courses/my_courses') ?>">
                                <span class="icon-book mr-1"></span>كورساتي
                            </a>
                        </li>
            <!--            <li <?/*= str_contains(current_url(), 'quizzes/my-attempts') ? 'class="active"' : '' */?>>
                            <a href="<?/*= site_url('quizzes/my-attempts') */?>">
                                <span class="icon-clipboard mr-1"></span>اختباراتي
                            </a>
                        </li>-->
                        <li <?= str_contains(current_url(), 'enrollments/my-purchases') ? 'class="active"' : '' ?>>
                            <a href="<?= site_url('enrollments/my-purchases') ?>">
                                <span class="icon-shopping-cart mr-1"></span>مشترياتي
                            </a>
                        </li>
                        <li <?= str_contains(current_url(), 'settings') ? 'class="active"' : '' ?>>
                            <a href="<?= site_url('settings') ?>">
                                <span class="icon-cog mr-1"></span>الإعدادات
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('logout') ?>">
                                <span class="icon-lock mr-1"></span>تسجيل الخروج
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Mobile Toggle -->
                <a href="#"
                   class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light"
                   data-toggle="collapse" data-target="#main-navbar"
                   role="button" aria-label="Toggle mobile menu" aria-expanded="false">
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

        /* ===== USER WELCOME SECTION ===== */
        .user-welcome-section {
            flex-wrap: wrap;
        }

        .user-welcome-section .welcome-message {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        @media (max-width: 768px) {
            .user-welcome-section {
                flex-direction: column;
                align-items: flex-end;
            }

            .user-welcome-section .user-info {
                margin-right: 0 !important;
                margin-bottom: 5px;
            }

            .user-welcome-section .welcome-message {
                max-width: 120px;
                font-size: 0.8rem;
            }
        }

        /* ===== NAVIGATION ACCESSIBILITY ===== */
        .site-menu a {
            transition: all 0.3s ease;
            position: relative;
        }

        .site-menu a:focus {
            outline: 2px solid #fff;
            outline-offset: 2px;
        }

        .site-menu a:hover {
            transform: translateY(-1px);
        }

        /* Mobile menu accessibility */
        .site-mobile-menu .site-nav-wrap a:focus {
            outline: 2px solid #136ad5;
            outline-offset: 2px;
        }

        /* Skip to content link for screen readers */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #136ad5;
            color: white;
            padding: 8px;
            text-decoration: none;
            z-index: 1000;
        }

        .skip-link:focus {
            top: 6px;
        }

        /* ===== MAIN SECTION ===== */
        .untree_co-section.bg-light {
            padding-top: 60px;
            padding-bottom: 60px;
        }
    </style>
<?php endif; ?>
