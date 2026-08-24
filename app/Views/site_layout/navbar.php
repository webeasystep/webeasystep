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

<nav class="site-nav" role="navigation" aria-label="Main navigation">
    <!-- Top Bar -->
    <div class="pb-2 top-bar mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-8 col-md-9 col-lg-9 d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <!-- “Have a question?” -->
                    <a href="https://wa.me/201032863861?text=%D8%A7%D8%B3%D8%AA%D9%81%D8%B3%D8%A7%D8%B1%20%D9%85%D9%86%D8%B5%D8%A9%20fakhrcs" class="nav-topbar-wa" aria-label="تواصل معنا عبر واتساب" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" class="mr-1" style="vertical-align:middle;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        <span class="nav-topbar-wa-label">هل لديك استفسار؟</span>
                        <span class="nav-topbar-wa-cta">راسلنا الآن ↗</span>
                    </a>
                    <!-- Telegram -->
                    <a href="https://t.me/fakhrcs" class="nav-topbar-tg" aria-label="تابعنا على تيليجرام" target="_blank" rel="noopener noreferrer">
                        <i class="icon-telegram mr-1" style="vertical-align:middle;"></i>
                        <span class="nav-topbar-tg-label">قناتنا</span>
                        <span class="nav-topbar-tg-cta">انضم الآن ↗</span>
                    </a>
                    <!-- Phone -->
                    <a href="tel:+201032863861" class="small p-2 d-none d-md-inline-block" dir="ltr" aria-label="اتصل بنا على 201032863861">
                        <span class="icon-phone mr-1"></span>
                        <span class="d-none d-lg-inline-block">+201032863861</span>
                    </a>
                    <!-- Email -->
                    <a href="mailto:support@fakhrcs.com" class="small p-2 d-none d-md-inline-block" dir="ltr" aria-label="راسلنا على support@fakhrcs.com">
                        <span class="icon-envelope mr-1"></span>
                        <span class="d-none d-lg-inline-block">support@fakhrcs.com</span>
                    </a>
                </div>

                <!-- Top Right: Theme Toggle -->
                <div class="col-4 col-md-3 col-lg-3 text-right d-flex align-items-center justify-content-end">
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
            <div class="site-navigation d-flex align-items-center justify-content-between justify-content-lg-center">
                <?php
                $currentUser = auth()->user();
                $isLoggedIn = auth()->loggedIn();
                $isInstructor = $isLoggedIn && \App\Libraries\UserType::isInstructor($currentUser);
                $isStudent = ! $isInstructor;
                $cartModel = new \Modules\Cart\Models\CartModel();
                $cartCount = $isLoggedIn ? $cartModel->getCartCount($currentUser->id) : count(session()->get('guest_cart') ?? []);
                $displayClass = $cartCount > 0 ? 'd-inline-important' : 'd-none-important';
                ?>
                <!-- Mobile Brand Link -->
                <a href="<?= site_url() ?>" class="d-inline-block d-lg-none text-white font-weight-bold" style="font-size: 1.15rem; text-decoration: none; letter-spacing: -0.3px;">
                    منصة فخر <span style="color: #60a5fa;">CS</span>
                </a>

                <!-- Main Menu -->
                <ul class="js-clone-nav d-none d-lg-inline-block site-menu mb-0">
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

                    <li class="cart-nav-item <?= current_url() == site_url('cart') ? 'active' : '' ?>">
                        <a href="<?= site_url('cart') ?>" class="cart-link" aria-label="سلة المشتريات" title="سلة المشتريات">
                            <i class="icon-shopping-cart"></i>
                            <span class="cart-nav-label d-inline d-lg-none mr-2">سلة المشتريات</span>
                            <span class="cart-badge badge badge-danger <?= $displayClass ?>"><?= $cartCount > 0 ? $cartCount : '' ?></span>
                        </a>
                    </li>

                </ul>

                <!-- Mobile Actions (Cart & Toggle) -->
                <div class="d-inline-flex align-items-center d-lg-none">
                    <a href="<?= site_url('cart') ?>" class="cart-link mobile-cart-icon-btn mr-3" aria-label="سلة المشتريات" title="سلة المشتريات">
                        <i class="icon-shopping-cart"></i>
                        <span class="cart-badge badge badge-danger <?= $displayClass ?>"><?= $cartCount > 0 ? $cartCount : '' ?></span>
                    </a>
                    <a href="#"
                       class="burger site-menu-toggle js-menu-toggle d-inline-block light"
                       data-toggle="collapse" data-target="#main-navbar"
                       role="button" aria-label="Toggle mobile menu" aria-expanded="false">
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    /* ===== CART LINK & BADGE ===== */
    .cart-link {
        position: relative !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #ffffff !important;
        font-size: 1.35rem;
        padding: 6px 10px;
        transition: transform 0.2s ease, opacity 0.2s ease;
        text-decoration: none !important;
        vertical-align: middle;
    }
    .cart-link:hover {
        transform: scale(1.08);
        color: #ffffff !important;
    }
    .cart-badge {
        position: absolute !important;
        top: -4px !important;
        right: -6px !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        border-radius: 50% !important;
        padding: 2px 6px !important;
        min-width: 18px !important;
        height: 18px !important;
        line-height: 14px !important;
        text-align: center !important;
        z-index: 1050 !important;
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border: 2px solid #136ad5;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        visibility: visible !important;
        opacity: 1 !important;
    }
    .cart-badge.d-none-important {
        display: none !important;
    }
    .cart-badge.d-inline-important {
        display: inline-block !important;
    }
    .mobile-cart-icon-btn {
        font-size: 1.4rem;
        padding: 4px 8px;
        color: #ffffff !important;
    }
    body.dark-mode .cart-badge {
        border-color: #1e293b;
    }
</style>

<?php if (current_url() != site_url()): ?>
    <style>
        /* ===== NAV & TOP BAR ===== */
        .site-nav {
            position: relative !important;
            background: #136ad5 !important; /* brand color */
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding-bottom: 12px; /* compact header spacing */
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
