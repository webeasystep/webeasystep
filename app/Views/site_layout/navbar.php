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
                    <a href="mailto:support@fakhrcs.com" class="small mr-3 p-2" dir="ltr" aria-label="راسلنا على support@fakhrcs.com">
                        <span class="icon-envelope mr-2"></span>
                        <span class="d-none d-lg-inline-block">support@fakhrcs.com</span>
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
                <?php
                $currentUser = auth()->user();
                $isLoggedIn = auth()->loggedIn();
                $isInstructor = $isLoggedIn && \App\Libraries\UserType::isInstructor($currentUser);
                $isStudent = ! $isInstructor;
                ?>
                <!-- Main Menu -->
                <ul class="js-clone-nav d-none d-lg-inline-block site-menu">
                    <li <?= current_url() == site_url() ? 'class="active"' : '' ?>>
                        <a href="<?= site_url() ?>">الرئيسية</a>
                    </li>
                    <li <?= str_contains(urldecode(current_url()), 'السنة-الاولى-المشتركة') || str_contains(current_url(), 'preparatory') ? 'class="active"' : '' ?>>
                        <a href="<?= site_url('الجامعة-السعودية-الالكترونية-السنة-الاولى-المشتركة-التحضيرية') ?>">السنة الأولى المشتركة (التحضيرية)</a>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <!-- User Navigation Links -->
                        <li class="has-children">
                            <a href="#">
                                <span>مرحباً، <?= esc($currentUser->full_name ?? $currentUser->username) ?></span>
                                <?php if (!empty($currentUser->avatar)): ?>
                                    <img src="<?= base_url('writable/uploads/profile/' . esc($currentUser->avatar)) ?>"
                                         alt="Avatar" class="rounded-circle ml-1"
                                         style="width: 25px; height: 25px; object-fit: cover; border: 1px solid #fff; display: inline-block; vertical-align: middle;">
                                <?php else: ?>
                                    <i class="icon-user ml-1" style="vertical-align: middle;"></i>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown">
                                <?php if ($isInstructor): ?>
                                    <li>
                                        <a href="<?= site_url('instructor/dashboard') ?>">
                                            <span class="icon-grid mr-2"></span>لوحة المحاضر
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= site_url('instructor/courses') ?>">
                                            <span class="icon-book mr-2"></span>المقررات
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= site_url('instructor/orders') ?>">
                                            <span class="icon-receipt mr-2"></span>الطلبات
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= site_url('instructor/faq') ?>">
                                            <span class="icon-question mr-2"></span>الأسئلة الشائعة
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a href="<?= site_url('enrollments/my-courses') ?>">
                                            <span class="icon-book mr-2"></span>مقرراتي
                                        </a>
                                    </li>
                                <?php endif; ?>
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

                    <li class="has-children <?= (str_contains(current_url(), 'student-benefits') || str_contains(current_url(), 'terms-conditions')) ? 'active' : '' ?>">
                        <a href="#">صفحات أخرى</a>
                        <ul class="dropdown">
                            <li <?= str_contains(current_url(), 'student-benefits') ? 'class="active"' : '' ?>>
                                <a href="<?= site_url('student-benefits') ?>">
                                    <span class="icon-book mr-1"></span>مميزات الاشتراك
                                </a>
                            </li>
                            <li <?= str_contains(current_url(), 'faqs') ? 'class="active"' : '' ?>>
                                <a href="<?= site_url('faqs') ?>">
                                    <span class="icon-question mr-1"></span>الأسئلة الشائعة
                                </a>
                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#courseRequestModal" style="font-weight: bold;">
                                    <span class="icon-pencil mr-1"></span>اطلب مقرر
                                </a>
                            </li>
                            <li <?= str_contains(current_url(), 'terms-conditions') ? 'class="active"' : '' ?>>
                                <a href="<?= site_url('terms-conditions') ?>">
                                    <span class="icon-document mr-1"></span>الشروط والأحكام
                                </a>
                            </li>
                        </ul>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <li style="margin-right: auto; padding-right: 20px;">
                            <style>
                                .cart-link {
                                    position: relative !important;
                                    display: inline-block !important;
                                    overflow: visible !important;
                                }
                                .cart-badge {
                                    position: absolute !important;
                                    top: -8px !important;
                                    right: -12px !important;
                                    font-size: 0.75rem !important;
                                    border-radius: 50% !important;
                                    padding: 4px 6px !important;
                                    min-width: 20px !important;
                                    text-align: center !important;
                                    z-index: 1050 !important;
                                    background-color: #dc3545 !important;
                                    color: #ffffff !important;
                                    visibility: visible !important;
                                    opacity: 1 !important;
                                }
                                .cart-badge.d-none-important {
                                    display: none !important;
                                }
                                .cart-badge.d-inline-important {
                                    display: inline-block !important;
                                }
                            </style>
                            <a href="<?= site_url('cart') ?>" class="cart-link" style="font-size: 1.5rem; color: #5c7cfa;">
                                <i class="icon-shopping-cart"></i>
                                <?php 
                                    $cartModel = new \Modules\Cart\Models\CartModel();
                                    $cartCount = $cartModel->getCartCount($currentUser->id);
                                    $displayClass = $cartCount > 0 ? 'd-inline-important' : 'd-none-important';
                                ?>
                                <span class="cart-badge badge badge-danger <?= $displayClass ?>"><?= $cartCount > 0 ? $cartCount : '' ?></span>
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
