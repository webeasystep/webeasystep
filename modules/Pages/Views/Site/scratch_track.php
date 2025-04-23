<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

    <!-- Hero Section -->
    <div class="services-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <!-- SEO: Consider changing to H1 if this is the absolute main title of the *page*. If template has H1, H2 is okay. -->
                        <h1 class="line-bottom mb-4">شرارة الإبداع: تعلم البرمجة باللعب والمرح مع سكراتش!</h1>
                        <!-- SEO: H4 provides good secondary context -->
                        <h4 class="text-primary mb-3">مسار مخصص للمبتدئين تمامًا - عمر 14 عامًا فما فوق</h4>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="100">
                        <strong>🚀 هل تريد أن يكتشف ابنك/ابنتك عالم البرمجة الممتع ويبدأ في بناء مهارات المستقبل الأساسية؟ </strong>
                    </p>

                    <p data-aos="fade-up" data-aos-delay="200">
                        مسار "شرارة الإبداع" هو نقطة الانطلاق المثالية لرحلة ابنك/ابنتك في عالم التكنولوجيا! باستخدام منصة سكراتش (Scratch) المرئية والشيقة من معهد ماساتشوستس للتكنولوجيا (MIT)، سيحولون أفكارهم إلى رسوم متحركة تفاعلية وألعاب بسيطة ومبتكرة.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="300">
                        <!-- SEO: Internal anchor links, good for navigation -->
                        <a href="#register" class="btn btn-primary">سجّل الآن!</a>
                        <a href="#contact" class="btn btn-outline-primary ms-2">تواصل معنا</a>
                        <!-- SEO Note: Ensure #contact corresponds to an actual element ID on the page or links to a contact page -->
                    </p>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: Added descriptive alt text -->
                    <img src="<?= base_url() ?>site/images/scratch.jpg" alt="واجهة منصة سكراتش لتعليم البرمجة المرئية للمبتدئين" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Course Benefits -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title - Good structure -->
                    <h2 class="line-bottom text-center mb-4">لماذا نبدأ بسكراتش؟</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature h-100">
                        <span class="uil uil-smile"></span>
                        <!-- SEO: H3 for benefit title -->
                        <h3>ممتع وجذاب</h3>
                        <p>يحول تعلم البرمجة إلى تجربة لعب إبداعية</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature h-100">
                        <span class="uil uil-user-check"></span>
                        <!-- SEO: H3 for benefit title -->
                        <h3>سهل للمبتدئين</h3>
                        <p>لا يتطلب كتابة أكواد معقدة، يعتمد على سحب وإفلات اللبنات</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature h-100">
                        <span class="uil uil-bolt"></span>
                        <!-- SEO: H3 for benefit title -->
                        <h3>يبني الثقة</h3>
                        <p>يرى الطلاب نتائج عملهم فورًا، مما يعزز حماسهم وثقتهم</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature h-100">
                        <span class="uil uil-rocket"></span>
                        <!-- SEO: H3 for benefit title -->
                        <h3>يؤسس للمستقبل</h3>
                        <p>يعلم المفاهيم البرمجية الأساسية التي ستفيدهم في تعلم لغات أكثر تقدماً لاحقًا</p>
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
                    <img src="<?= base_url() ?>site/images/scratch_students.jpg" alt="طلاب يتعلمون البرمجة باستخدام سكراتش في دورة تفاعلية" class="img-fluid rounded">
                </div>
                <div class="col-lg-7 ml-auto" data-aos="fade-up" data-aos-delay="100">
                    <!-- SEO: H3 for subsection title - Good structure -->
                    <h3 class="line-bottom mb-4">هل هذا المسار مناسب لابني/ابنتي؟</h3>
                    <p>نعم، إذا كان ابنك/ابنتك:</p>

                    <!-- SEO: Accordion content is generally crawlable -->
                    <div class="custom-accordion" id="accordion_1">
                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <!-- SEO: Using H2 for button text inside accordion is common practice -->
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">العمر المناسب</button>
                            </h2>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <div class="d-flex">
                                        <div>
                                            <p>عمره/عمرها 14 عامًا أو أكثر، حيث صُمم المسار خصيصًا لهذه الفئة العمرية.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">مستوى الخبرة</button>
                            </h2>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <div class="d-flex">
                                        <div>
                                            <p>ليس لديه أي خبرة برمجية سابقة على الإطلاق. هذا المسار مصمم خصيصًا للمبتدئين تمامًا!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">المهارات الأساسية</button>
                            </h2>

                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <div class="d-flex">
                                        <div>
                                            <p>يمتلك مهارات استخدام الكمبيوتر الأساسية (تصفح الإنترنت، استخدام الفأرة).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">الشغف والفضول</button>
                            </h2>

                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion_1">
                                <div class="accordion-body">
                                    <div class="d-flex">
                                        <div>
                                            <p>لديه فضول ورغبة في التعلم والإبداع، وهي من أهم عوامل النجاح في رحلة تعلم البرمجة.</p>
                                        </div>
                                    </div>
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
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">🌟 ماذا سيتعلم ويحقق ابنك/ابنتك في هذا المسار؟ 🌟</h2>
                    <p>ينقسم هذا المسار إلى مستويين متتاليين لبناء المهارات تدريجيًا:</p>
                </div>
            </div>

            <div class="row mb-5 justify-content-center">
                <!-- Level 1.1 -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/scratch_1_1.jpg" alt="محتوى المستوى الأول في دورة برمجة سكراتش للمبتدئين" class="img-fluid mb-4 mx-auto" style="max-height: 180px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 1.1: استكشاف عالم البرمجة المرئية</h3>
                            <p class="text-muted"><strong>8 حصص / ~10-12 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>فهم "ما هي البرمجة؟" بطريقة بسيطة وممتعة.</li>
                                <li>التعرف على بيئة سكراتش وتركيب اللبنات البرمجية.</li>
                                <li>تحريك الشخصيات، تغيير مظهرها، وجعلها تتفاعل.</li>
                                <li>اكتشاف التكرار والأحداث وإضافة الأصوات.</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> بناء أول مشروع رسوم متحركة أو قصة تفاعلية بسيطة.</p>
                        </div>
                    </div>
                </div>

                <!-- Level 1.2 -->
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-1 h-100 d-flex flex-column shadow-sm bg-white p-4 rounded">
                        <div class="icon text-center">
                            <!-- SEO: Added descriptive alt text -->
                            <img src="<?= base_url() ?>site/images/scratch_1_2.jpg" alt="محتوى المستوى الثاني لبناء الألعاب في دورة سكراتش" class="img-fluid mb-4 mx-auto" style="max-height: 180px; width: auto;">
                        </div>
                        <div class="feature-1-content flex-grow-1">
                            <!-- SEO: H3 for level title -->
                            <h3 class="text-primary h5">المستوى 1.2: بناء الألعاب والقصص التفاعلية</h3>
                            <p class="text-muted"><strong>8 حصص / ~10-12 ساعة</strong></p>
                            <ul class="list-unstyled ul-check primary small">
                                <li>بناء ألعاب وقصص أكثر تعقيدًا بقواعد وأنظمة.</li>
                                <li>جعل البرامج تتخذ قرارات وتتذكر المعلومات (المتغيرات).</li>
                                <li>جعل الشخصيات تتفاعل مع بعضها ومع المستخدم.</li>
                                <li>تنظيم الأوامر البرمجية بشكل أفضل للمشاريع الأكبر.</li>
                            </ul>
                            <p class="mb-0 mt-auto small"><strong>النتيجة الملموسة:</strong> بناء مشروع نهائي متكامل كلعبة بسيطة أو قصة تفاعلية.</p>
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
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">💡 الفوائد الرئيسية لمسار سكراتش</h2>
                </div>
            </div>
            <div class="row">
                <!-- Using H3 for individual benefits, consistent with earlier benefits section -->
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature text-center h-100">
                        <span class="uil uil-brain"></span>
                        <h3>تأسيس قوي</h3>
                        <p>تأسيس قوي لمبادئ التفكير المنطقي والخوارزمي</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature text-center h-100">
                        <span class="uil uil-lightbulb-alt"></span>
                        <h3>حل المشكلات</h3>
                        <p>تنمية مهارات حل المشكلات بطريقة إبداعية</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature text-center h-100">
                        <span class="uil uil-shield-check"></span>
                        <h3>الثقة بالنفس</h3>
                        <p>تعزيز الثقة بالنفس والقدرة على تحويل الأفكار إلى واقع</p>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature text-center h-100">
                        <span class="uil uil-arrow-growth"></span>
                        <h3>إعداد للمستقبل</h3>
                        <p>إعداد مثالي للانتقال إلى لغات البرمجة النصية لاحقًا</p>
                    </div>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.untree_co-section -->

    <!-- Pricing Section -->
    <!-- SEO: Background images are less optimal than <img> tags, but ok for patterns. Ensure text is clear. -->
    <div class="untree_co-section" id="pricing" style="background-image: url('<?= base_url() ?>site/images/pattern-bg.png'); background-repeat: repeat; background-size: 200px;">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">💰 الأسعار والباقات</h2>
                    <p>استثمار مميز في مستقبل ابنك/ابنتك مع أفضل الأسعار للتعلم الفعّال</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for pricing plan name -->
                            <h3>المستوى 1.1</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">240</span>
                            </div>
                            <p class="pricing-text small">استكشاف عالم البرمجة المرئية</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">8 حصص تعليمية</li>
                                <li class="py-2">~12 ساعة تدريبية (متوسط)</li>
                                <li class="py-2">سعر الساعة: ~$20</li>
                                <li class="py-2">مشروع نهائي للمستوى</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Link points to the registration CTA section -->
                            <a href="#register" class="btn btn-outline-primary btn-sm">سجّل في المستوى 1.1</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing h-100 text-center bg-white rounded shadow-sm d-flex flex-column">
                        <div class="pricing-header py-4 px-3">
                            <!-- SEO: H3 for pricing plan name -->
                            <h3>المستوى 1.2</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">240</span>
                            </div>
                            <p class="pricing-text small">بناء الألعاب والقصص التفاعلية</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">8 حصص تعليمية</li>
                                <li class="py-2">~12 ساعة تدريبية (متوسط)</li>
                                <li class="py-2">سعر الساعة: ~$20</li>
                                <li class="py-2">مشروع نهائي متقدم</li>
                                <li class="py-2">شهادة إتمام المستوى</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Link points to the registration CTA section -->
                            <a href="#register" class="btn btn-outline-primary btn-sm">سجّل في المستوى 1.2</a>
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
                                <span class="amount">432</span>
                                <del class="text-muted small">$480</del>
                            </div>
                            <p class="pricing-text small">المستويين معًا بخصم 10%</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <ul class="list-unstyled small">
                                <li class="py-2">16 حصة تعليمية</li>
                                <li class="py-2">~24 ساعة تدريبية (متوسط)</li>
                                <li class="py-2"><strong>خصم 10% على السعر الكامل</strong></li>
                                <li class="py-2">مشاريع متدرجة المستوى</li>
                                <li class="py-2">شهادة إتمام المسار الكامل</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <!-- SEO: Link points to the registration CTA section -->
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
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">📊 ملخص المسار</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="row text-center">
                        <!-- SEO: Content is clear and summarises key metrics -->
                        <div class="col-md-4 mb-4">
                            <div class="counter">
                                <div class="counter-number">
                                    <span class="uil uil-clock"></span>
                                    <span class="counter-text">
                                    <strong>~24</strong>
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
                                    <strong>16</strong>
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
    <div class="untree_co-section" id="register"> <!-- SEO: ID matches the links -->
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="0">
                    <!-- SEO: H2 for section title -->
                    <h2 class="line-bottom text-center mb-4">✨ ابدأ رحلة الإبداع الآن! ✨</h2>
                    <p class="mb-5">امنح ابنك/ابنتك هدية تعلم مهارات المستقبل بطريقة ممتعة ومحفزة.</p>

                    <p>
                        <!-- SEO: Clear Call to Action links -->
                        <a href="#register" class="btn btn-primary btn-lg">سجل الآن في مسار سكراتش!</a>
                        <a href="#contact" class="btn btn-outline-primary btn-lg ms-3">تواصل معنا للمزيد من المعلومات</a>
                        <!-- SEO Note: Ensure #contact corresponds to an actual element ID on the page or links to a contact page -->
                    </p>
                </div>
            </div>
        </div>
    </div>


<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
    <!-- Additional JS if needed -->
<?php $this->endSection(); ?>
