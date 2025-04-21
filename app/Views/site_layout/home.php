<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>
<!-- intro -->
<div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/hero-img-1-min.jpg');">

    <div class="container">
        <div class="row align-items-center justify-content-center">

            <div class="col-12">

                <div class="row justify-content-center ">

                    <div class="col-lg-6 text-center ">
                        <a href="#" data-fancybox data-aos="fade-up"
                           data-aos-delay="0" class="caption mb-4 d-inline-block">شاهد الفيديو</a>
                        <a href="https://wa.me/201032863861" class="whatsapp-float" target="_blank" rel="noopener">
                            <i class="fab fa-whatsapp"></i> <span style="padding: .2rem;">لديك استفسار؟  تحدث</span>
                        </a>
                        <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">اصنع مستقبلك
                            الرقمي</h1>
                        <p class="mb-3 small text-white" data-aos="fade-up" data-aos-delay="200">أول أكاديمية متخصصة في
                            تدريس علوم الحاسب للطلبة وإعدادهم لوظائف المستقبل.</p>
                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300">
                            <a href="<?= base_url('courses') ?>" class="btn btn-secondary">استكشف الدورات</a></p>

                    </div>


                </div>

            </div>

        </div> <!-- /.row -->
    </div> <!-- /.container -->

</div>
<!-- /.untree_co-hero -->
<!--Package-->
<div class="package">
    <div class="package_title">
        <h4>ابدأ الآن واختر المسار المناسب لك في عالم البرمجة!</h4>
        <p>
            صممت المسارات التعليمية خصيصًا لتناسب الطلاب من عمر 14 سنة فما فوق،
            وتشمل مراحل متعددة لبناء أساس قوي في علوم البرمجة
        </p>
    </div>
    <div class="container-fluid">
        <div class="d-flex" style="justify-content: space-evenly;flex-wrap:wrap ">

            <div class="package_item package1">
                <div class="package_item_head">
                    <div>
                        <p>مبتدئ - Scratch </p>
                        <img src="<?= base_url() ?>site/images/package/scratch.png" alt="Alternate Text" />
                    </div>
                    <p>ابدأ من الصفر، وتعلّم أساسيات البرمجة بطريقة ممتعة وسهلة باستخدام Scratch!</p>
                    <a href="<?= base_url() ?>/pages/scratch_track">  اعرف المزيد ></a>
                    <img src="<?= base_url() ?>site/images/package/level_prog1.png" alt="Alternate Text" class="package_icon" />
                </div>


                <div>
                    <h4>تفاصيل المسار</h4>
                    <p>ابدأ رحلتك في البرمجة بطريقة ممتعة وتفاعلية باستخدام Scratch – بدون أي سطور كود!</p>

                    <ul>
                        <li>👦 حد أقصى 5 أطفال بالمجموعة</li>
                        <li>🧩 عدد المستويات التعليمية: 2 </li>
                        <li>📺 جلسة مباشرة أسبوعية مع المدرب</li>
                        <li>📝 مشاريع عملية ومهام مستمرة</li>
                        <li>💬 جروب دعم تليجرام لكل مستوى</li>
                    </ul>
                </div>

                <img src="<?= base_url() ?>site/images/package/arrow1.png" alt="Alternate Text" class="arrow" />
            </div>


            <div class="package_item package2">
                <div class="package_item_head">
                    <div>
                        <p>متوسط - Python</p>
                        <img src="<?= base_url() ?>site/images/package/python.png" alt="Alternate Text" />
                    </div>
                    <p>اتعلم البرمجة الحقيقية بلغة Python، وابدأ تحل مشكلات وتبني مشاريع بسيطة</p>
                    <a href="<?= base_url() ?>/pages/python_track"> اعرف المزيد ></a>
                    <img src="<?= base_url() ?>site/images/package/level_prog2.png" alt="Alternate Text" class="package_icon" />
                </div>


                <div>
                    <h4>تفاصيل المسار</h4>
                    <p>اتعلم كتابة كود حقيقي بلغة Python وابدأ تفهم المنطق البرمجي خطوة بخطوة.</p>

                    <ul>
                        <li>👦 حد أقصى 5 أطفال بالمجموعة</li>
                        <li>🧩 عدد المستويات داخل المسار: 2</li>
                        <li>📺 جلسة مباشرة أسبوعية مع المدرب</li>
                        <li>📝 مشاريع عملية ومهام مستمرة</li>
                        <li>💬 جروب دعم تليجرام لكل مستوى</li>
                    </ul>
                </div>

                <img src="<?= base_url() ?>site/images/package/arrow2.png" alt="Alternate Text" class="arrow" />
                <img src="<?= base_url() ?>site/images/package/arrow2_emp.png" alt="Alternate Text" class="arrow_empty" />
            </div>


            <div class="package_item package3">
                <div class="package_item_head">
                    <div>
                        <p>متقدم - Web Track</p>
                        <img src="<?= base_url() ?>site/images/package/globe.png" alt="Alternate Text" />
                    </div>
                    <p>خد مهاراتك لمستوى احترافي، وابدأ تتعلم تطوير مواقع الويب خطوة بخطوة</p>
                    <a href="<?= base_url() ?>/pages/web_track"> اعرف المزيد ></a>
                </div>

                <div class="package_item_head">
                    <div>
                        <p>متقدم - Mobile Track  </p>
                        <img src="<?= base_url() ?>site/images/package/mobile.png" alt="Alternate Text" />
                    </div>
                    <p>تعلم بناء تطبيقات تفاعلية وقوية تعمل على أنظمة Android و iOS.</p>
                    <a href="<?= base_url() ?>/pages/mobile_track"> اعرف المزيد ></a>
                </div>


                <div>
                    <h4>تفاصيل المسار</h4>
                    <p>اختر مسارك الاحترافي وابدأ تطبيق مهاراتك في بناء مواقع أو تطبيقات الهاتف.</p>

                    <ul>
                        <li>👦 حد أقصى 5 أطفال بالمجموعة</li>
                        <li>🧩 عدد المستويات داخل المسار: 3</li>
                        <li>📺 جلسة مباشرة أسبوعية مع المدرب</li>
                        <li>📝 مشاريع عملية ومهام مستمرة</li>
                        <li>💬 جروب دعم تليجرام لكل مستوى</li>
                    </ul>
                </div>

                <img src="<?= base_url() ?>site/images/package/arrow1_emp.png" alt="Alternate Text" class="arrow_empty" />
            </div>
        </div>
    </div>
