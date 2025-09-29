<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>
    <!-- intro -->
    <div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/main_banner.jpg');">
        <!-- SEO Note: Background images are harder for SEO than <img> tags. Ensure surrounding text clearly describes the section's purpose. -->
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-12">
                    <div class="row justify-content-center ">
                        <div class="col-lg-7 text-center ">
                            <a href="https://wa.me/201032863861" class="whatsapp-float" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i> <span style="padding: .2rem;">لديك استفسار؟ تحدث معنا</span>
                            </a>
                            <!-- SEO: H1 is the main page title - Good usage -->
                            <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">جهز ابنك لوظائف المستقبل: تعلم البرمجة خطوة بخطوة</h1>
                            <p class="mb-4 small text-white" data-aos="fade-up" data-aos-delay="200">أول أكاديمية في مصر لتدريس مسار الهندسة وعلوم الحاسب</p>
                            <p class="mb-0" data-aos="fade-up" data-aos-delay="300">
                                <!-- SEO: Internal anchor link to relevant section - Good -->
                                <a href="#learning-tracks" class="btn btn-secondary">استكشف الدورات التعليمية</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.untree_co-hero -->

    <!-- Package Section -->
    <!-- SEO: Added relevant ID for internal linking -->
    <div class="untree_co-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">كورساتنا التعليمية المتخصصة</h2>
                <p>تعلم البرمجة وعلوم الحاسب من خلال نظام تعليمي متطور يشمل حصص تفاعلية، اختبارات بعد كل درس، ومتابعة مستمرة مع أولياء الأمور.</p>
            </div>
        </div>
        <div class="row">
            <!-- Modal -->
            <div class="modal fade" id="startNowModal" tabindex="-1" role="dialog" aria-labelledby="startNowModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="startNowModalLabel">بادر الأن وقم بحجز حصة مجانية</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="study-year">السنة الدراسية</label>
                                    <select class="form-control" id="study-year">
                                        <option>اولى ثانوي</option>
                                        <option>ثانية ثانوي</option>
                                        <option>ثالثة ثانوي</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">الاسم</label>
                                    <input type="text" class="form-control" id="name">
                                </div>
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" id="email">
                                </div>
                                <div class="form-group">
                                    <label for="notes">ملاحظات أخرى (اختياري)</label>
                                    <textarea class="form-control" id="notes"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="button" class="btn btn-primary">تقديم</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-4">
                        <div class="custom-media">
                            <img
                                    alt="<?= esc($course['course_title']) ?>"
                                    style="object-fit: cover;"
                                    src="<?= thumb($course['image'], 540, 540) ?>"
                                    class="card-img-top"
                            >
                            <div class="custom-media-body">
                                <div class="d-flex justify-content-between pb-3">
                                    <div class="text-primary">
                                        <span class="uil uil-play-circle"></span>
                                        <span><?= $course['video_count'] ?> فيديو</span>
                                    </div>
                                    <div class="text-info">
                                        <span class="uil uil-layers"></span>
                                        <span><?= $course['unit_count'] ?> وحدة</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between pb-3">
                                    <div class="text-success">
                                        <span class="uil uil-clipboard-notes"></span>
                                        <span><?= $course['quiz_count'] ?> اختبار</span>
                                    </div>
                                    <div class="text-warning">
                                        <span class="uil uil-file-alt"></span>
                                        <span><?= $course['page_count'] ?> صفحة</span>
                                    </div>
                                </div>
                                <h3><?= esc($course['course_title']) ?></h3>
                                <p class="text-muted small mb-3"><?= esc(substr($course['short_desc'], 0)) ?>...</p>
                                <div class="border-top d-flex justify-content-between pt-3 mt-3 align-items-center">
                                    <div>
                                        <?php if ($course['is_free']): ?>
                                            <span class="badge bg-success">مجاني</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-inline-flex" style="gap: 5px;">
                                        <!-- شراء وحدات button - always visible -->
                                        <a href="<?= base_url('courses/course_details/' . $course['slug']) ?>" class="btn btn-primary btn-sm">شراء وحدات</a>
                                        <!-- مشاهدة button - only visible if user is logged in and enrolled -->
                                        <?php if (auth()->loggedIn() && $course['is_enrolled']): ?>
                                            <a href="<?= $course['course_url'] ?>" class="btn btn-secondary btn-sm">مشاهدة</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <div class="col-12 text-center">
                     <p class="text-muted">لا توجد كورسات متاحة حالياً</p>
                 </div>
             <?php endif; ?>
        </div>
    </div>
