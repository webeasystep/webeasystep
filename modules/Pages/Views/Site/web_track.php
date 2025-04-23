<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

    <!-- Hero Section -->
    <div class="services-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <!-- SEO: Changed to H1 for main page title -->
                        <h1 class="line-bottom mb-4">ابنِ الويب بنفسك: تعلم تصميم وتطوير المواقع الاحترافية!</h1>
                        <!-- SEO: H4 provides good secondary context -->
                        <h4 class="text-primary mb-3">مسار متقدم - عمر 14 عامًا فما فوق</h4>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="100">
                        <strong>💻 هل يحلم ابنك/ابنتك بتصميم وبناء المواقع الإلكترونية الرائعة التي يتصفحونها كل يوم؟ </strong>
                    </p>

                    <p data-aos="fade-up" data-aos-delay="200">
                        مسار "بناء الويب" هو رحلة شاملة تأخذ ابنك/ابنتك من الصفر إلى القدرة على إنشاء مواقع ويب جميلة وتفاعلية واحترافية المظهر! خلال هذا المسار، سيتعلمون اللغات والأدوات الأساسية التي يستخدمها مطورو الويب المحترفون: HTML لهيكلة المحتوى، CSS للتصميم الجذاب، و JavaScript لإضافة التفاعلية والحياة للموقع.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="250">
                        هذا المسار لا يعلم فقط الأدوات، بل ينمي أيضًا حس التصميم، مهارات حل المشكلات التقنية، وفهم كيفية عمل الإنترنت بشكل أعمق.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="300">
                        <!-- SEO: Internal anchor links -->
                        <a href="#register" class="btn btn-primary">سجّل الآن!</a>
                        <a href="#contact" class="btn btn-outline-primary ms-2">تواصل معنا</a>
                        <!-- SEO Note: Ensure #contact ID exists or links to contact page -->
                    </p>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: Added descriptive alt text -->
                    <img src="<?= base_url() ?>site/images/web.jpg" alt="رسم توضيحي لعملية تصميم وتطوير المواقع الإلكترونية" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Course Benefits -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">لماذا تعلم تطوير الويب؟</h2>
                </div>
            </div>
            <div class="row">
                <!-- SEO: Using H3 for benefit titles -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature h-100">
                        <span class="uil uil-check-circle"></span>
                        <h3>مهارة عملية ومطلوبة</h3>
                        <p>القدرة على بناء المواقع أصبحت أساسية في العصر الرقمي.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature h-100">
                        <span class="uil uil-palette"></span>
                        <h3>يجمع بين الإبداع والمنطق</h3>
                        <p>يتيح لهم التعبير عن أفكارهم بصريًا وتقنيًا.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature h-100">
                        <span class="uil uil-desktop"></span>
                        <h3>نتائج ملموسة</h3>
                        <p>يرون مواقعهم تنبض بالحياة، مما يعزز الثقة والإنجاز.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature h-100">
                        <span class="uil uil-globe"></span>
                        <h3>فهم أعمق للإنترنت</h3>
                        <p>يعرفون كيف تعمل التكنولوجيا التي يستخدمونها يوميًا.</p>
                    </div>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Is this course right for your child? -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mr-auto mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: Added descriptive alt text -->
                    <img src="<?= base_url() ?>site/images/web_students.jpg" alt="طلاب يشاركون في دورة تطوير وتصميم مواقع الويب" class="img-fluid rounded">
                </div>
                <div class="col-lg-7 ml-auto" data-aos="fade-up" data-aos-delay="100">
                    <!-- SEO: Changed from H3 to H2 for section title -->
                    <h2 class="line-bottom mb-4">🤔 هل هذا المسار مناسب لابني/ابنتي؟</h2>
                    <p>نعم، إذا كان ابنك/ابنتك:</p>

                    <!-- SEO: Accordion content is crawlable. H2 for button text is acceptable. -->
                    <div class="custom-accordion" id="accordion_1">
                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">العمر المناسب</button>
                            </h2>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>عمره/عمرها 14 عامًا أو أكثر.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">مستوى الخبرة (المتطلب السابق)</button>
                            </h2>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>أكمل مسار بايثون (المستوى 2) بنجاح أو لديه فهم قوي للمنطق البرمجي وأساسيات البرمجة النصية.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">الاهتمامات</button>
                            </h2>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>لديه اهتمام بالتصميم المرئي، وحل المشكلات التقنية، وكيفية عمل المواقع الإلكترونية.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">المهارات الأساسية</button>
                            </h2>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>يمتلك مهارات استخدام الكمبيوتر الأساسية والقدرة على البحث عبر الإنترنت.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /.untree_co-section -->

    <!-- Course Levels -->
    <div class="untree_co-section bg-light" id="course-levels">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-9 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">🌟 ماذا سيتعلم ويحقق ابنك/ابنتك في هذا المسار؟ 🌟</h2>
                    <p>ينقسم هذا المسار إلى ثلاثة مستويات متكاملة لبناء خبرة شاملة في تطوير الواجهات الأمامية:</p>
                </div>
            </div>

            <div class="row mb-5 justify-content-center">
                <!-- Level 3A.1: HTML -->
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/html_3_1.jpg" alt="شعار HTML5 لتعلم بناء هياكل الويب" class="img-fluid mb-4 mx-auto" style="max-height: 150px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 3A.1: بناء هياكل الويب (HTML)</h3>
                            <p class="text-muted"><strong>8 حصص / ~12 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>فهم كيفية بناء صفحات الويب باستخدام لغة HTML.</li>
                                <li>إضافة وتنظيم النصوص، الصور، الروابط، والقوائم.</li>
                                <li>استخدام التقسيمات الدلالية لبنية موقع مفهومة.</li>
                                <li>بناء نماذج بسيطة لجمع البيانات.</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> بناء الهيكل الكامل لموقع شخصي بسيط.</p>
                        </div>
                    </div>
                </div>

                <!-- Level 3A.2: CSS -->
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/css_3_2.jpg" alt="شعار CSS3 لتصميم وتنسيق صفحات الويب" class="img-fluid mb-4 mx-auto" style="max-height: 150px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 3A.2: تزيين الويب وتنسيقه (CSS)</h3>
                            <p class="text-muted"><strong>10 حصص / ~15 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>إتقان لغة CSS لإضافة الألوان والخطوط والتصميم.</li>
                                <li>التحكم الدقيق في مكان وحجم العناصر.</li>
                                <li>بناء تخطيطات مرنة باستخدام Flexbox و Grid.</li>
                                <li>جعل الموقع متجاوبًا مع جميع الشاشات.</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> تحويل هيكل HTML إلى موقع جذاب بصريًا ومتجاوب.</p>
                        </div>
                    </div>
                </div>

                <!-- Level 3A.3: JavaScript -->
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/js_3_3.jpg" alt="شعار JavaScript لإضافة التفاعلية لصفحات الويب" class="img-fluid mb-4 mx-auto" style="max-height: 150px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 3A.3: إضافة الحياة للويب (JavaScript)</h3>
                            <p class="text-muted"><strong>12 حصص / ~18 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>تعلم JavaScript لإضافة التفاعلية والسلوك الديناميكي.</li>
                                <li>جعل الموقع يستجيب لأفعال المستخدم (النقر، الكتابة).</li>
                                <li>تغيير محتوى وشكل الصفحة ديناميكيًا.</li>
                                <li>تنظيم الكود باستخدام الدوال وجلب بيانات خارجية (APIs).</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> بناء مشروع ويب تفاعلي (معرض صور، قائمة مهام، لعبة بسيطة).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Key Benefits -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-9 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">💡 الفوائد الرئيسية لمسار تطوير الويب</h2>
                </div>
            </div>
            <div class="row">
                <!-- SEO: H3 for benefit titles -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature text-center h-100">
                        <span class="uil uil-brackets-curly"></span>
                        <h3>اكتساب مهارات شاملة</h3>
                        <p>بناء الواجهات الأمامية للمواقع (Frontend Development).</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature text-center h-100">
                        <span class="uil uil-ruler-combined"></span>
                        <h3>تصميم وتنفيذ مواقع</h3>
                        <p>القدرة على تصميم وتنفيذ مواقع ويب عملية وجذابة.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature text-center h-100">
                        <span class="uil uil-file-alt"></span>
                        <h3>تطوير ملف أعمال</h3>
                        <p>Portfolio بمشاريع حقيقية لعرض مهاراتهم.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature text-center h-100">
                        <span class="uil uil-layer-group"></span>
                        <h3>فهم عميق للتقنيات</h3>
                        <p>فهم عميق لتقنيات الويب الأساسية المستخدمة في كل مكان.</p>
                    </div>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Pricing Section -->
    <!-- SEO: Decorative background image, text clarity is key -->
    <div class="untree_co-section" id="pricing" style="background-image: url('<?= base_url() ?>site/images/pattern-bg.png'); background-repeat: repeat; background-size: 200px;">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">💰 الأسعار والباقات لمسار تطوير الويب</h2>
                    <p>استثمر في مهارات بناء المستقبل الرقمي بأسعار تنافسية.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- HTML Pricing -->
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المستوى 3A.1 (HTML)</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">300</span>
                            </div>
                            <p class="pricing-text small">بناء هياكل الويب</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">8 حصص تعليمية</li>
                                <li class="py-2">~12 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$25</li>
                                <li class="py-2">مشروع HTML متكامل</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Corrected link to #register -->
                            <a href="#register" class="btn btn-outline-primary btn-sm">سجّل في HTML</a>
                        </div>
                    </div>
                </div>

                <!-- CSS Pricing -->
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المستوى 3A.2 (CSS)</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">375</span>
                            </div>
                            <p class="pricing-text small">تزيين الويب وتنسيقه</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">10 حصص تعليمية</li>
                                <li class="py-2">~15 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$25</li>
                                <li class="py-2">مشروع CSS متجاوب</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Corrected link to #register -->
                            <a href="#register" class="btn btn-outline-primary btn-sm">سجّل في CSS</a>
                        </div>
                    </div>
                </div>

                <!-- JavaScript Pricing -->
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المستوى 3A.3 (JS)</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">450</span>
                            </div>
                            <p class="pricing-text small">إضافة الحياة للويب</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">12 حصص تعليمية</li>
                                <li class="py-2">~18 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$25</li>
                                <li class="py-2">مشروع JS تفاعلي</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Corrected link to #register -->
                            <a href="#register" class="btn btn-outline-primary btn-sm">سجّل في JavaScript</a>
                        </div>
                    </div>
                </div>

                <!-- Full Track Pricing -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="pricing pricing-popular h-100 text-center bg-white rounded shadow d-flex flex-column">
                        <span class="popularity-badge">العرض الأفضل!</span>
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المسار الكامل (Frontend)</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">1000</span>
                                <del class="text-muted small">$1125</del>
                            </div>
                            <p class="pricing-text small">HTML + CSS + JS بخصم ~11%</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">30 حصة تعليمية</li>
                                <li class="py-2">~45 ساعة تدريبية</li>
                                <li class="py-2"><strong>خصم ~11% على السعر الكامل</strong></li>
                                <li class="py-2">مشاريع متكاملة لكل مستوى</li>
                                <li class="py-2">شهادة إتمام المسار الكامل</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Corrected link to #register -->
                            <a href="#register" class="btn btn-primary btn-sm">سجّل في المسار الكامل</a>
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
                        <!-- SEO: Clear summary info -->
                        <div class="col-md-4 mb-4">
                            <div class="counter">
                                <div class="counter-number">
                                    <span class="uil uil-clock"></span>
                                    <span class="counter-text">
                                    <strong>~45</strong>
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
                                    <strong>30</strong>
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
                                    <strong>90</strong>
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
                    <h2 class="line-bottom text-center mb-4">✨ ابنِ مستقبل ابنك/ابنتك الرقمي اليوم! ✨</h2>
                    <p class="mb-5">امنحهم القدرة على تصميم وبناء وتشكيل العالم الرقمي من حولهم.</p>
                    <p>
                        <!-- SEO: Clear CTAs pointing to correct anchors -->
                        <a href="#register" class="btn btn-primary btn-lg">سجل الآن في مسار تطوير الويب!</a>
                        <a href="#contact" class="btn btn-outline-primary btn-lg ms-3">تواصل معنا للمزيد من المعلومات</a>
                        <!-- SEO Note: Ensure #contact ID exists or links to contact page -->
                    </p>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
    <!-- Additional JS if needed for this specific page -->
    <script>
        // Example: Smooth scroll for pricing buttons if needed
        // document.querySelectorAll('.pricing-footer a[href^="#register"]').forEach(anchor => { // Target links starting with #register
        //     anchor.addEventListener('click', function (e) {
        //         e.preventDefault();
        //         const targetId = this.getAttribute('href'); // Get the full href like #register
        //         const targetElement = document.querySelector(targetId); // Find element with ID "register"
        //         if(targetElement) {
        //             targetElement.scrollIntoView({
        //                 behavior: 'smooth'
        //             });
        //         }
        //     });
        // });
    </script>
<?php $this->endSection(); ?>
