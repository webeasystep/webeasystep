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
    <!-- Top Bar -->
    <div class="pb-2 top-bar mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-lg-9">
                    <!-- “Have a question?” -->
                    <a href="https://wa.me/201032863861" class="small mr-3 p-2" aria-label="هل لديك استفسار - تواصل عبر واتساب" target="_blank">
                        <span class="icon-question-circle-o mr-2"></span>
                        <span class="d-none d-lg-inline-block">هل لديك استفسار ؟</span>
                    </a>
                    <!-- Phone -->
                    <a href="tel:+201032863861" class="small mr-3 p-2" dir="ltr" aria-label="اتصل بنا على 201032863861">
                        <span class="icon-phone mr-2"></span>
                        <span class="d-none d-lg-inline-block">+201032863861</span>
                    </a>
                    <!-- Email -->
                    <a href="mailto:info@webeasystep.com" class="small mr-3 p-2" dir="ltr" aria-label="راسلنا على info@webeasystep.com">
                        <span class="icon-envelope mr-2"></span>
                        <span class="d-none d-lg-inline-block">info@webeasystep.com</span>
                    </a>
                </div>

                <!-- Top Right: User Welcome Section or Login/Register -->
                <!-- Top Right: Theme Toggle -->
                <div class="col-6 col-lg-3 text-right d-flex align-items-center justify-content-end">
                    <!-- Theme Toggle - Always Visible -->
                    <button type="button" class="theme-toggle-btn ml-2" id="themeToggleTop" aria-label="Toggle dark mode" title="تبديل الوضع الداكن">
                        <i class="fas fa-sun theme-icon-light"></i>
                        <i class="fas fa-moon theme-icon-dark"></i>
                    </button>
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
                    <li <?= current_url() == site_url('courses/fundamentals') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('courses/fundamentals') ?>">كورسات الأساسيات</a>
                    </li>
               
                    <?php if (auth()->loggedIn()): ?>
                        <!-- User Navigation Links -->
                        <li class="has-children">
                            <a href="#">
                                <span>مرحباً، <?= esc(auth()->user()->full_name ?? auth()->user()->username) ?></span>
                                <?php if (!empty(auth()->user()->avatar)): ?>
                                    <img src="<?= base_url('writable/uploads/profile/' . esc(auth()->user()->avatar)) ?>"
                                         alt="Avatar" class="rounded-circle ml-1"
                                         style="width: 25px; height: 25px; object-fit: cover; border: 1px solid #fff; display: inline-block; vertical-align: middle;">
                                <?php else: ?>
                                    <i class="icon-user ml-1" style="vertical-align: middle;"></i>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown">
                                <li>
                                    <a href="<?= site_url('enrollments/my-courses') ?>">
                                        <span class="icon-book mr-2"></span>كورساتي
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= site_url('settings') ?>">
                                        <span class="icon-cog mr-2"></span>الإعدادات
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= site_url('logout') ?>">
                                        <span class="icon-lock mr-2"></span>تسجيل الخروج
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Guest Links -->
                        <li <?= str_contains(current_url(), 'login') ? 'class="active"' : '' ?>>
                            <a href="<?= site_url('login') ?>">
                                <span class="icon-lock mr-1"></span>تسجيل الدخول
                            </a>
                        </li>
                    <?php endif; ?>
                    <li <?= str_contains(current_url(), 'terms-conditions') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('terms-conditions') ?>">
                            <span class="icon-document mr-1"></span>الشروط والأحكام
                        </a>
                    </li>
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
                max-width: 150px;
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

        /* ===== DROPDOWN MENU FIX ===== */
        .site-navigation .site-menu .has-children .dropdown {
            background-color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 10px 0;
            border: 1px solid #eee;
            margin-top: 10px;
            z-index: 1002; /* Ensure it stays above other elements */
        }

        /* Override the global white text for dropdown items */
        .site-navigation .site-menu .has-children .dropdown > li > a {
            color: #333333 !important; /* Dark text for visibility */
            padding: 8px 20px;
            display: block;
            font-size: 14px;
            font-weight: 500;
        }

        .site-navigation .site-menu .has-children .dropdown > li > a:hover {
            background-color: #f8f9fa;
            color: #136ad5 !important;
            padding-right: 25px; 
        }

        .site-navigation .site-menu .has-children .dropdown > li > a span {
            color: inherit !important; /* Icons inherit text color */
        }
        
        /* Dark mode overrides */
        body.dark-mode .site-navigation .site-menu .has-children .dropdown {
            background-color: #1a202c !important;
            border-color: #2d3748;
        }
        
        body.dark-mode .site-navigation .site-menu .has-children .dropdown > li > a {
            color: #e2e8f0 !important;
        }
        
        body.dark-mode .site-navigation .site-menu .has-children .dropdown > li > a:hover {
            background-color: #2d3748;
            color: #136ad5 !important;
        }
    </style>
<?php endif; ?>
