<?=$this->extend('site_layout/template');?>
<?=$this->section('content');?>
<!-- intro -->
<div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/hero-img-1-min.jpg');">

    <div class="container">
        <div class="row align-items-center justify-content-center">

            <div class="col-12">

                <div class="row justify-content-center ">

                    <div class="col-lg-6 text-center ">
                        <a href="#" data-fancybox data-aos="fade-up"
                           data-aos-delay="0" class="caption mb-4 d-inline-block">شاهد الفيديو</a>

                        <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">اصنع مستقبلك الرقمي</h1>
                        <p class="mb-3 small text-white" data-aos="fade-up" data-aos-delay="200">أول أكاديمية متخصصة في تدريس علوم الحاسب للطلبة وإعدادهم لوظائف المستقبل.</p>
                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300"><a href="#" class="btn btn-secondary">استكشف الدورات</a></p>

                    </div>


                </div>

            </div>

        </div> <!-- /.row -->
    </div> <!-- /.container -->

</div>
<!-- /.untree_co-hero -->

  <div class="untree_co-section">
      <div class="container">
          <div class="row justify-content-center mb-3">
              <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                  <h2 class="line-bottom text-center mb-4">تصفح الفئات الرئيسية</h2>
              </div>
          </div>
          <div class="row align-items-stretch">
              <?php
              foreach ($categories as $category):
                  ?>
                  <div class="col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="0">
                      <a href="<?= site_url('category/' . $category['slug']) ?>" class="category d-flex align-items-start h-100">
                          <div>
                              <i class="<?= esc($category['icon_class']) ?>"></i>
                          </div>
                          <div>
                              <h3><?= esc($category['name_ar']) ?></h3>
                              <span><?= esc($category['course_count']) ?> دورة</span>
                          </div>
                      </a>
                  </div>
              <?php endforeach; ?>
          </div>

          <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="400">
              <div class="col-lg-8 text-center">
                  <p>لدينا المزيد من الفئات هنا. <a href="<?= site_url('categories') ?>">تصفح الكل</a></p>
              </div>
          </div>
      </div>
  </div>

<div id="bookNow" class="untree_co-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">بادر الأن وقم بحجز مقعدك في درس مجاني!</h2>
                <p>استكشف مجموعة متنوعة من الدورات التي تغطي مسار علوم الحاسب الألي لبناء أساس متين في مجال تكنولوجيا المعلومات.</p>
            </div>
        </div>
        <div class="row">
            <?php
            foreach ($courses as $course): ?>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-4">
                    <div class="custom-media">
                        <a href="#"><img src="<?= base_url() ?>site/images/default_course.webp" alt="Course Image" class="img-fluid"></a>
                        <div class="custom-media-body">
                            <div class="d-flex justify-content-between pb-3">
                                <div class="text-primary"><span class="uil uil-book-open"></span> <span>قريباً</span></div>
                                <div class="review"><span class="icon-star"></span> <span>4.8</span></div>
                            </div>
                            <h3><?= esc($course['course_name']) ?></h3>
                            <div class="border-top d-flex justify-content-between pt-3 mt-3 align-items-center">
                                <div><span class="price">$<?= esc(number_format($course['price'], 2)) ?></span></div>
                                <!-- Button trigger modal -->
                                <a href="<?= base_url('courses/course_details/'.$course['slug']) ?>" class="btn btn-primary">تفاصيل</a>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#startNowModal" data-course="<?= esc($course['id']) ?>">سجل الآن</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->include('Modules\ContactUs\Views\Site\course_subscription_modal'); ?>
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
                    <img src="<?= base_url() ?>site/images/teacher-min.jpg" alt="Image" class="img-fluid">
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
                <p>نقدم تجربة تعليمية غنية تستند إلى الممارسة العملية في مجال تكنولوجيا المعلومات، معززة بمحتوى مصمم خصيصًا لطلاب الثانوية.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature">
                    <span class="uil uil-clock"></span>
                    <h3>دعم مستمر</h3>
                    <p>خدمة دعم متواصلة على مدار الساعة لتلبية كل احتياجاتكم التعليمية والتقنية.</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature">
                    <span class="uil uil-video"></span>
                    <h3>حصص مباشرة ومسجلة</h3>
                    <p>اختر بين الحضور المباشر أو مشاهدة الجلسات المسجلة بحرية تامة حسب جدولك الزمني.</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature">
                    <span class="uil uil-brain"></span>
                    <h3>دراسة عملية</h3>
                    <p>تعلم من خلال التطبيق العملي واكتسب مهارات حقيقية تستند إلى خبرات في صناعة البرمجيات.</p>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature">
                    <span class="uil uil-repeat"></span>
                    <h3>مراجعة في أي وقت</h3>
                    <p>جميع الحصص متاحة للمراجعة في أي وقت، مما يوفر مرونة فائقة في الدراسة.</p>
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