</div>
    <!--End Package Section-->
    <br />

    <!-- instructor Section -->
    <div class="services-section" style="background-color: #f8f9fa; padding: 5em 0;">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <!-- SEO: H2 for section title - Good structure -->
                        <h2 class="line-bottom mb-4">المدرب: م/ أحمد فخر الدين</h2>
                    </div>
                    <p data-aos="fade-up" data-aos-delay="100">بخبرة عملية تمتد لأكثر من 12 عامًا في صناعة البرمجيات وتدريب المبرمجين، يقدم م/أحمد نظاماً تعليمياً متطوراً يشمل:</p>
                    <ul class="ul-check list-unstyled mb-5 primary" data-aos="fade-up" data-aos-delay="200">
                        <li> نظام تعلم بالحصص مع اختبارات بعد كل درس</li>
                        <li> عدم السماح بالانتقال للحصة التالية إلا بعد اجتياز الاختبار</li>
                        <li> تنبيهات أسبوعية لأولياء الأمور بتقدم الطالب</li>
                        <li> مجموعة دعم فني وأكاديمي طوال الأسبوع</li>
                        <li> لقاء أسبوعي مباشر عبر Google Meet</li>
                        <li> مشاريع عملية وتطبيقات تفاعلية</li>
                        <li> محاكاة لنظام امتحانات TOFAS</li>
                    </ul>
                    <p data-aos="fade-up" data-aos-delay="300">
                        <!-- SEO: Internal anchor link - Good -->
                        <a href="#learning-tracks" class="btn btn-primary">استفد من خبرة +12 عامًا - اكتشف المسارات</a>
                    </p>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <figure class="img-wrap-2">
                        <!-- SEO: Added descriptive alt text for the instructor's image -->
                        <img src="<?= base_url() ?>site/images/profile_image.jpg" alt="صورة المدرب المهندس أحمد فخر الدين خبير البرمجة" class="img-fluid">
                        <div class="dotted"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <!-- End instructor Section -->

    <!-- Features/Benefits Section -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title - Good structure -->
                    <h2 class="line-bottom text-center mb-4">لماذا تختار مسارلينك؟</h2>
                    <p>نحن لا نعلم البرمجة فقط، بل نبني جيلاً مبدعاً ومؤهلاً للمستقبل، مع تجربة تعليمية فريدة ومتابعة لصيقة مصممة لطلاب نظام المسارات.</p>
                </div>
            </div>
            <div class="row">
                <!-- Feature items use H3 which is good for sub-headings within this section -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature">
                        <span class="uil uil-graduation-cap"></span>
                        <h3>نظام تعلم متدرج</h3>
                        <p>تعلم بالحصص مع اختبارات بعد كل درس، لا يمكن الانتقال للحصة التالية إلا بعد اجتياز الاختبار.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature">
                        <span class="uil uil-bell"></span>
                        <h3>متابعة أولياء الأمور</h3>
                        <p>تنبيهات أسبوعية لأولياء الأمور بتقدم الطالب ونتائج الاختبارات والواجبات.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature">
                        <span class="uil uil-users-alt"></span>
                        <h3>مجموعة دعم متواصلة</h3>
                        <p>دعم فني وأكاديمي طوال الأسبوع مع مجموعة دعم متخصصة للإجابة على جميع الاستفسارات.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature">
                        <span class="uil uil-video"></span>
                        <h3>لقاءات مباشرة أسبوعية</h3>
                        <p>لقاء أسبوعي مباشر عبر Google Meet لمراجعة المفاهيم والإجابة على الأسئلة.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature">
                        <span class="uil uil-constructor"></span>
                        <h3>مشاريع عملية تطبيقية</h3>
                        <p>تطبيقات ومشاريع عملية تفاعلية تطور المهارات البرمجية والتفكير المنطقي.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature">
                        <span class="uil uil-clipboard-alt"></span>
                        <h3>محاكاة امتحانات TOFAS</h3>
                        <p>تدريب على نظام امتحانات TOFAS لإعداد الطلاب للامتحانات الرسمية بثقة.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.untree_co-section -->

    <!-- Who We Are Section -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-between align-items-center text-center aos-init aos-animate" >
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <!-- SEO: H2 for section title - Good structure -->
                    <h2 class="line-bottom mb-4" data-aos="fade-up" data-aos-delay="0">أكاديمية متخصصة لطلاب المسارات</h2>
                    <p data-aos="fade-up" data-aos-delay="100">منصة تعليمية متطورة تقدم نظام تعلم بالحصص مع نظام اختبارات متقدم ومتابعة شاملة لأولياء الأمور.</p>
                    <ul class="list-unstyled ul-check mb-5 primary" data-aos="fade-up" data-aos-delay="200">
                        <li> نظام بيع كورسات بالحصص مع تقدم متدرج</li>
                        <li> اختبارات إجبارية بعد كل حصة لضمان الفهم</li>
                        <li> منع الوصول للحصة التالية قبل اجتياز الاختبار</li>
                        <li> تنبيهات أسبوعية لأولياء الأمور بالتقدم</li>
                        <li> مجموعة دعم فني وأكاديمي طوال الأسبوع</li>
                        <li> لقاءات أسبوعية مباشرة عبر Google Meet</li>
                        <li> مشاريع عملية وتطبيقات تفاعلية</li>
                        <li> محاكاة شاملة لنظام امتحانات TOFAS</li>
                    </ul>
                    <p data-aos="fade-up" data-aos-delay="300">
                        <!-- SEO: Internal anchor link & external link with noopener - Good -->
                        <a href="#learning-tracks" class="btn btn-primary btn-lg">تصفح المسارات التعليمية</a>
                        <a href="https://wa.me/201032863861" target="_blank" rel="noopener" class="btn btn-outline-primary">تحدث مع مستشار تعليمي</a>
                    </p>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="400">
                    <!-- SEO: Added descriptive alt text for the image -->
                    <img src="<?= base_url() ?>site/images/students_girls.jpg" alt="طالبات يدرسن البرمجة في أكاديمية متخصصة لنظام المسارات" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>
    <!-- /.untree_co-section -->