</div>
<!--Package-->
<br />

<!-- instructor -->
<div class="services-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-4 mb-5 mb-lg-0">

                <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom mb-4">م/أحمد فخر الدين</h2>
                </div>

                <p data-aos="fade-up" data-aos-delay="100">خبرة لأكثر من 11 سنة في مجال صناعة البرمجيات داخل المملكة
                    وخارجها ،أشرفت على العديد من الدورات الإلكترونية في أشهر المنصات الإلكترونية بالإضافة إلى :</p>

                <ul class="ul-check list-unstyled mb-5 primary" data-aos="fade-up" data-aos-delay="200">
                    <li>حاصل على شهادة MCSE من مايكروسوفت في ادارة الشبكات</li>
                    <li>حاصل على شهادة CISCO 101 لادارة الشبكات</li>
                    <li>أشرفت على العديد من مشارع التخرج من كلية علوم الحاسب</li>
                </ul>

                <p data-aos="fade-up" data-aos-delay="300"><a href="#" class="btn btn-primary">ابدأ الآن</a></p>

            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="0">
                <figure class="img-wrap-2">
                    <img src="<?= base_url() ?>site/images/profile_image.png" alt="Image" class="img-fluid">
                    <div class="dotted"></div>
                </figure>

            </div>
        </div>
    </div>
</div>

<!-- articles -->
<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">معك خطوة بخطوة على طول المسار</h2>
                <p>نقدم تجربة تعليمية غنية تستند إلى الممارسة العملية في مجال تكنولوجيا المعلومات، معززة بمحتوى مصمم
                    خصيصًا لطلاب الثانوية.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature">
                    <span class="uil uil-clock"></span>
                    <h3>دعم مستمر</h3>
                    <p>خدمة دعم متواصلة  لتلبية كل احتياجاتكم التعليمية والتقنية.</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature">
                    <span class="uil uil-video"></span>
                    <h3>دروس  متاحة دائماً</h3>
                    <p>استمتع بحضور الدروس مباشرة أو شاهدها مسجلة في أي وقت يناسب طفلك</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature">
                    <span class="uil uil-brain"></span>
                    <h3>تعلم ممتع ومحفز</h3>
                    <p>نوفر بيئة تعليمية ممتعة ومحفزة تشجع الأطفال على الإبداع والاستمرار في تعلم البرمجة.</p>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature">
                    <span class="uil uil-repeat"></span>
                    <h3>تعلم عملي</h3>
                    <p>تعلم البرمجة من خلال المشاريع العملية والألعاب التفاعلية لتطوير مهارات التفكير المنطقي.</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature">
                    <span class="uil uil-smile"></span>
                    <h3>دعم نفسي وإرشادي</h3>
                    <p>نوفر الإرشاد النفسي والدعم حتى بعد انتهاء مرحلة الثانوية لضمان تكامل التعليم والرفاهية.</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature">
                    <span class="uil uil-link"></span>
                    <h3>الربط بين المواد الدراسية</h3>
                    <p>نضمن ارتباط المواد ببعضها لتعميق الفهم وتحسين الاستيعاب لجميع مراحل المسار التعليمي.</p>
                </div>
            </div>
        </div>
    </div> <!-- /.container -->
