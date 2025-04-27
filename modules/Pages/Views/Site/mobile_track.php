<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

    <!-- Hero Section -->
    <div class="services-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <!-- SEO: Changed to H1 for main page title -->
                        <h1 class="line-bottom mb-4">اصنع تطبيقك بنفسك: تعلم بناء تطبيقات الهاتف الذكي!</h1>
                        <!-- SEO: H4 provides good secondary context -->
                        <h4 class="text-primary mb-3">مسار للمستوى المتوسط - عمر 14 عامًا فما فوق (باستخدام App Inventor)</h4> <!-- Added App Inventor context -->
                    </div>

                    <p data-aos="fade-up" data-aos-delay="100">
                        <strong>📱 هل يحلم ابنك/ابنتك بتحويل أفكاره المبتكرة إلى تطبيقات حقيقية تعمل على الهواتف الذكية؟</strong>
                    </p>

                    <p data-aos="fade-up" data-aos-delay="200">
                        مسار "صناعة التطبيقات" هو فرصتهم الفريدة لتحقيق ذلك! باستخدام أداة MIT App Inventor المرئية والسهلة، سيتمكن ابنك/ابنتك من تعلم أساسيات بناء تطبيقات الأندرويد خطوة بخطوة، حتى لو لم يكتبوا سطراً واحداً من كود التطبيقات من قبل.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="250">
                        هذا المسار يركز على جعل عملية تطوير التطبيقات ممتعة ومتاحة، مما يسمح بتصميم الواجهات، برمجة السلوك، واستخدام ميزات الهاتف بطريقة بصرية.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="300">
                        <a href="#pricing" class="btn btn-primary">اكتشف تفاصيل المسار والسعر</a>
                        <a href="<?= base_url('checkout/7') ?>" target="_blank" rel="noopener" class="btn btn-outline-primary ms-2">سجل الأن</a>
                    </p>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: Added descriptive alt text -->
                    <img src="<?= base_url() ?>site/images/mobile.jpg" alt="تعلم بناء تطبيقات الهاتف الذكي باستخدام MIT App Inventor" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Course Benefits (Why App Inventor) -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">لماذا تعلم بناء التطبيقات مع App Inventor؟</h2>
                </div>
            </div>
            <div class="row">
                <!-- SEO: Using H3 for benefit titles -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature h-100">
                        <span class="uil uil-mobile-android-alt"></span>
                        <h3>يجعل التطوير ممكنًا</h3>
                        <p>يبسط العملية المعقدة ويجعلها في متناول الشباب.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature h-100">
                        <span class="uil uil-rocket"></span>
                        <h3>نتائج سريعة ومحفزة</h3>
                        <p>يرون تطبيقاتهم تعمل على هواتفهم بسرعة، مما يعزز الدافع.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature h-100">
                        <span class="uil uil-brain"></span>
                        <h3>يعلم مفاهيم مهمة</h3>
                        <p>يقدم أساسيات تصميم الواجهات، برمجة الأحداث، والتعامل مع البيانات.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature h-100">
                        <span class="uil uil-lightbulb-alt"></span>
                        <h3>يشجع على الابتكار</h3>
                        <p>يمنحهم الأدوات لتحويل أي فكرة إلى تطبيق عملي.</p>
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
                    <img src="<?= base_url() ?>site/images/students_learn.jpg" alt="طلاب يتعلمون البرمجة في دورة تفاعلية أونلاين" class="img-fluid rounded shadow-sm">
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
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsePassion" aria-expanded="false" aria-controls="collapsePassion">الشغف والحماس</button>
                            </h2>
                            <div id="collapsePassion" class="collapse" aria-labelledby="headingPassion" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>لديه حماس وشغف بفكرة إنشاء تطبيقات الهاتف الخاصة به.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">مستوى الخبرة (المفضل)</button>
                            </h2>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>(يفضل) لديه فهم أساسي للمنطق البرمجي (مثل التسلسل، الشروط) من خلال سكراتش أو بايثون، ولكنه ليس شرطًا إلزاميًا.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">المهارات الأساسية</button>
                            </h2>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>يمتلك مهارات استخدام الكمبيوتر الأساسية.</p>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDevice" aria-expanded="false" aria-controls="collapseDevice">الجهاز المطلوب (للتجربة الكاملة)</button>
                            </h2>
                            <div id="collapseDevice" class="collapse" aria-labelledby="headingDevice" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <p>لديه هاتف أو جهاز لوحي يعمل بنظام أندرويد لاختبار التطبيقات عليه مباشرة (لكن يمكن استخدام المحاكي على الكمبيوتر أيضًا).</p>
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
                    <p>ينقسم هذا المسار إلى مستويين لبناء القدرة على إنشاء تطبيقات متزايدة التعقيد:</p>
                </div>
            </div>

            <div class="row mb-5 justify-content-center">
                <!-- Level 4.1 -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/mobile_4_1.jpg" alt="واجهة MIT App Inventor لتعلم أساسيات برمجة التطبيقات - المستوى 4.1" class="img-fluid mb-4 mx-auto" style="max-height: 180px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 4.1: أساسيات صنع تطبيقات الهاتف</h3>
                            <p class="text-muted"><strong>10 حصص / ~15 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>التعرف على بيئة MIT App Inventor وكيفية استخدامها.</li>
                                <li>تصميم واجهات التطبيقات البسيطة (أزرار، نصوص، صور).</li>
                                <li>جعل التطبيق يتفاعل مع المستخدم (الاستجابة للنقرات).</li>
                                <li>تعليم التطبيق اتخاذ قرارات بسيطة.</li>
                                <li>إضافة الأصوات والصور وجعل التطبيق يتحدث.</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> بناء عدة تطبيقات عملية وبسيطة وتشغيلها على الهاتف!</p>
                        </div>
                    </div>
                </div>

                <!-- Level 4.2 -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/mobile_4_2.jpg" alt="مثال على برمجة تطبيق متقدم باستخدام MIT App Inventor - المستوى 4.2" class="img-fluid mb-4 mx-auto" style="max-height: 180px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 4.2: تطبيقات تفاعلية ومتقدمة</h3>
                            <p class="text-muted"><strong>12 حصص / ~18 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>بناء تطبيقات أكثر تطوراً وتنظيم الكود بشكل أفضل.</li>
                                <li>التعامل مع قوائم المعلومات وعرضها.</li>
                                <li>جعل التطبيق يتذكر المعلومات (حفظ البيانات).</li>
                                <li>استخدام المؤقت لبناء ألعاب أو مهام متكررة.</li>
                                <li>التفاعل مع حركة الهاتف وإضافة الرسم.</li>
                                <li>بناء تطبيقات متعددة الشاشات.</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> بناء مشروع نهائي متكامل (لعبة، تطبيق اختبار، ملاحظات رقمي).</p>
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
                    <h2 class="line-bottom text-center mb-4">💡 الفوائد الرئيسية لمسار صناعة التطبيقات</h2>
                </div>
            </div>
            <div class="row">
                <!-- SEO: Using H3 for benefit titles -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature text-center h-100">
                        <span class="uil uil-key-skeleton-alt"></span>
                        <h3>إزالة الغموض</h3>
                        <p>جعل عملية تطوير تطبيقات الهاتف ممتعة وممكنة.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature text-center h-100">
                        <span class="uil uil-vector-square"></span>
                        <h3>تنمية التفكير التصميمي</h3>
                        <p>تنمية مهارات Design Thinking وحل المشكلات العملية.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature text-center h-100">
                        <span class="uil uil-shield-check"></span>
                        <h3>بناء الثقة بالنفس</h3>
                        <p>من خلال إنشاء منتجات رقمية حقيقية وقابلة للاستخدام.</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature text-center h-100">
                        <span class="uil uil-mobile-android"></span>
                        <h3>فهم الأساسيات</h3>
                        <p>فهم أساسيات مهمة في عالم تطوير تطبيقات الهاتف المحمول.</p>
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
                    <h2 class="line-bottom text-center mb-4">💰 الأسعار والباقات لمسار صناعة التطبيقات</h2>
                    <p>حوّل أفكار ابنك/ابنتك إلى تطبيقات بأسعار تشجع على الابتكار.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Level 4.1 Pricing -->
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المستوى 4.1</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">375</span>
                            </div>
                            <p class="pricing-text small">أساسيات صنع تطبيقات الهاتف</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">10 حصص تعليمية</li>
                                <li class="py-2">~15 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$25</li>
                                <li class="py-2">مشاريع تطبيقات بسيطة</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Corrected link to #register -->
                            <a href="<?= base_url('checkout/7') ?>" class="btn btn-outline-primary btn-sm">سجّل في المستوى الأساسي</a>
                        </div>
                    </div>
                </div>

                <!-- Level 4.2 Pricing -->
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المستوى 4.2</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">450</span>
                            </div>
                            <p class="pricing-text small">تطبيقات تفاعلية ومتقدمة</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">12 حصص تعليمية</li>
                                <li class="py-2">~18 ساعة تدريبية</li>
                                <li class="py-2">سعر الساعة: ~$25</li>
                                <li class="py-2">مشروع تطبيق متكامل</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Corrected link to #register -->
                            <a href="<?= base_url('checkout/8') ?>" class="btn btn-outline-primary btn-sm">سجّل في المستوى المتقدم</a>
                        </div>
                    </div>
                </div>

                <!-- Full Track Pricing -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="pricing pricing-popular h-100 text-center bg-white rounded shadow d-flex flex-column">
                        <span class="popularity-badge">العرض الأفضل!</span>
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for plan name -->
                            <h3>المسار الكامل (App Inventor)</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">735</span>
                                <del class="text-muted small">$825</del>
                            </div>
                            <p class="pricing-text small">المستويين معًا بخصم ~11%</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">22 حصة تعليمية</li>
                                <li class="py-2">~33 ساعة تدريبية</li>
                                <li class="py-2"><strong>خصم ~11% على السعر الكامل</strong></li>
                                <li class="py-2">مشاريع تطبيقات متدرجة</li>
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
                                    <strong>~33</strong>
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
                                    <strong>22</strong>
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
                    <h2 class="line-bottom text-center mb-4">✨ حول أفكار ابنك/ابنتك إلى تطبيقات حقيقية! ✨</h2>
                    <p class="mb-5">امنحهم الفرصة ليصبحوا من صانعي التكنولوجيا، وليس فقط مستهلكيها.</p>
                    <p>
                        <!-- SEO: Clear CTAs pointing to correct anchors -->
                        <a href="<?= base_url('checkout/7') ?>" class="btn btn-primary btn-lg">سجل الآن في مسار صناعة التطبيقات!</a>
                        <a href="https://wa.me/201032863861?text=أرغب في حجز استشارة مجانية بخصوص مسار تطبيقات الجوال" target="_blank" rel="noopener" class="btn btn-outline-primary btn-lg">احجز استشارتك المجانية الآن</a>
                        <!-- SEO Note: Ensure #contact ID exists or links to contact page -->
                    </p>
                </div>
            </div>
        </div>
    </div>


<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
    <!-- Additional JS if needed for this specific page -->
<?php $this->endSection(); ?>