<!-- Free Session Booking Section -->
<div class="untree_co-section bg-light" id="book-free-session">}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center" data-aos="fade-up" data-aos-delay="0">
                <figure class="mb-4">
                    <!-- Option 1: Icon -->
                    <span class="uil uil-calendar-alt display-4 text-primary mb-3"></span>
                    <!-- Option 2: Remove icon if preferred -->
                </figure>
                <!-- SEO: H2 for section title - Good structure -->
                <h2 class="mb-4">ابدأ رحلة التعلم مع نظامنا المتطور!</h2>
                <p class="lead" style="font-size: 1.1rem;">
                    انضم إلى نظام تعليمي فريد يجمع بين التعلم بالحصص والاختبارات التفاعلية. <br>
                    مع متابعة مستمرة لأولياء الأمور ودعم أكاديمي طوال الأسبوع، بالإضافة إلى لقاءات مباشرة أسبوعية ومحاكاة امتحانات TOFAS.
                </p>
                <p class="mt-4">
                    <!-- SEO: Clear Call to Action linking to courses -->
                    <a href="#learning-tracks" class="btn btn-primary btn-lg">استكشف الكورسات المتاحة</a>
                    <a href="https://wa.me/201032863861?text=أرغب في معرفة المزيد عن نظام التعلم بالحصص" target="_blank" rel="noopener" class="btn btn-outline-primary btn-lg ml-3">تحدث مع مستشار تعليمي</a>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- End Free Session Booking Section -->

<?= $this->endSection(); ?>
