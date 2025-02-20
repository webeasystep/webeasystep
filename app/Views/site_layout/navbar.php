<div class="site-mobile-menu">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
            <span class="icofont-close js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>

<style>
    .logo {
        display: block; /* or inline-block, depending on your layout */
        text-align: center; /* Center logo if navigation links are centered */
        padding: 10px 0; /* Adjust spacing around the logo */
        right: 100px;
        /* Add more styling here as needed */
    }

    /* Ensure sticky-nav sticks to the top */
    .sticky-nav {
        position: -webkit-sticky; /* For Safari */
        position: sticky;
        top: 0;
        z-index: 1000; /* Make sure it stays above other content */
    }
</style>

<!-- navbar -->
<nav class="site-nav mb-5">
        <?php if(current_url() == site_url()){ ?>
            <a href="<?= site_url() ?>" class="logo menu-absolute m-0 d-inline-block">
                <img src="<?= base_url() ?>site/images/logo.png" alt="MsarLink Logo" class="rounded-circle logo-image" style="object-fit: contain;">
            </a>
       <?php } ?>
    <div class="pb-2 top-bar mb-3">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-6 col-lg-9">
                    <a href="#" class="small mr-3" dir="ltr"><span class="icon-question-circle-o mr-2"></span> <span
                                class="d-none d-lg-inline-block">هل لديك أسئلة؟</span></a>
                    <a href="#" class="small mr-3" dir="ltr"><span class="icon-phone mr-2"></span> <span
                                class="d-none d-lg-inline-block">10 20 123 456</span></a>
                    <a href="#" class="small mr-3" dir="ltr"><span class="icon-envelope mr-2"></span> <span
                                class="d-none d-lg-inline-block">info@mydomain.com</span></a>
                </div>

           <!--     <div dir="ltr" class="col-6 col-lg-3 text-right">
                    <a href="login.html" class="small mr-3">
                        <span class="icon-lock"></span>
                        تسجيل الدخول
                    </a>
                    <a href="register.html" class="small">
                        <span class="icon-person"></span>
                        التسجيل
                    </a>
                </div>
-->
            </div>
        </div>
    </div>
    <div class="sticky-nav js-sticky-header">
        <div class="container position-relative">
            <div class="site-navigation text-center">


                <ul class="js-clone-nav d-none d-lg-inline-block site-menu">
                    <li <?= current_url() == site_url() ? 'class="active"' : '' ?>><a href="<?= site_url() ?>">الرئيسية</a></li>
<!--
                    <li <?/*= current_url() == site_url('articles') ? 'class="active"' : '' */?>><a href="<?/*= site_url('articles') */?>">المدونة</a></li>-->
                    <li <?= current_url() == site_url('contact_us') ? 'class="active"' : '' ?>><a href="<?= site_url('contact_us') ?>">اتصل بنا</a></li>
                    <?php if (isset(auth()->user()->username) ): ?>
                        <li>
                            <a href="<?= base_url('/exams') ?>">قائمة الاختبارات</a>
                        </li>

                    <?php else: ?>
                        <li> <a href="<?= site_url('login') ?>">تسجيل الدخول</a></li>
                    <?php endif; ?>

                    <!--                    <li class="has-children">
                        <a href="#">قائمة</a>
                        <ul class="dropdown">
                            <li><a href="elements.html">عناصر</a></li>
                            <li class="has-children">
                                <a href="#">قائمة فرعية اثنين</a>
                                <ul class="dropdown">
                                    <li><a href="#">القائمة الفرعية واحد</a></li>
                                    <li><a href="#">القائمة الفرعية اثنين</a></li>
                                    <li><a href="#">القائمة الفرعية ثلاثة</a></li>
                                </ul>
                            </li>
                            <li><a href="#">قائمة ثلاث</a></li>
                        </ul>
                    </li>
                    <li><a href="staff.html">طاقمنا</a></li>
                    <li><a href="gallery.html">معرض</a></li>-->
                </ul>
                <?php if(current_url() == site_url()){ ?>
                    <a href="#bookNow" class="btn-book btn btn-secondary btn-sm menu-absolute">احجز درس مجاني</a>

                <?php } ?>

                <a href="#"
                   class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light"
                   data-toggle="collapse" data-target="#main-navbar">
                    <span></span>
                </a>

            </div>
        </div>
    </div>
</nav>