</div>

<!-- /.untree_co-section -->

<div class="untree_co-section"  style=" overflow-x: hidden;">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5 mb-5">
                <h2 class="line-bottom mb-4" data-aos="fade-up" data-aos-delay="0">من نحن</h2>
                <p data-aos="fade-up" data-aos-delay="100">أول منصة تعليمية متخصصة في تدريس نظام المسارات لطلبة الثانوية
                    بالمملكة العربية السعودية.</p>
                <ul class="list-unstyled ul-check mb-5 primary" data-aos="fade-up" data-aos-delay="200">
                    <li>دورات خاصة عبر زووم</li>
                    <li>دورات مسجلة لسهولة الاسترجاع</li>
                    <li>معلمين متخصصين في كل مادة</li>
                    <li>متابعة مستمرة طوال أيام الأسبوع</li>
                    <li>دورات صيفية عملية</li>
                </ul>
                <p data-aos="fade-up" data-aos-delay="200">
                    <a href="#" class="btn btn-primary mr-1">التسجيل</a>
                    <a href="#" class="btn btn-outline-primary">اعرف المزيد</a>
                </p>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-1"></div>
                <a href="https://vimeo.com/342333493" data-fancybox class="video-wrap">
                    <span class="play-wrap"><span class="icon-play"></span></span>
                    <img src="<?= base_url() ?>site/images/img-school-4-min.jpg" alt="Image" class="img-fluid rounded">
                </a>
            </div>
        </div>
    </div>
</div> <!-- /.untree_co-section -->

<!-- Articles Loop -->
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">المدونة</h2>
                <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>
            </div>
        </div>

        <div class="row align-items-stretch">
            <?php if (!empty($articles)): ?>
                <?php
                // Display only 2 articles in this layout
                // If you want more, remove the limit or adjust the loop.
                $maxToShow = 2;
                foreach ($articles as $idx => $article):
                    if ($idx >= $maxToShow) {
                        break;
                    }
                    ?>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?= 100 * ($idx + 1) ?>">
                        <div class="media-h d-flex h-100">
                            <figure>
                                <!-- Replace with your actual article image or a fallback -->
                                <img
                                        src="<?= base_url('uploads/' . ($article['image'] ?? 'default.png')) ?>"
                                        alt="Article Image"
                                >
                            </figure>
                            <div class="media-h-body">
                                <h2 class="mb-3">
                                    <a href="<?= site_url('articles/show/' . $article['id']) ?>">
                                        <?= esc($article['title'] ?? 'بدون عنوان') ?>
                                    </a>
                                </h2>
                                <div class="meta mb-2">
                                    <span class="icon-calendar mr-2"></span>
                                    <span>
                                        <!-- Example: format created_at if it exists -->
                                        <?php if (!empty($article['created_at'])): ?>
                                            <?= date('d M Y', strtotime($article['created_at'])) ?>
                                        <?php else: ?>
                                            <?= date('d M Y') ?>
                                        <?php endif; ?>
                                    </span>
                                    <span class="icon-person mr-2"></span>الإدارة
                                </div>
                                <p>
                                    <!-- Short description if you have one -->
                                    <?= esc($article['short_desc'] ?? 'لا يوجد وصف قصير.') ?>
                                </p>
                                <p>
                                    <a href="<?= site_url('articles/show/' . $article['id']) ?>">اعرف المزيد</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>لا توجد مقالات متاحة حالياً.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> <!-- /.untree_co-section -->

<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center" data-aos="fade-up" data-aos-delay="0">
                <figure>
                    <img src="<?= base_url() ?>site/images/gurantee.png" alt="ضمان الاسترداد" class="img-fluid rounded">
                </figure>
                <h2 class="mb-4">ضمان استعادة المال</h2>
                <p>المحتوى المقدم على المنصة من قبل معلمين شغوفين بما يقدمون ، وليسوا فقط اكاديميون مترمرسون،لذا فهناك
                    ثقة تامة بأنه سوف ينال رضاءكم التام بإذن الله، ولكن أخي وأختي في حالة عدم رضاءكم يمكنكم طلب استرداد
                    كامل المبلغ ودون سؤال واحد</p>
                <p><a href="#" class="btn btn-primary">اعرف المزيد</a></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