<div class="untree_co-section pt-0 bg-img overlay" style="background-image: url('<?= base_url() ?>site/images/img-school-1-min.jpg');">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-7">
                <h2 class="text-white mb-3" data-aos="fade-up" data-aos-delay="0">سجّل الآن واستثمر في مستقبلك.</h2>
                <p class="text-white h5 mb-4" data-aos="fade-up" data-aos-delay="100">انضم إلينا لتتقن علوم الحاسب وتكنولوجيا المعلومات من خلال منهج مصمم لتطوير القدرات وبناء الخبرات.</p>
                <p><a href="#" class="btn btn-secondary" data-aos="fade-up" data-aos-delay="200">سجل الآن</a></p>
            </div>
        </div>
    </div>
</div> <!-- /.untree_co-section -->

<div class="untree_co-section">
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

                <!--
                          <div class="row count-numbers mb-5">
                            <div class="col-4 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                              <span class="counter d-block"><span data-number="12023">0</span><span>+</span></span>
                              <span class="caption-2">عدد الطلاب</span>
                            </div>
                            <div class="col-4 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                              <span class="counter d-block"><span data-number="49">0</span><span></span></span>
                              <span class="caption-2">عدد المعلمين</span>
                            </div>
                            <div class="col-4 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                              <span class="counter d-block"><span data-number="12">0</span><span></span></span>
                              <span class="caption-2">عدد الجوائز</span>
                            </div>
                          </div>
                -->

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

<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
                <h2 class="line-bottom text-center mb-4">المدونة</h2>
                <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>
            </div>
        </div>
        <div class="row align-items-stretch">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="media-h d-flex h-100">
                    <figure>
                        <img src="<?= base_url() ?>site/images/img-school-1-min.jpg" alt="Image">
                    </figure>
                    <div class="media-h-body">
                        <h2 class="mb-3"><a href="#">التعليم لقادة الغد</a></h2>
                        <div class="meta mb-2"><span class="icon-calendar mr-2"></span><span>22 يونيو 2020</span> <span
                                    class="icon-person mr-2"></span>الإدارة
                        </div>
                        <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>
                        <p><a href="#">اعرف المزيد</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="media-h d-flex h-100">
                    <figure>
                        <img src="<?= base_url() ?>site/images/img-school-2-min.jpg" alt="Image">
                    </figure>
                    <div class="media-h-body">
                        <h2 class="mb-3"><a href="#">سجل أطفالك هذا الصيف للحصول على خصم 30%</a></h2>
                        <div class="meta mb-2"><span class="icon-calendar mr-2"></span><span>22 يونيو 2020</span> <span
                                    class="icon-person mr-2"></span>الإدارة
                        </div>
                        <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>
                        <p><a href="#">اعرف المزيد</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /.untree_co-section -->

<!--  <div class="untree_co-section">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
          <h2 class="line-bottom text-center mb-4">الأسعار</h2>
          <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4 mb-lg-0 col-lg-4" data-aos="fade-up" data-aos-delay="00">
          <div class="pricing">
            &lt;!&ndash; <div class="pricing-img mb-4"><img src="<?= base_url() ?>site/images/1x/asset-1.png" alt="Image" class="img-fluid"></div> &ndash;&gt;
            <div class="pricing-body">

              <h3 class="pricing-plan-title">مبتدئ</h3>
              <div class="price"><span class="fig">$50.99</span><span>/شهر</span></div>
              <p class="mb-4">بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>

              <p><a href="#" class="btn btn-outline-primary">ابدأ الآن</a></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 mb-lg-0 col-lg-4" data-aos="fade-up" data-aos-delay="200">
          <div class="pricing">
            &lt;!&ndash; <div class="pricing-img mb-4"><img src="<?= base_url() ?>site/images/1x/asset-2.png" alt="Image" class="img-fluid"></div> &ndash;&gt;
            <div class="pricing-body">

              <h3 class="pricing-plan-title">أعمال</h3>
              <div class="price"><span class="fig">$99.99</span><span>/شهر</span></div>
              <p class="mb-4">بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>

              <p><a href="#" class="btn btn-primary">ابدأ الآن</a></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 mb-lg-0 col-lg-4" data-aos="fade-up" data-aos-delay="300">
          <div class="pricing">
            &lt;!&ndash; <div class="pricing-img mb-4"><img src="<?= base_url() ?>site/images/1x/asset-3.png" alt="Image" class="img-fluid"></div> &ndash;&gt;
            <div class="pricing-body">

              <h3 class="pricing-plan-title">بريميوم</h3>
              <div class="price"><span class="fig">$199.99</span><span>/شهر</span></div>
              <p class="mb-4">بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء.</p>

              <p><a href="#" class="btn btn-outline-primary">ابدأ الآن</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>--> <!-- /.untree_co-section -->

