<div class="site-footer" id="main-footer">
    <?php
    $currentUser = auth()->user();
    $isLoggedIn = auth()->loggedIn();
    $isInstructor = $isLoggedIn && \App\Libraries\UserType::isInstructor($currentUser);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mr-auto">
                <div class="widget">
                    <h3>معلومات عنا<span class="text-primary">.</span></h3>
                    <p>المنصة الأولى المخصصة لطلبة الجامعة السعودية الإلكترونية.</p>
                </div> <div class="widget">
                    <h3>تواصل معنا</h3>
                    <ul class="list-unstyled social">
                        <li><a href="https://www.youtube.com/" target="_blank" title="يوتيوب" aria-label="تابعنا على يوتيوب"><span class="icon-youtube"></span></a></li>
                        <li><a href="https://wa.me/201032863861" target="_blank" title="+201032863861" aria-label="تواصل عبر واتساب"><span class="icon-whatsapp"></span></a></li>
                        <li><a href="https://t.me/fakhrcs" target="_blank" title="@fakhrcs" aria-label="تابعنا على تيليجرام"><span class="icon-telegram"></span></a></li>
                        <li><a href="https://www.facebook.com/khtwasahla" target="_blank" title="تابعنا عبر فيسبوك" aria-label="تابعنا عبر فيسبوك"><span class="icon-facebook"></span></a></li>
                        <li><a href="https://x.com/fakhrcs" target="_blank" title="تابعنا عبر منصة إكس" aria-label="تابعنا عبر منصة إكس"><span><svg viewBox="0 0 24 24" style="width: 1em; height: 1em; fill: currentColor;"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg></span></a></li>
                    </ul>
                </div> </div> 
                <div class="col-lg-4 ml-auto">
                    <div class="widget">
                        <h3>مقررات الجامعة السعودية الإلكترونية</h3>
                        <ul class="list-unstyled float-right links">
                            <li><a href="<?= site_url('الجامعة-السعودية-الالكترونية-السنة-الاولى-المشتركة-التحضيرية') ?>">السنة الأولى المشتركة (التحضيرية)</a></li>
                            <li><a href="<?= site_url('courses') ?>">المقررات</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#courseRequestModal">اطلب مقرر</a></li>
                        </ul>
                    </div> 
                </div> 
                <div class="col-lg-2">
                    <div class="widget">
                        <h3>روابط هامة</h3>
                        <ul class="list-unstyled float-right links">
                            <?php if ($isInstructor): ?>
                                <li><a href="<?= site_url('instructor/dashboard') ?>">لوحة المحاضر</a></li>
                                <li><a href="<?= site_url('instructor/courses') ?>">المقررات</a></li>
                                <li><a href="<?= site_url('instructor/orders') ?>">الطلبات</a></li>
                                <li><a href="<?= site_url('instructor-terms') ?>">دليل الشراكة</a></li>
                                <li><a href="<?= site_url('blog') ?>">المدونة</a></li>
                            <?php else: ?>
                                <?php if ($isLoggedIn): ?>
                                    <li><a href="<?= site_url('enrollments/my-courses') ?>">مقرراتي</a></li>
                                <?php endif; ?>
                                <li><a href="<?= site_url('student-benefits') ?>">مميزات الاشتراك</a></li>
                                <li><a href="<?= site_url('blog') ?>">المدونة</a></li>
                                <li><a href="<?= site_url('faqs') ?>">الأسئلة الشائعة</a></li>
                                <li><a href="<?= site_url('terms-conditions') ?>">الشروط والأحكام</a></li>
                            <?php endif; ?>
                        </ul>
                    </div> 
                </div> 
                <div class="col-lg-3">
                <div class="widget">
                    <h3>اتصل بنا</h3>
                    <ul class="list-unstyled links mb-4">
                        <li dir="ltr"><a href="https://wa.me/201032863861" target="_blank" aria-label="تواصل عبر واتساب على 201032863861">+201032863861</a></li>
                        <li dir="ltr"><a href="mailto:support@fakhrcs.com">support@fakhrcs.com</a></li>
                    </ul>
                </div> </div> </div> <div class="row mt-3">
            <div class="col-12 text-center">
                <p>حقوق النشر &copy;<script>document.write(new Date().getFullYear());</script>
                    . جميع الحقوق محفوظة. &mdash; <a href="https://webeasystep.com">Web Easy Step</a>
                </p>
            </div>
        </div>
    </div> </div> <div id="overlayer"></div>
<div class="loader">
    <div class="spinner-border" role="status">
        <span class="sr-only">جار التحميل...</span>
    </div>
</div>

<!-- Include Course Request Modal -->
<?= view('site_layout/course_request_modal') ?>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/201032863861?text=<?= urlencode('السلام عليكم، لدي استفسار بخصوص منصة فخر CS') ?>"
   class="whatsapp-float"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="تواصل معنا عبر واتساب"
   title="تواصل معنا عبر واتساب">
    <div class="whatsapp-float-pulse"></div>
    <div class="whatsapp-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24" fill="currentColor">
            <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
        </svg>
    </div>
    <span>تواصل معنا</span>
</a>
