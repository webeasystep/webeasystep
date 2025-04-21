<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

<!-- Hero Section -->
<div class="services-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom mb-4">شرارة الإبداع: تعلم البرمجة باللعب والمرح مع سكراتش!</h2>
                    <h4 class="text-primary mb-3">مسار مخصص للمبتدئين تمامًا - عمر 14 عامًا فما فوق</h4>
                </div>

                <p data-aos="fade-up" data-aos-delay="100">
                    <strong>🚀 هل تريد أن يكتشف ابنك/ابنتك عالم البرمجة الممتع ويبدأ في بناء مهارات المستقبل الأساسية؟ 🚀</strong>
                </p>

                <p data-aos="fade-up" data-aos-delay="200">
                    مسار "شرارة الإبداع" هو نقطة الانطلاق المثالية لرحلة ابنك/ابنتك في عالم التكنولوجيا! باستخدام منصة سكراتش (Scratch) المرئية والشيقة من معهد ماساتشوستس للتكنولوجيا (MIT)، سيحولون أفكارهم إلى رسوم متحركة تفاعلية وألعاب بسيطة ومبتكرة.
                </p>

                <p data-aos="fade-up" data-aos-delay="300"><a href="#register" class="btn btn-primary">سجّل الآن!</a> <a href="#contact" class="btn btn-outline-primary ms-2">تواصل معنا</a></p>
            </div>
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                <figure class="img-wrap-2">
                    <img src="<?= base_url() ?>site/images/scratch.jpg" alt="تعلم البرمجة مع سكراتش" class="img-fluid">
                    <div class="dotted"></div>
                </figure>
            </div>
        </div>
    </div>
</div>

<!-- Course Benefits -->
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">لماذا نبدأ بسكراتش؟</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                <div class="feature h-100">
                    <span class="uil uil-smile"></span>
                    <h3>ممتع وجذاب</h3>
                    <p>يحول تعلم البرمجة إلى تجربة لعب إبداعية</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature h-100">
                    <span class="uil uil-user-check"></span>
                    <h3>سهل للمبتدئين</h3>
                    <p>لا يتطلب كتابة أكواد معقدة، يعتمد على سحب وإفلات اللبنات</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature h-100">
                    <span class="uil uil-bolt"></span>
                    <h3>يبني الثقة</h3>
                    <p>يرى الطلاب نتائج عملهم فورًا، مما يعزز حماسهم وثقتهم</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature h-100">
                    <span class="uil uil-rocket"></span>
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
                <img src="<?= base_url() ?>site/images/scratch_students.png" alt="طلاب يتعلمون البرمجة" class="img-fluid rounded">
            </div>
            <div class="col-lg-7 ml-auto" data-aos="fade-up" data-aos-delay="100">
                <h3 class="line-bottom mb-4">هل هذا المسار مناسب لابني/ابنتي؟</h3>
                <p>نعم، إذا كان ابنك/ابنتك:</p>

                <div class="custom-accordion" id="accordion_1">
                    <div class="accordion-item">
                        <h2 class="mb-0">
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
                <h2 class="line-bottom text-center mb-4">🌟 ماذا سيتعلم ويحقق ابنك/ابنتك في هذا المسار؟ 🌟</h2>
                <p>ينقسم هذا المسار إلى مستويين متتاليين لبناء المهارات تدريجيًا:</p>
            </div>
        </div>

        <!-- Level 1.1 -->
        <div class="row mb-5">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-1">
                    <div class="icon">
                        <img src="<?= base_url() ?>site/images/scratch_1_1.jpg" alt="المستوى الأول" class="img-fluid mb-4">
                    </div>
                    <div class="feature-1-content">
                        <h2 class="text-primary">المستوى 1.1: استكشاف عالم البرمجة المرئية</h2>
                        <p class="text-muted"><strong>8 حصص / ~10-12 ساعة</strong></p>
                        <ul class="list-unstyled ul-check primary">
                            <li>فهم "ما هي البرمجة؟" بطريقة بسيطة من خلال الألعاب والرسوم المتحركة.</li>
                            <li>التعرف على بيئة سكراتش وتركيب "لبنات الأوامر" كالأحجية.</li>
                            <li>تعلم كيفية تحريك الشخصيات، تغيير مظهرها، وجعلها تتفاعل.</li>
                            <li>اكتشاف قوة التكرار والأحداث لبدء البرامج.</li>
                            <li>إضافة أصوات بسيطة للمشاريع.</li>
                        </ul>
                        <p class="mb-0"><strong>النتيجة الملموسة:</strong> بناء أول مشروع رسوم متحركة أو قصة تفاعلية بسيطة بأيديهم!</p>
                    </div>
                </div>
            </div>

            <!-- Level 1.2 -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-1">
                    <div class="icon">
                        <img src="<?= base_url() ?>site/images/scratch_1_2.jpg" alt="المستوى الثاني" class="img-fluid mb-4">
                    </div>
                    <div class="feature-1-content">
                        <h2 class="text-primary">المستوى 1.2: بناء الألعاب والقصص التفاعلية</h2>
                        <p class="text-muted"><strong>8 حصص / ~10-12 ساعة</strong></p>
                        <ul class="list-unstyled ul-check primary">
                            <li>الانتقال لبناء ألعاب وقصص أكثر تعقيدًا بقواعد وأنظمة.</li>
                            <li>تعلم كيف تجعل البرامج تتخذ قرارات وتتذكر المعلومات (مثل نقاط اللاعب).</li>
                            <li>جعل الشخصيات تتفاعل مع بعضها البعض ومع المستخدم بشكل أذكى.</li>
                            <li>تنظيم الأوامر البرمجية بشكل أفضل للمشاريع الأكبر.</li>
                        </ul>
                        <p class="mb-0"><strong>النتيجة الملموسة:</strong> تتويج المهارات ببناء مشروع نهائي متكامل كـ "لعبة مطاردة بسيطة" أو "قصة تفاعلية" تتغير أحداثها بناءً على اختيارات المستخدم.</p>
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
                <h2 class="line-bottom text-center mb-4">💡 الفوائد الرئيسية لمسار سكراتش</h2>
            </div>
        </div>
        <div class="row">
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

<!-- Course Summary -->
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">📊 ملخص المسار</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <div class="counter">
                            <div class="counter-number">
                                <span class="uil uil-clock"></span>
                                <span class="counter-text">
                                    <strong>20-24</strong>
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
                                    <strong>75-90</strong>
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
<div class="untree_co-section" id="register">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">✨ ابدأ رحلة الإبداع الآن! ✨</h2>
                <p class="mb-5">امنح ابنك/ابنتك هدية تعلم مهارات المستقبل بطريقة ممتعة ومحفزة.</p>

                <p>
                    <a href="#register-form" class="btn btn-primary btn-lg">سجل الآن في مسار سكراتش!</a>
                    <a href="#contact" class="btn btn-outline-primary btn-lg ms-3">تواصل معنا للمزيد من المعلومات</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<!-- Additional JS if needed -->
<?php $this->endSection(); ?>