<!--  <div class="untree_co-section bg-light">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 text-center mx-auto">

          <h3 class="line-bottom mb-4">شهادات</h3>
          <div class="owl-carousel wide-slider-testimonial">
            <div class="item">
              <blockquote class="block-testimonial">

                <p>
                  &ldquo;بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء. منفصلة يعيشون في بوكماركسجروف على ساحل السيمانتكس، المحيط اللغوي الكبير.&rdquo;</p>
                <div class="author">
                  <img src="<?= base_url() ?>site/images/person_1.jpg" alt="قالب مجاني من TemplateUX">
                  <h3>جون دو</h3>
                  <p class="position">الرئيس التنفيذي، المؤسس</p>
                </div>
              </blockquote>
            </div>

            <div class="item">
              <blockquote class="block-testimonial">

                <p>
                  &ldquo;عندما وصلت إلى أول تلال جبال الإيطاليك، كانت تلقي نظرة أخيرة خلفها على أفق مسقط رأسها بوكماركسجروف، عنوان قرية الأبجدية والسطر الفرعي لطريقها، لين لين. تساءلت بحسرة عندما مرت سؤال خطابي على خدها، ثم واصلت طريقها.&rdquo;</p>
                <div class="author">
                  <img src="<?= base_url() ?>site/images/person_2.jpg" alt="قالب مجاني من TemplateUX">
                  <h3>جيمس وودلاند</h3>
                  <p class="position">مصمم في فيسبوك</p>
                </div>
              </blockquote>
            </div>

            <div class="item">
              <blockquote class="block-testimonial">

                <p>
                  &ldquo;نهر صغير يدعى دودن يتدفق بجانب مكانهم ويمدهم بالريجاليا اللازمة. إنها بلاد الفردوس، حيث تطير أجزاء مشوية من الجمل إلى فمك.&rdquo;</p>
                <div class="author">
                  <img src="<?= base_url() ?>site/images/person_3.jpg" alt="قالب مجاني من TemplateUX">
                  <h3>روب سميث</h3>
                  <p class="position">مصمم المنتجات في تويتر</p>
                </div>
              </blockquote>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>-->
<!--

  <div class="untree_co-section">


    <div class="container">
      <div class="row">
        <div class="col-lg-5 mr-auto mb-5 mb-lg-0"  data-aos="fade-up" data-aos-delay="0">
          <img src="<?= base_url() ?>site/images/img-school-5-min.jpg" alt="صورة" class="img-fluid">
        </div>
        <div class="col-lg-7 ml-auto" data-aos="fade-up" data-aos-delay="100">
          <h3 class="line-bottom mb-4">لماذا تختارنا</h3>
          <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء. </p>

          <div class="custom-accordion" id="accordion_1">
            <div class="accordion-item">
              <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">معلمون وموظفون جيدون</button>
              </h2>

              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion_1">
                <div class="accordion-body">
                  <div class="d-flex">
                    <div class="accordion-img mr-4">
                      <img src="<?= base_url() ?>site/images/img-school-1-min.jpg" alt="صورة" class="img-fluid">
                    </div>
                    <div>
                      <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء. </p>
                      <p>منفصلة يعيشون في بوكماركسجروف على ساحل السيمانتكس، المحيط اللغوي الكبير.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div> &lt;!&ndash; .accordion-item &ndash;&gt;

            <div class="accordion-item">
              <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">نقدر الشخصيات الجيدة</button>
              </h2>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_1">
                <div class="accordion-body">
                  <div class="d-flex">
                    <div class="accordion-img mr-4">
                      <img src="<?= base_url() ?>site/images/img-school-2-min.jpg" alt="صورة" class="img-fluid">
                    </div>
                    <div>
                      <p>بعيداً جداً، خلف جبال الكلمات، بعيداً عن بلاد فوكاليا وكونسونانتيا، تعيش النصوص العمياء. </p>
                      <p>منفصلة يعيشون في بوكماركسجروف على ساحل السيمانتكس، المحيط اللغوي الكبير.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div> &lt;!&ndash; .accordion-item &ndash;&gt;
            <div class="accordion-item">
              <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">أطفالكم في أمان</button>
              </h2>

              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion_1">
                <div class="accordion-body">
                  <div class="d-flex">
                    <div class="accordion-img mr-4">
                      <img src="<?= base_url() ?>site/images/img-school-3-min.jpg" alt="صورة" class="img-fluid">
                    </div>
                    <div>
                      <p>عندما وصلت إلى أول تلال جبال الإيطاليك، كانت تلقي نظرة أخيرة خلفها على أفق مسقط رأسها بوكماركسجروف، عنوان قرية الأبجدية والسطر الفرعي لطريقها، لين لين.</p>
                      <p>تساءلت بحسرة عندما مرت سؤال خطابي على خدها، ثم واصلت طريقها.</p>
                    </div>
                  </div>

                </div>
              </div>

            </div> &lt;!&ndash; .accordion-item &ndash;&gt;

          </div>

        </div>
      </div>
    </div>
  </div>-->
<!-- /.untree_co-section -->

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

<?=$this->endSection();?>
