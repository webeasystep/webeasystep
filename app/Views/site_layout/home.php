هذه النقطة هي "الجوكر" الخاص بك. في علم التسويق، هذا يسمى **"Niche Specialization"**.

العميل (الطالب) دائماً يثق في "الأخصائي" أكثر من "الممارس العام". عندما تقول له: *"أنا لا أبيع لكل الجامعات، أنا أبيع لكليتك أنت فقط"*، يرتفع معدل الثقة فوراً.

بصفتي خبير UX و Copywriting، المكان المثالي لهذه الرسالة ليس في الأسفل، بل **فوراً بعد الـ Hero Section (الواجهة)** وقبل عرض المميزات. لماذا؟ لكي تصفي الجمهور فوراً وتقول له: *"نعم، أنت في المكان الصحيح، هذا الموقع صُمم لك خصيصاً"*.

إليك الكود المعدل بالكامل. أضفت قسماً جديداً (Section) بتصميم مميز يبرز هذه النقاط بوضوح بصري (Visual Hierarchy) لا يمكن تجاهله.

**انسخ الكود التالي بالكامل (تم دمج القسم الجديد باحترافية):**

```php
<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>
    <div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/main_banner.jpg');">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-12">
                    <div class="row justify-content-center ">
                        <div class="col-lg-8 text-center ">
                            <a href="https://www.youtube.com/@web_easy_step" target="_blank" class="d-inline-block mb-3">
                                <span class="badge badge-warning text-dark py-2 px-3" style="font-size: 0.9rem; border-radius: 20px;">
                                    <i class="fab fa-youtube text-danger mr-1"></i> انضم لأكثر من 20,000 مبرمج تعلموا معنا
                                </span>
                            </a>

                            <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">
                                ضمن الـ A+ في مواد التخصص (CS/IT)<br>بدون حفظ سلايدات الجامعة المملة
                            </h1>
                            
                            <p class="mb-4 text-white" style="font-size: 1.2rem; line-height: 1.6;" data-aos="fade-up" data-aos-delay="200">
                                شرح عملي مخصص لطلاب <strong>الجامعة السعودية الإلكترونية (SEU)</strong>.. نفهمك كيف الكود بيشتغل في الرامات، مش بس بنقرأ المحاضرة.
                            </p>

                            <p class="mb-0" data-aos="fade-up" data-aos-delay="300">
                                <a href="#courses" class="btn btn-primary btn-lg" style="padding: 15px 40px; font-weight: bold;">اختر مادتك وابدأ الآن</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section py-5" style="background-color: #fff; border-bottom: 1px solid #eee;">
        <div class="container">
             <div class="row justify-content-center text-center mb-4">
                 <div class="col-lg-8">
                     <span class="badge badge-primary text-uppercase mb-2" style="letter-spacing: 1px;">التخصص سر التفوق</span>
                     <h2 style="font-size: 1.8rem; font-weight: 800;">ليه FakhrCS مش زي أي منصة تانية؟</h2>
                     <p class="text-muted">أغلب المنصات "بتاعة كله".. بتشرح لكل الجامعات ولكل التخصصات (إدارة، قانون، آداب..).<br> <strong class="text-dark">إحنا هنا متخصصين في ملعب واحد بس:</strong></p>
                 </div>
             </div>
             
             <div class="row align-items-center justify-content-center">
                 <div class="col-md-5 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100">
                     <div class="p-4 bg-light shadow-sm rounded border" style="border-right: 5px solid #007bff !important;"> <div class="d-flex align-items-center">
                            <div class="icon ml-3 text-primary"><i class="fas fa-university fa-3x"></i></div> <div class="text">
                                <h5 class="font-weight-bold mb-1">كلية الحوسبة والمعلوماتية فقط</h5>
                                <p class="mb-0 small text-muted">إحنا مش بنشتت نفسنا. تركيزنا 100% على منهج (CCI) في الجامعة السعودية الإلكترونية.</p>
                            </div>
                        </div>
                     </div>
                 </div>

                 <div class="col-md-7" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-4 bg-white shadow-sm rounded border">
                        <h6 class="font-weight-bold text-center mb-4">بنخدم برامج البكالوريوس الثلاثة حصرياً:</h6>
                        <div class="row text-center">
                            <div class="col-4 border-left">
                                <i class="fas fa-network-wired text-info mb-2 fa-2x"></i>
                                <h6 class="small font-weight-bold">IT</h6>
                                <span class="d-block x-small text-muted">تقنية المعلومات</span>
                            </div>
                            <div class="col-4 border-left">
                                <i class="fas fa-code text-warning mb-2 fa-2x"></i>
                                <h6 class="small font-weight-bold">CS</h6>
                                <span class="d-block x-small text-muted">علوم الحاسب</span>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-database text-success mb-2 fa-2x"></i>
                                <h6 class="small font-weight-bold">DS</h6>
                                <span class="d-block x-small text-muted">علوم البيانات</span>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">إزاي بنحل مشكلة "الشرح النظري"؟</h2>
                    <p class="lead">لاننا متخصصين في موادك، عارفين إن الامتحان بيجي "فهم" مش حفظ.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature text-center">
                        <div class="icon-wrap bg-primary text-white d-inline-block rounded-circle mb-3" style="width: 60px; height: 60px; line-height: 60px; font-size: 24px;">
                            <span class="uil uil-brain"></span>
                        </div>
                        <h3>فهم عميق (Memory Model)</h3>
                        <p>مش هنحفظ أكواد. هنرسم الرامات ونشوف الداتا بتتحرك ازاي (Stack vs Heap) عشان تحل أي سؤال Tracing في الامتحان.</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature text-center">
                        <div class="icon-wrap bg-primary text-white d-inline-block rounded-circle mb-3" style="width: 60px; height: 60px; line-height: 60px; font-size: 24px;">
                            <span class="uil uil-file-check-alt"></span>
                        </div>
                        <h3>بنك التجميعات (The Exam Hack)</h3>
                        <p>ملخصات لأهم أسئلة الميدتيرم والفاينل للسنوات السابقة، محلولة ومشروحة بالتفصيل. (دي الخلاصة).</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature text-center">
                        <div class="icon-wrap bg-primary text-white d-inline-block rounded-circle mb-3" style="width: 60px; height: 60px; line-height: 60px; font-size: 24px;">
                            <span class="uil uil-user-md"></span>
                        </div>
                        <h3>شرح مهندس مش دكتور</h3>
                        <p>المهندس أحمد فخر الدين (خبرة 12 سنة) بيشرحلك المادة بأسلوب سوق العمل، فبتفهم الـ Why قبل الـ How.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section bg-light" id="courses">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">المواد المتاحة للفصل الحالي</h2>
                    <p>اختر المادة اللي شايل همها، واترك الباقي علينا.</p>
                </div>
            </div>
            <div class="row">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-4">
                            <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; transition: transform 0.3s;">
                                <div style="position: relative;">
                                    <img
                                        alt="<?= esc($course['course_title']) ?>"
                                        style="object-fit: cover; height: 200px; width: 100%;"
                                        src="<?= thumb($course['image'], 540, 400) ?>"
                                        class="card-img-top"
                                    >
                                    <span style="position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                        🔥 التسجيل مفتوح
                                    </span>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2" style="font-weight: 700;">
                                        <?= esc($course['course_title']) ?>
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        <?= esc(mb_substr($course['short_desc'] ?? '', 0, 80)) ?>...
                                    </p>
                                    
                                    <ul class="list-unstyled small text-muted mb-3" style="flex-grow: 1;">
                                        <li><i class="uil uil-check text-success"></i> شرح المنهج كاملاً</li>
                                        <li><i class="uil uil-check text-success"></i> حل تجميعات الميدتيرم</li>
                                    </ul>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <div style="font-size: 1.4rem; font-weight: 800; color: #333;">
                                            <?php if ($course['is_free']): ?>
                                                <span class="text-success">مجاني</span>
                                            <?php else: ?>
                                                <span><?= number_format($course['course_price'] ?? 0) ?></span>
                                                <span style="font-size: 0.8rem; color: #777;">ر.س</span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?= base_url('courses/course_details/' . $course['slug']) ?>" 
                                           class="btn btn-primary" 
                                           style="border-radius: 8px; font-weight: 600; padding: 8px 25px;">
                                           اشترك الآن
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                     <div class="col-12 text-center">
                         <p class="text-muted">جاري تحديث المواد للفصل الدراسي الجديد...</p>
                     </div>
                 <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="services-section" style="padding: 5em 0;">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <div class="section-title mb-3" data-aos="fade-up" data-aos-delay="0">
                        <h2 class="line-bottom mb-4">مين هو المهندس أحمد فخر الدين؟</h2>
                    </div>
                    <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                        "أنا مش دكتور جامعة، أنا Software Engineer بشتغل بإيدي كل يوم."
                    </p>
                    <p data-aos="fade-up" data-aos-delay="100">
                        بخبرة عملية +12 سنة في الـ Backend Development، ومؤسس قناة <strong>(الويب خطوة سهلة)</strong> التقنية.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="200">
                        هدفي إنك تتأسس صح عشان لما تتخرج تلاقي وظيفة بمرتب محترم، لأنك فاهم اللي بيحصل ورا الكواليس مش حافظه.
                    </p>
                    
                    <div class="row mt-4" data-aos="fade-up" data-aos-delay="300">
                         <div class="col-6">
                             <h3 class="text-primary mb-0" style="font-weight: 800;">+20,000</h3>
                             <span class="small text-muted">مشترك على يوتيوب</span>
                         </div>
                         <div class="col-6">
                             <h3 class="text-primary mb-0" style="font-weight: 800;">+12</h3>
                             <span class="small text-muted">سنة خبرة برمجية</span>
                         </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="400">
                    <figure class="img-wrap-2">
                        <img src="<?= base_url() ?>site/images/fakhrcs.jpg" alt="م/ أحمد فخر الدين" class="img-fluid" style="border-radius: 15px;">
                        
                        <div style="position: absolute; bottom: -30px; right: -20px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); max-width: 350px;">
                             <div class="d-flex align-items-start">
                                 <i class="fas fa-quote-right text-primary mr-3" style="font-size: 24px;"></i>
                                 <div>
                                     <p class="mb-2 small font-weight-bold" style="line-height: 1.6; font-style: italic;">
                                         "أشكر شعب مصر وعلماؤها هم من ينير الأمة العربية بالعلم والشرح والنشر.."
                                     </p>
                                     <div class="d-flex align-items-center">
                                         <i class="fab fa-youtube text-danger mr-2"></i>
                                         <small class="text-muted">تعليق متابع (Saif Amir)</small>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section bg-primary text-white text-center">
        <div class="container">
             <h2 class="text-white mb-3">لسه متردد؟ جرب أول محاضرة مجاناً</h2>
             <p class="mb-4 text-white-50">شوف الفرق بنفسك في أسلوب الشرح قبل ما تدفع ريال واحد.</p>
             <a href="#courses" class="btn btn-light btn-lg font-weight-bold">تصفح المواد الآن</a>
        </div>
    </div>

<?= $this->endSection(); ?>

```