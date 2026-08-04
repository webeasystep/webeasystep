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
                            <?php else: ?>
                                <?php if ($isLoggedIn): ?>
                                    <li><a href="<?= site_url('enrollments/my-courses') ?>">مقرراتي</a></li>
                                <?php endif; ?>
                                <li><a href="<?= site_url('student-benefits') ?>">مميزات الاشتراك</a></li>
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
