<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

    <!-- Hero Section -->
    <div class="services-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <!-- SEO: Changed to H1 for main page title -->
                        <h1 class="line-bottom mb-4">أتقن بايثون: اكتسب لغة البرمجة القوية والمطلوبة عالميًا!</h1>
                        <!-- SEO: H4 provides good secondary context -->
                        <h4 class="text-primary mb-3">مسار للمستوى المتوسط - عمر 14 عامًا فما فوق</h4>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="100">
                        <strong>🐍 هل ابنك/ابنتك مستعد للانتقال من البرمجة المرئية إلى كتابة أكواد حقيقية بلغة قوية يستخدمها المحترفون؟ </strong>
                    </p>

                    <p data-aos="fade-up" data-aos-delay="200">
                        مسار "إتقان بايثون" هو بوابتكم إلى عالم البرمجة النصية الاحترافية! لغة بايثون (Python) هي واحدة من أكثر لغات البرمجة شيوعًا وتنوعًا في العالم، وتستخدمها شركات عملاقة مثل جوجل، ناسا، ونيتفليكس.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="300">
                        <a href="#pricing" class="btn btn-primary">اكتشف تفاصيل المسار والسعر</a>
                        <a href="https://wa.me/201032863861?text=أرغب في حجز استشارة مجانية بخصوص مسار التأسيس" target="_blank" rel="noopener" class="btn btn-outline-primary btn-lg">احجز استشارتك المجانية الآن</a>
                    </p>

                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: Added descriptive alt text -->
                    <img src="<?= base_url() ?>site/images/python.jpg" alt="شعار لغة بايثون Python لتعليم البرمجة للمستوى المتوسط" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Why Python? -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">لماذا بايثون؟</h2>
                </div>
            </div>
            <div class="row">
                <!-- SEO: Using H3 for feature titles -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature h-100">
                        <span class="uil uil-award"></span>
                        <h3>لغة قوية ومطلوبة</h3>
                        <p>تفتح الأبواب أمام فرص مستقبلية واعدة في سوق العمل</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature h-100">
                        <span class="uil uil-book-open"></span>
                        <h3>سهلة القراءة والتعلم</h3>
                        <p>تعتبر نقطة انطلاق ممتازة للبرمجة النصية</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature h-100">
                        <span class="uil uil-apps"></span>
                        <h3>متعددة الاستخدامات</h3>
                        <p>تستخدم في تطبيقات الويب، الألعاب، تحليل البيانات والذكاء الاصطناعي</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature h-100">
                        <span class="uil uil-brain"></span>
                        <h3>تنمي التفكير العميق</h3>
                        <p>تعزز القدرة على التحليل المنطقي وتصميم الحلول</p>
                    </div>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Used by Top Companies -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">لغة يستخدمها العمالقة</h2>
                    <p>تُستخدم بايثون في أكبر الشركات التقنية حول العالم</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="row text-center">
                        <!-- SEO: Added descriptive alt text for logos -->
                        <div class="col-4 col-md-2 mb-4">
                            <div class="client-logo">
                                <img src="<?= base_url() ?>site/images/logo-google.png" alt="شعار شركة جوجل التي تستخدم بايثون" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-4 col-md-2 mb-4">
                            <div class="client-logo">
                                <img src="<?= base_url() ?>site/images/logo-nasa.png" alt="شعار وكالة ناسا التي تستخدم بايثون" class="img-fluid">
                            </div>
                        </div>
                        <!-- تم استبدال Netflix بـ YouTube -->
                        <div class="col-4 col-md-2 mb-4">
                            <div class="client-logo">
                                <!-- تأكد من وجود ملف logo-youtube.png -->
                                <img src="<?= base_url() ?>site/images/logo-youtube.png" alt="شعار منصة يوتيوب التي تستخدم بايثون" class="img-fluid">
                            </div>
                        </div>
                        <!-- نهاية الاستبدال -->
                        <div class="col-4 col-md-2 mb-4">
                            <div class="client-logo">
                                <img src="<?= base_url() ?>site/images/logo-instagram.png" alt="شعار انستجرام الذي يستخدم بايثون" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-4 col-md-2 mb-4">
                            <div class="client-logo">
                                <img src="<?= base_url() ?>site/images/logo-spotify.png" alt="شعار سبوتيفاي الذي يستخدم بايثون" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-4 col-md-2 mb-4">
                            <div class="client-logo">
                                <img src="<?= base_url() ?>site/images/logo-dropbox.png" alt="شعار دروب بوكس الذي يستخدم بايثون" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Is this course right for your child? -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mr-auto mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: Added descriptive alt text -->
                    <img src="<?= base_url() ?>site/images/students_learn.jpg" alt="طلاب يتعلمون البرمجة في دورة تفاعلية أونلاين" class="img-fluid rounded shadow-sm">
                    <!-- Consider renaming img-school-4-min.jpg to something more descriptive like python-student.jpg -->
                </div>
                <div class="col-lg-7 ml-auto" data-aos="fade-up" data-aos-delay="100">
                    <!-- SEO: H3 for subsection title -->
                    <h3 class="line-bottom mb-4">هل هذا المسار مناسب لابني/ابنتي؟</h3>
                    <p>نعم، إذا كان ابنك/ابنتك:</p>

                    <!-- SEO: Accordion content is crawlable -->
                    <div class="custom-accordion" id="accordion_1">
                        <div class="accordion-item">
                            <!-- SEO: H2 for accordion button text is acceptable -->
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">العمر المناسب</button>
                            </h2>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>عمره/عمرها 14 عامًا أو أكثر، حيث صُمم المسار خصيصًا لهذه الفئة العمرية.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">الخبرة السابقة</button>
                            </h2>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>أكمل مسار سكراتش (المستوى 1) بنجاح أو لديه فهم جيد للمفاهيم المنطقية الأساسية للبرمجة (التسلسل، التكرار، الشروط).</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">الاستعداد</button>
                            </h2>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>مستعد للانتقال إلى كتابة الأكواد النصية ويمتلك مهارات استخدام الكمبيوتر الأساسية.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">اللغة الإنجليزية</button>
                            </h2>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>(يفضل) لديه أساسيات بسيطة في اللغة الإنجليزية لفهم المصطلحات البرمجية.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /.untree_co-section -->

    <!-- Course Levels Intro -->
    <div class="untree_co-section" id="course-levels">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">🌟 ماذا سيتعلم ويحقق ابنك/ابنتك في هذا المسار؟ 🌟</h2>
                    <p>ينقسم هذا المسار إلى مستويين لضمان بناء مهارات بايثون بشكل متين:</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Level 2.1 -->
    <div class="untree_co-section pt-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                    <div class="image-stack">
                        <div class="image-stack__item image-stack__item--top">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/python_2_1.jpg" alt="مثال على كود أساسيات لغة بايثون في المستوى 2.1" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 ms-auto" data-aos="fade-up" data-aos-delay="100">
                    <!-- SEO: H2 for level title (could be H3 if H2 is used above for the section intro, adjust based on overall structure) -->
                    <h2 class="section-title mb-4 text-primary">المستوى 2.1: أساسيات لغة بايثون</h2>
                    <p class="text-muted"><strong>8 حصص / ~12 ساعة</strong></p>
                    <ul class="list-unstyled ul-check primary">
                        <li>الانتقال السلس من البرمجة المرئية إلى كتابة الأكواد النصية</li>
                        <li>فهم كيفية إعطاء الأوامر للكمبيوتر باستخدام لغة بايثون</li>
                        <li>تعلم تخزين المعلومات (أرقام، نصوص، بيانات منطقية)</li>
                        <li>إجراء العمليات الحسابية والمنطقية اللازمة لحل المشكلات</li>
                        <li>كتابة برامج بسيطة تتفاعل مع المستخدم</li>
                        <li>التحكم في مسار البرنامج باستخدام الشروط لاتخاذ القرارات</li>
                    </ul>
                    <p class="mt-4"><strong>النتيجة الملموسة:</strong> بناء برامج نصية صغيرة باستخدام بايثون لحل مشاكل محددة.</p>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Level 2.2 -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 me-auto" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for level title (adjust to H3 if needed) -->
                    <h2 class="section-title mb-4 text-primary">المستوى 2.2: أدوات بايثون المتقدمة وحل المشكلات</h2>
                    <p class="text-muted"><strong>10 حصص / ~15 ساعة</strong></p>
                    <ul class="list-unstyled ul-check primary">
                        <li>تعلم طرق أكثر كفاءة لتكرار المهام</li>
                        <li>تنظيم وتخزين مجموعات كبيرة من البيانات (باستخدام القوائم)</li>
                        <li>بناء "أدوات برمجية" خاصة (الدوال) لتنظيم الكود وإعادة استخدامه</li>
                        <li>استخدام "صناديق أدوات جاهزة" (المكتبات) لتوسيع قدرات البرامج</li>
                        <li>تعلم كيفية قراءة المعلومات من الملفات وكتابة النتائج إليها</li>
                    </ul>
                    <p class="mt-4"><strong>النتيجة الملموسة:</strong> تطبيق جميع المهارات في بناء مشروع برمجي صغير متكامل، مثل "لعبة تخمين الكلمات" أو "مدير مهام بسيط".</p>
                </div>
                <div class="col-lg-6 ms-auto" data-aos="fade-up" data-aos-delay="100">
                    <div class="image-stack">
                        <div class="image-stack__item image-stack__item--bottom">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/python_2_2.jpg" alt="شاشة تعرض كود بايثون متقدم لمشروع المستوى 2.2" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Key Benefits -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">💡 الفوائد الرئيسية لمسار بايثون</h2>
                </div>
            </div>
            <div class="row">
                <!-- SEO: Using H3 for benefit titles -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature text-center h-100">
                        <span class="uil uil-briefcase"></span>
                        <h3>مهارة عالية الطلب</h3>
                        <p>اكتساب مهارة برمجية عالية الطلب في سوق العمل</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature text-center h-100">
                        <span class="uil uil-chart"></span>
                        <h3>تفكير تحليلي</h3>
                        <p>تعزيز القدرة على التفكير التحليلي وحل المشكلات المعقدة</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature text-center h-100">
                        <span class="uil uil-monitor"></span>
                        <h3>فهم أعمق</h3>
                        <p>فهم أعمق لكيفية عمل البرمجيات والتكنولوجيا</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature text-center h-100">
                        <span class="uil uil-robot"></span>
                        <h3>أساس للتخصص</h3>
                        <p>بناء أساس قوي لمتابعة التخصص في مجالات متقدمة مثل الذكاء الاصطناعي</p>
                    </div>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Pricing Section -->
    <!-- SEO: Background image is decorative, alt text not needed, ensure text is clear -->
    <div class="untree_co-section" id="pricing" style="background-image: url('<?= base_url() ?>site/images/pattern-bg.png'); background-repeat: repeat; background-size: 200px;">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">💰 الأسعار والباقات لمسار بايثون</h2>
                    <p>استثمارك في لغة المستقبل القوية مع خطط أسعار مرنة.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for pricing plan name -->
                            <h3>المستوى 2.1</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">276</span>
                            </div>
                            <p class="pricing-text">أساسيات لغة بايثون</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled">
                                <li class="py-2">8 حصص تعليمية</li>
                                <li class="py-2">~12 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$23</li>
                                <li class="py-2">مشروع نهائي للمستوى</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto"> <!-- Added mt-auto for consistent button alignment -->
                            <!-- SEO: Corrected link to point to #register -->
                            <a href="<?= base_url('checkout/2') ?>" class="btn btn-outline-primary">سجّل في هذا المستوى</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for pricing plan name -->
                            <h3>المستوى 2.2</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">345</span>
                            </div>
                            <p class="pricing-text">أدوات بايثون المتقدمة</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled">
                                <li class="py-2">10 حصص تعليمية</li>
                                <li class="py-2">~15 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$23</li>
                                <li class="py-2">مشروع نهائي متقدم</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto"> <!-- Added mt-auto -->
                            <!-- SEO: Corrected link to point to #register -->
                            <a href="<?= base_url('checkout/3') ?>" class="btn btn-outline-primary">سجّل في هذا المستوى</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="pricing pricing-popular h-100 text-center bg-white rounded shadow d-flex flex-column">
                        <span class="popularity-badge">العرض الأفضل!</span>
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for pricing plan name -->
                            <h3>المسار الكامل</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">550</span>
                                <del class="text-muted">$621</del>
                            </div>
                            <p class="pricing-text">المستويين معًا مع خصم ~11%</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled">
                                <li class="py-2">18 حصة تعليمية</li>
                                <li class="py-2">~27 ساعة تدريبية</li>
                                <li class="py-2"><strong>خصم ~11% على السعر الكامل</strong></li>
                                <li class="py-2">مشاريع متدرجة المستوى</li>
                                <li class="py-2">شهادة إتمام المسار الكامل</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto"> <!-- Added mt-auto -->
                            <!-- SEO: Corrected link to point to #register -->
                            <a href="#" class="btn btn-primary">سجّل في المسار الكامل</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Pricing Section -->

    <!-- Course Summary -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">📊 ملخص المسار</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="row text-center">
                        <!-- SEO: Clear summary information -->
                        <div class="col-md-4 mb-4">
                            <div class="counter">
                                <div class="counter-number">
                                    <span class="uil uil-clock"></span>
                                    <span class="counter-text">
                                    <strong>~27</strong>
                                    <span>ساعة دراسية</span>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="counter">
                                <div class="counter-number">
                                    <span class="uil uil-presentation-check"></span>
                                    <span class="counter-text">
                                    <strong>18</strong>
                                    <span>حصة</span>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="counter">
                                <div class="counter-number">
                                    <span class="uil uil-hourglass"></span>
                                    <span class="counter-text">
                                    <strong>~90</strong>
                                    <span>دقيقة لكل حصة</span>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="untree_co-section" id="register"> <!-- SEO: ID matches links -->
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">✨ استثمر في مهارات المستقبل اليوم! ✨</h2>
                    <p class="mb-5">جهز ابنك/ابنتك بلغة البرمجة التي تقود الابتكار في العالم.</p>
                    <p>
                        <a href="https://wa.me/201032863861?text=أرغب في حجز استشارة مجانية بخصوص مسار التأسيس" target="_blank" rel="noopener" class="btn btn-outline-primary btn-lg">احجز استشارتك المجانية الآن</a>
                        <a href="<?= base_url('checkout/2') ?>" class="btn btn-primary btn-lg">سجل الآن في مسار بايثون!</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
    <!-- Additional JS if needed -->
    <script>
        // Optional: JS for pricing toggle or effects if added later
    </script>
<?php $this->endSection(); ?>
