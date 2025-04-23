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
                            <p class="mb-4 small text-white" data-aos="fade-up" data-aos-delay="200">أكاديمية رائدة في تدريس علوم الحاسب للطلاب (+14) بنظام المسارات، وتأهيلهم لسوق العمل المستقبلي .</p>
                            <p class="mb-0" data-aos="fade-up" data-aos-delay="300">
                                <!-- SEO: Internal anchor link to relevant section - Good -->
                                <a href="#learning-tracks" class="btn btn-secondary">استكشف مساراتنا التعليمية</a>
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
    <div class="package" id="learning-tracks" style="padding-top: 5em; padding-bottom: 3em;">
        <div class="package_title text-center mb-5">
            <!-- SEO: H2 for section title - Good structure -->
            <h2 class="line-bottom mb-4">ابدأ الآن واختر المسار المناسب لابنك في عالم البرمجة!</h2>
            <p class="lead">
                مسارات تعليمية متدرجة مصممة خصيصًا للطلاب (+14) لتأسيسهم بقوة في علوم البرمجة وتكنولوجيا المعلومات.
            </p>
        </div>
        <div class="container-fluid">
            <div class="d-flex" style="justify-content: space-evenly;flex-wrap:wrap ">

                <!-- Package 1: Scratch -->
                <div class="package_item package1">
                    <div class="package_item_head">
                        <div>
                            <!-- SEO: Changed from <p> to <h3> for better heading structure -->
                            <h3>مسار المبتدئين: Scratch</h3>
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/package/scratch.png" alt="شعار لغة سكراتش لتعليم البرمجة للمبتدئين" />
                        </div>
                        <p>مدخل ممتع لعالم البرمجة بدون أكواد معقدة - مثالي للبداية!</p>
                        <!-- SEO: Internal link to track details - Good -->
                        <a href="<?= base_url() ?>/pages/scratch_track"> اعرف المزيد عن مسار Scratch ></a>
                        <!-- SEO: Added descriptive alt text -->
                        <img src="<?= base_url() ?>site/images/package/level_prog1.png" alt="أيقونة المستوى الأول لمسار البرمجة" class="package_icon" />
                    </div>
                    <div>
                        <!-- SEO: H4 for subsection heading - Good structure -->
                        <h4>مميزات المسار:</h4>
                        <p>نؤسس ابنك خطوة بخطوة في التفكير المنطقي وحل المشكلات بطريقة تفاعلية:</p>
                        <ul class="ul-check list-unstyled primary">
                            <li> جلسات تفاعلية مباشرة (Zoom) تضمن تركيز واهتمام فردي.</li>
                            <li> مرونة تامة: جلسة أو جلستين أسبوعياً حسب استيعاب الطالب.</li>
                            <li> تطبيق عملي فوري: مشاريع وألعاب لترسيخ المفاهيم.</li>
                            <li> دعم متواصل طوال الأسبوع للإجابة على أي استفسار.</li>
                            <li> مستويان تعليميان للانتقال السلس للمرحلة التالية.</li>
                        </ul>
                    </div>
                    <!-- SEO: Decorative image, empty alt attribute -->
                    <img src="<?= base_url() ?>site/images/package/arrow1.png" alt="" class="arrow" />
                </div>

                <!-- Package 2: Python -->
                <div class="package_item package2">
                    <div class="package_item_head">
                        <div>
                            <!-- SEO: Changed from <p> to <h3> for better heading structure -->
                            <h3>المسار المتوسط: Python</h3>
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/package/python.png" alt="شعار لغة بايثون لتعليم البرمجة" />
                        </div>
                        <p>انتقل لكتابة الأكواد الحقيقية بلغة Python القوية والمطلوبة عالمياً.</p>
                        <!-- SEO: Internal link to track details - Good -->
                        <a href="<?= base_url() ?>/pages/python_track"> اعرف المزيد عن مسار Python ></a>
                        <!-- SEO: Added descriptive alt text -->
                        <img src="<?= base_url() ?>site/images/package/level_prog2.png" alt="أيقونة المستوى الثاني لمسار البرمجة" class="package_icon" />
                    </div>
                    <div>
                        <!-- SEO: H4 for subsection heading - Good structure -->
                        <h4>مميزات المسار:</h4>
                        <p>نمكن ابنك من بناء برامج بسيطة وحل المشكلات باستخدام لغة العصر:</p>
                        <ul class="ul-check list-unstyled primary">
                            <li> جلسات تفاعلية مباشرة (Zoom) لتطبيق عملي وفهم عميق.</li>
                            <li> مرونة تامة: جلسة أو جلستين أسبوعياً حسب استيعاب الطالب.</li>
                            <li> بناء مشاريع حقيقية: مهام مستمرة لتطوير المهارات البرمجية.</li>
                            <li> دعم فني وأكاديمي طوال الأسبوع لتخطي أي عقبة.</li>
                            <li> مستويان تعليميان لتأسيس قوي قبل التخصص.</li>
                        </ul>
                    </div>
                    <!-- SEO: Decorative image, empty alt attribute -->
                    <img src="<?= base_url() ?>site/images/package/arrow2.png" alt="" class="arrow" />
                    <!-- SEO: Decorative image, empty alt attribute -->
                    <img src="<?= base_url() ?>site/images/package/arrow2_emp.png" alt="" class="arrow_empty" />
                </div>

                <!-- Package 3: Advanced Tracks -->
                <div class="package_item package3">
                    <div class="package_item_head">
                        <div>
                            <!-- SEO: Changed from <p> to <h3> for better heading structure -->
                            <h3>المسار المتقدم: تطوير الويب</h3>
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/package/globe.png" alt="أيقونة مسار تطوير الويب" />
                        </div>
                        <p>احتراف بناء وتصميم مواقع وتطبيقات ويب تفاعلية خطوة بخطوة.</p>
                        <!-- SEO: Internal link to track details - Good -->
                        <a href="<?= base_url() ?>/pages/web_track"> اعرف المزيد عن مسار الويب ></a>
                    </div>
                    <div class="package_item_head">
                        <div>
                            <!-- SEO: Changed from <p> to <h3> for better heading structure -->
                            <h3>المسار المتقدم: تطوير تطبيقات الجوال</h3>
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/package/mobile.png" alt="أيقونة مسار تطوير تطبيقات الجوال" />
                        </div>
                        <p>تعلم بناء تطبيقات جوال احترافية تعمل على Android و iOS.</p>
                        <!-- SEO: Internal link to track details - Good -->
                        <a href="<?= base_url() ?>/pages/mobile_track"> اعرف المزيد عن مسار الجوال ></a>
                    </div>
                    <div>
                        <!-- SEO: H4 for subsection heading - Good structure -->
                        <h4>مميزات المسارات المتقدمة:</h4>
                        <p>تخصص في مجال مطلوب واختر طريقك لبناء مشاريع احترافية:</p>
                        <ul class="ul-check list-unstyled primary">
                            <li> تدريب مكثف عبر جلسات Zoom تفاعلية مع المدرب.</li>
                            <li> جدول مرن: جلسة أو جلستين أسبوعياً لتعلم متوازن.</li>
                            <li> بناء مشاريع تخرج قوية تضاف لسيرتك الذاتية.</li>
                            <li> دعم مستمر ومتابعة دقيقة لضمان اتقان المهارات.</li>
                            <li> 3 مستويات متقدمة لإعدادك لسوق العمل مباشرة.</li>
                        </ul>
                    </div>
                    <!-- SEO: Decorative image, empty alt attribute -->
                    <img src="<?= base_url() ?>site/images/package/arrow1_emp.png" alt="" class="arrow_empty" />
                </div>
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
                    <p data-aos="fade-up" data-aos-delay="100">بخبرة عملية تمتد لأكثر من 12 عامًا في صناعة البرمجيات وتدريب المبرمجين في المملكة وخارجها، يقود م/أحمد طلابنا نحو الاحتراف:</p>
                    <ul class="ul-check list-unstyled mb-5 primary" data-aos="fade-up" data-aos-delay="200">
                        <li> حاصل على شهادة MCSE من مايكروسوفت في إدارة الشبكات.</li>
                        <li> حاصل على شهادة CISCO 101 لإدارة الشبكات.</li>
                        <li> أشرف على العديد من مشاريع التخرج لطلاب علوم الحاسب.</li>
                        <li> قدم دورات متخصصة على منصات تعليمية رائدة.</li>
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
                    <h2 class="line-bottom text-center mb-4">لماذا يختار أولياء الأمور أكاديميتنا لأبنائهم؟</h2>
                    <p>نحن لا نعلم البرمجة فقط، بل نبني جيلاً مبدعاً ومؤهلاً للمستقبل، مع تجربة تعليمية فريدة ومتابعة لصيقة مصممة لطلاب نظام المسارات.</p>
                </div>
            </div>
            <div class="row">
                <!-- Feature items use H3 which is good for sub-headings within this section -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature">
                        <span class="uil uil-clock"></span>
                        <!-- SEO: H3 for feature title -->
                        <h3>دعم لا يتوقف</h3>
                        <p>لن يقف ابنك عند أي عقبة، دعم فني وأكاديمي متواصل طوال الأسبوع للإجابة والمساعدة.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature">
                        <span class="uil uil-video"></span>
                        <!-- SEO: H3 for feature title -->
                        <h3>تعلم في أي وقت</h3>
                        <p>مرونة كاملة لابنك لحضور الدروس مباشرة أو مشاهدتها مسجلة بالوقت المناسب له.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature">
                        <span class="uil uil-brain"></span>
                        <!-- SEO: H3 for feature title -->
                        <h3>بيئة تعليمية محفزة</h3>
                        <p>نحول التعلم إلى متعة! بيئة تفاعلية تشجع الإبداع وتنمي شغف البرمجة لدى ابنك.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature">
                        <span class="uil uil-file-check-alt"></span>
                        <!-- SEO: H3 for feature title -->
                        <h3>تطبيق عملي ومشاريع</h3>
                        <p>يتعلم ابنك بالممارسة عبر مشاريع عملية وأنشطة تطور تفكيره المنطقي ومهاراته.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature">
                        <span class="uil uil-comments-alt"></span>
                        <!-- SEO: H3 for feature title -->
                        <h3>إرشاد ودعم شامل</h3>
                        <p>نهتم بمستقبله كاملاً، نقدم الإرشاد لاختيار المسار الجامعي والمهني حتى بعد التخرج.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature">
                        <span class="uil uil-link"></span>
                        <!-- SEO: H3 for feature title -->
                        <h3>ربط بمنهج المسارات</h3>
                        <p>نعزز فهم ابنك للمنهج الدراسي بربط مفاهيم البرمجة بمواد نظام المسارات السعودي.</p>
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
                    <p data-aos="fade-up" data-aos-delay="100">نحن أول منصة تعليمية تركز حصرياً على تدريس علوم الحاسب والبرمجة للطلاب بنظام المسارات، مع منهجية تراعي احتياجاتهم وأهدافهم المستقبلية.</p>
                    <ul class="list-unstyled ul-check mb-5 primary" data-aos="fade-up" data-aos-delay="200">
                        <li> دورات فردية تفاعلية عبر Zoom لتركيز أكبر.</li>
                        <li> وصول دائم للمحاضرات المسجلة للمراجعة.</li>
                        <li> مسار تعليمي واضح ومخصص يراعي الفروق الفردية.</li>
                        <li> متابعة أسبوعية دقيقة لتقدم الطالب.</li>
                        <li> مرونة في عدد الحصص (1-2 أسبوعياً) حسب الحاجة.</li>
                        <li> مشاريع عملية تطبيقية بنهاية كل مستوى.</li>
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

    <!-- Guarantee Section -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center" data-aos="fade-up" data-aos-delay="0">
                    <figure class="mb-4">
                        <!-- SEO: Added descriptive alt text for the guarantee seal -->
                        <img src="<?= base_url() ?>site/images/gurantee.png" alt="شعار ضمان استعادة الأموال ١٠٠٪ لرضا العملاء" class="img-fluid rounded" style="max-width: 150px;">
                    </figure>
                    <!-- SEO: H2 for section title - Good structure -->
                    <h2 class="mb-4">ضماننا الذهبي: رضاك أو استرداد أموالك بالكامل!</h2>
                    <p class="lead" style="font-size: 1.1rem;">
                        نثق تمامًا بجودة المحتوى وشغف المدرب وخبرته، ونؤمن بأن ابنك سيحقق أقصى استفادة. <br> ولكن، إن لم تكن راضيًا تمامًا لأي سبب خلال الفترة الأولى من الدورة، يمكنك طلب استرداد كامل المبلغ المدفوع، بدون أي أسئلة.
                    </p>
                    <p class="mt-4">
                        <!-- SEO: Internal anchor link - Good CTA -->
                        <a href="#learning-tracks" class="btn btn-primary btn-lg">ابدأ رحلتك التعليمية بأمان الآن</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Guarantee Section -->

<?= $this->endSection(); ?>
