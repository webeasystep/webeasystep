<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>

    <!-- Hero Section -->
    <div class="services-section">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <h1 class="line-bottom mb-4">ابدأ رحلتك البرمجية: تعلم سكراتش بأسلوب CS50 من هارفارد!</h1>
                        <h4 class="text-primary mb-3">مسار تأسيسي مكثف للمبتدئين (+14) في 12 حصة تفاعلية</h4>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="100">
                        <strong>🚀 هل تريد أن يكتسب ابنك/ابنتك أساسيات التفكير الحاسوبي وحل المشكلات التي تؤهله لوظائف المستقبل؟ </strong>
                    </p>

                    <p data-aos="fade-up" data-aos-delay="200">
                        انضم لمسارنا التأسيسي المكثف، المعتمد على <strong>منهج CS50 Scratch الشهير من جامعة هارفارد</strong>، والمقدم بأسلوب تفاعلي مباشر عبر Zoom. باستخدام منصة سكراتش (Scratch) المرئية، سيحول الطلاب أفكارهم إلى رسوم متحركة وألعاب وقصص، بينما يتعلمون المفاهيم البرمجية الأساسية بطريقة ممتعة وعملية في <strong>12 حصة مركزة (~18 ساعة تدريبية)</strong>.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="250">
                        نضيف إلى قوة منهج هارفارد <strong>التفاعل المباشر، الدعم المستمر، والتطبيق العملي الموجه</strong> بإشراف م/ أحمد فخر الدين لضمان أقصى استفادة.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="300">
                        <a href="#pricing" class="btn btn-primary">اكتشف تفاصيل المسار والسعر</a>
                        <a href="<?= base_url('checkout/1') ?>" target="_blank" rel="noopener" class="btn btn-outline-primary ms-2">سجل الأن</a>
                    </p>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                    <img src="<?= base_url() ?>site/images/scratch.png" alt="شعار منصة سكراتش لتعليم البرمجة المرئية للمبتدئين" class="img-fluid rounded shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Course Benefits (Why Scratch + CS50) -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">لماذا هذا المسار هو الأفضل للبداية؟</h2>
                    <p>نجمع بين متعة سكراتش، قوة منهجية هارفارد، والتفاعل المباشر لتأسيس لا مثيل له.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="0"> <div class="feature h-100 text-center p-3"> <span class="uil uil-university display-4 mb-3 text-primary"></span> <h3>منهجية عالمية</h3> <p>نعتمد على أسس منهج CS50 Scratch المعترف به من هارفارد.</p> </div> </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100"> <div class="feature h-100 text-center p-3"> <span class="uil uil-grin display-4 mb-3 text-primary"></span> <h3>سهل وممتع</h3> <p>بيئة سكراتش المرئية تجعل تعلم المفاهيم الأساسية ممتعًا.</p> </div> </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="200"> <div class="feature h-100 text-center p-3"> <span class="uil uil-comments-alt display-4 mb-3 text-primary"></span> <h3>تفاعل ودعم</h3> <p>حصص مباشرة ودعم مستمر من المدرب لضمان الفهم.</p> </div> </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="300"> <div class="feature h-100 text-center p-3"> <span class="uil uil-rocket display-4 mb-3 text-primary"></span> <h3>يؤسس للمستقبل</h3> <p>يبني التفكير المنطقي ومهارات حل المشكلات اللازمة للبرمجة.</p> </div> </div>
            </div>
        </div>
    </div>

    <!-- Is this course right for your child? -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mr-auto mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                    <img src="<?= base_url() ?>site/images/students_learn.jpg" alt="طلاب يتعلمون البرمجة في دورة تفاعلية أونلاين" class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-lg-7 ml-auto" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="line-bottom mb-4">هل هذا المسار مناسب لابني/ابنتي؟</h3>
                    <p>نعم، إذا كان ابنك/ابنتك:</p>
                    <div class="custom-accordion" id="accordion_1">
                        <div class="accordion-item"> <h2 class="mb-0"> <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">العمر المناسب</button> </h2> <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion_1"> <div class="accordion-body">عمره/عمرها 14 عامًا أو أكثر.</div> </div> </div>
                        <div class="accordion-item"> <h2 class="mb-0"> <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">مستوى الخبرة</button> </h2> <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_1"> <div class="accordion-body">مبتدئ تمامًا في البرمجة (لا يتطلب خبرة سابقة).</div> </div> </div>
                        <div class="accordion-item"> <h2 class="mb-0"> <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">المهارات الأساسية</button> </h2> <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion_1"> <div class="accordion-body">يمتلك مهارات استخدام الكمبيوتر الأساسية.</div> </div> </div>
                        <div class="accordion-item"> <h2 class="mb-0"> <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">الرغبة في التعلم</button> </h2> <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion_1"> <div class="accordion-body">لديه فضول ورغبة في تعلم كيفية عمل الألعاب والبرامج.</div> </div> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
    <div class="untree_co-section bg-light" id="course-content">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-9 text-center" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">🌟 محتوى المسار المكثف (مستوحى من CS50 Scratch) 🌟</h2>
                    <p>نغطي المفاهيم الأساسية والعملية لمنهج CS50 Scratch في <strong>12 حصة تفاعلية</strong>، تركز على بناء المشاريع الأساسية وتطبيق المفاهيم الجوهرية:</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Removed the explicit <i> tags from inside <li> -->
                    <ul class="list-unstyled ul-check primary row">
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="100"><strong>Sprites & Basics:</strong> الواجهة، الكائنات، المظاهر، الأصوات.</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="150"><strong>Events:</strong> الاستجابة للنقرات والمفاتيح.</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="200"><strong>Loops:</strong> التكرار البسيط والمركب.</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="250"><strong>Conditions:</strong> اتخاذ القرارات (If/Else).</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300"><strong>Variables:</strong> تخزين المعلومات واستخدامها.</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="350"><strong>Functions (Custom Blocks):</strong> تنظيم الكود (مقدمة وتطبيق).</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="400"><strong>Building Projects:</strong> تطبيقات عملية ومشاريع صغيرة لكل مفهوم.</li>
                        <li class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="450"><strong>Final Mini-Project:</strong> مشروع تجميعي لتطبيق أهم المهارات.</li>
                    </ul>
                    <p class="text-muted small text-center" data-aos="fade-up" data-aos-delay="500">(يتم التركيز على المفاهيم الأكثر أهمية وتطبيقها عمليًا ضمن الـ 12 حصة)</p>
                </div>
            </div>
            <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="550">
                <p><strong>إجمالي المسار: 12 حصة تفاعلية (~18 ساعة تدريبية)</strong></p>
            </div>
        </div>
    </div>

    <!-- Key Skills Acquired -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">💡 المهارات المكتسبة بنهاية المسار</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="0"> <div class="feature text-center h-100 p-3"> <span class="uil uil-brain display-4 mb-3 text-primary"></span> <h3>التفكير المنطقي</h3> <p>فهم كيفية تحليل المشكلات ووضع خطوات منطقية لحلها</p> </div> </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100"> <div class="feature text-center h-100 p-3"> <span class="uil uil-lightbulb-alt display-4 mb-3 text-primary"></span> <h3>حل المشكلات</h3> <p>تطبيق المفاهيم البرمجية لإيجاد حلول إبداعية للتحديات</p> </div> </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="200"> <div class="feature text-center h-100 p-3"> <span class="uil uil-constructor display-4 mb-3 text-primary"></span> <h3>أساسيات البرمجة</h3> <p>فهم المفاهيم الجوهرية (تكرار، شرط، متغيرات) بلغة مرئية</p> </div> </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="300"> <div class="feature text-center h-100 p-3"> <span class="uil uil-arrow-growth display-4 mb-3 text-primary"></span> <h3>الاستعداد للمستقبل</h3> <p>تأسيس جيد للانتقال لتعلم لغات برمجة أكثر تقدماً</p> </div> </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="untree_co-section" id="pricing" style="background-image: url('<?= base_url() ?>site/images/pattern-bg.png'); background-repeat: repeat; background-size: 200px;">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">💰 استثمارك في رحلة برمجة مركزة وفعالة</h2>
                    <p>احصل على تجربة تعليمية مكثفة تجمع منهجية CS50 العالمية، التفاعل المباشر، وخبرة المدرب المتخصص بسعر مدروس.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing pricing-popular h-100 text-center bg-white rounded shadow d-flex flex-column">
                        <span class="popularity-badge">مسار مكثف</span>
                        <div class="pricing-header py-4 px-3">
                            <h3>مسار سكراتش المكثف</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">360</span>
                                <span class="d-block small text-muted"> (بمعدل 20$ للساعة التدريبية) </span>
                            </div>
                            <p class="pricing-text small">12 حصة تفاعلية، ~18 ساعة تدريبية إجمالية</p>
                        </div>
                        <div class="pricing-body py-4 px-3 flex-grow-1">
                            <!-- Removed the explicit <i> tags from inside <li> -->
                            <ul class="list-unstyled small text-start">
                                <li class="py-2">تغطية المفاهيم الأساسية لمنهج CS50 Scratch</li>
                                <li class="py-2"><strong>12 حصة تفاعلية مباشرة (Zoom)</strong> (60-90 دقيقة للحصة)</li>
                                <li class="py-2">إجمالي <strong>~18 ساعة تدريبية</strong> موجهة وتطبيقية</li>
                                <li class="py-2">مشاريع وتحديات عملية لكل مفهوم أساسي</li>
                                <li class="py-2"><strong>مشروع نهائي مصغر</strong> لتطبيق المهارات</li>
                                <li class="py-2"><strong>دعم ومتابعة مستمرة</strong> من م/ أحمد فخر الدين</li>
                                <li class="py-2">وصول دائم لتسجيلات الحصص للمراجعة</li>
                            </ul>
                        </div>
                        <div class="pricing-footer py-4 px-3 mt-auto">
                            <a href="<?= base_url('checkout/1') ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg">سجّل الآن في المسار المكثف</a>
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
                    <h2 class="line-bottom text-center mb-4">📊 المسار في أرقام</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="row text-center">
                        <div class="col-md-4 col-6 mb-4"> <div class="counter"> <div class="counter-number"> <span class="uil uil-focus-target"></span> <span class="counter-text"> <strong>8+</strong> <span>مفاهيم أساسية</span> </span> </div> </div> </div>
                        <div class="col-md-4 col-6 mb-4"> <div class="counter"> <div class="counter-number"> <span class="uil uil-presentation-play"></span> <span class="counter-text"> <strong>12</strong> <span>حصة مباشرة</span> </span> </div> </div> </div>
                        <div class="col-md-4 col-6 mb-4"> <div class="counter"> <div class="counter-number"> <span class="uil uil-clock"></span> <span class="counter-text"> <strong>18</strong> <span>ساعة تدريبية</span> </span> </div> </div> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Combined Call to Action Section -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">🚀 مستعد لبدء رحلة الإبداع البرمجي؟ 🚀</h2>
                    <p class="mb-4">لا تفوت الفرصة! استثمر في مستقبل ابنك/ابنتك بمسار يجمع منهجية هارفارد العالمية والتوجيه العملي المباشر. المقاعد محدودة!</p>

                    <!-- Primary CTA: Register Now -->
                    <p data-aos="fade-up" data-aos-delay="100">
                        <a href="<?= base_url('checkout/1') ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg px-5">سجّل الآن في المسار المكثف!</a>
                    </p>

                    <!-- Secondary CTA: Consultation for hesitant users -->
                    <div class="mt-5" data-aos="fade-up" data-aos-delay="200">
                        <hr style="max-width: 200px; margin: 1rem auto;">
                        <h4 class="h5 mb-3">ما زلت مترددًا أو لديك أسئلة؟</h4>
                        <p class="mb-4">احجز استشارة مجانية قصيرة (15-20 دقيقة) مع م/أحمد فخر الدين لمناقشة التفاصيل والتأكد من أن هذا المسار هو الأنسب لابنك/ابنتك.</p>
                        <p>
                            <a href="https://wa.me/201032863861?text=أرغب في حجز استشارة مجانية بخصوص مسار سكراتش" target="_blank" rel="noopener" class="btn btn-outline-primary btn-lg">احجز استشارتك المجانية الآن</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Combined Call to Action Section -->

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
    <!-- Additional JS if needed -->
<?php $this->endSection(); ?>
