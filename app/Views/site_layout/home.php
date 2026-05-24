<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4T7TS76"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
    $primaryCourse = null;

    if (! empty($courses)) {
        foreach ($courses as $phaseCourse) {
            if ((int) ($phaseCourse['waiting_list'] ?? 0) === 0) {
                $primaryCourse = $phaseCourse;
                break;
            }
        }
    }
    ?>
    <div class="untree_co-hero overlay" style="background-color: #0a1730; background-image: url('<?= base_url() ?>site/images/vibe_coding_mastery.jpg'); background-position: center 50px; background-size: cover;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-12">
                    <div class="row justify-content-end">
                        <div class="col-lg-7 text-center " style="padding-left: 5%;">

                            <p class="text-warning font-weight-bold mb-3" data-aos="fade-up" data-aos-delay="50" style="letter-spacing: .5px; font-size: 1.1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                أي شخص يمكنه بناء Demo بالـ AI.. المحترفون فقط يطلقون Product حقيقي 🚀
                            </p>

                            <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100" style="font-weight: 900; line-height: 1.4; text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                                من <span style="color: #60a5fa;">مجرد Demo</span>.. إلى <span style="color: #34d399;">Product حقيقي</span>
                            </h1>

                            <p class="lead text-white mb-4" data-aos="fade-up" data-aos-delay="200" style="line-height: 1.9; font-size: 1.15rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">
                                الذكاء الاصطناعي جعل بناء التطبيقات سهلاً، لكن القيمة الحقيقية الآن في <strong>"هندسة النظام"</strong>.
                                انضم لمسار <strong>Vibe to Launch</strong> وتحول من مجرد "كاتب أوامر للـ AI" إلى مهندس منتجات محترف.
                            </p>

                            <p class="mb-0 mt-4" data-aos="fade-up" data-aos-delay="300">
                                <a href="<?= esc(base_url('courses/course_details/' . ($primaryCourse['slug'] ?? 'phase-1-ai-communication-prompt-mastery'))) ?>" class="btn btn-primary btn-lg" style="padding: 15px 40px; font-weight: bold;">
                                    ابدأ الآن بالمرحلة الأولى (بـ 21$ فقط)
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section py-5" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <span class="badge mb-3 px-4 py-2 shadow-sm" style="background-color: #fee2e2; color: #991b1b; font-size: 0.95rem; font-weight: bold; border-radius: 50px;">⚠️ الفخ الذي يقع فيه المطورون</span>
                    <h2 class="mb-3" style="font-size: 2.2rem; font-weight: 800; color: #1a202c; line-height: 1.4;">لا تكتفِ بـ Demo ينهار بعد أول تعديل!</h2>
                    <p class="text-muted mb-0" style="font-size: 1.15rem; line-height: 1.8;">
                        الاعتماد الأعمى على أدوات الذكاء الاصطناعي يخلق كوداً عشوائياً (Spaghetti Code) ينهار مع أول ميزة تضيفها.
                        الحل ليس في التوقف عن استخدام الـ AI، بل في أن تقوده كـ <strong>"مهندس برمجيات"</strong> يفهم ما يحدث خلف الكواليس.
                    </p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="specialization-card p-4 rounded-lg shadow" style="background: linear-gradient(135deg, #136ad5 0%, #0d5bba 100%) !important; border-radius: 16px !important;">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #ffffff; border-radius: 50%;">
                                    <i class="fas fa-music fa-2x" style="color: #136ad5;"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="font-weight-bold mb-2" style="color: #ffffff !important; font-size: 1.25rem;">المشكلة ليست في سرعة البناء.. بل في غياب القيادة</h3>
                                <p class="mb-0" style="color: rgba(255,255,255,0.9) !important; font-size: 1rem;">
                                    عندما تتعلم كيف تضع القيود، تنظم الأدوات، وتفهم بنية النظام، لن يصبح الذكاء الاصطناعي مصدر تشويش،
                                    بل مضاعفًا حقيقيًا للإنتاجية تحت قيادتك أنت.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section py-4">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4" style="font-weight: 800;">منهجية عمل تحولك من هاوٍ إلى صانع منتجات</h2>
                    <p class="lead" style="color: #4a5568;">كل مرحلة في هذا المسار تبني على ما قبلها، لتتأكد أنك لن تتوقف عند مجرد فكرة جميلة غير قابلة للتطبيق.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature text-center position-relative">
                        <div class="icon-wrap bg-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 65px; height: 65px; border: 4px solid #eff6ff;">
                            <span style="color: #ffffff; font-size: 1.5rem; font-weight: 900;">1</span>
                        </div>
                        <h3 style="font-size: 1.2rem; font-weight: 700;">السيطرة على الـ AI</h3>
                        <p style="color: #64748b; font-size: 0.95rem;">توجيه الذكاء الاصطناعي بذكاء وتحديد القيود الصحيحة بدلاً من التخمين العشوائي.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature text-center position-relative">
                        <div class="icon-wrap bg-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 65px; height: 65px; border: 4px solid #eff6ff;">
                            <span style="color: #ffffff; font-size: 1.5rem; font-weight: 900;">2</span>
                        </div>
                        <h3 style="font-size: 1.2rem; font-weight: 700;">بيئة العمل الاحترافية</h3>
                        <p style="color: #64748b; font-size: 0.95rem;">استخدام الـ Version Control والأدوات الحديثة للحفاظ على استقرار مشروعك.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature text-center position-relative">
                        <div class="icon-wrap bg-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 65px; height: 65px; border: 4px solid #eff6ff;">
                            <span style="color: #ffffff; font-size: 1.5rem; font-weight: 900;">3</span>
                        </div>
                        <h3 style="font-size: 1.2rem; font-weight: 700;">هندسة النظم</h3>
                        <p style="color: #64748b; font-size: 0.95rem;">فهم المعمارية الصحيحة، وإدارة قواعد البيانات لضمان عدم انهيار التطبيق.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature text-center position-relative">
                        <div class="icon-wrap bg-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 65px; height: 65px; border: 4px solid #eff6ff;">
                            <span style="color: #ffffff; font-size: 1.5rem; font-weight: 900;">4</span>
                        </div>
                        <h3 style="font-size: 1.2rem; font-weight: 700;">الإطلاق الحقيقي</h3>
                        <p style="color: #64748b; font-size: 0.95rem;">الاختبار، التعامل مع الثغرات، وتسليم منتج حقيقي لمستخدمين حقيقيين.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section bg-light" id="courses">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 text-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="line-bottom text-center mb-4">خارطة Vibe to Launch</h2>
                    <p>ابدأ بالمرحلة المتاحة الآن، واحجز أسبقيتك في المراحل القادمة قبل فتحها رسميًا.</p>
                </div>
            </div>
            <div class="row">
                <?php
                $gradients = [
                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                    'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                    'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
                    'linear-gradient(135deg, #ff0844 0%, #ffb199 100%)',
                ];
                $icons = ['fa-cubes', 'fa-project-diagram', 'fa-database', 'fa-network-wired', 'fa-cogs', 'fa-globe', 'fa-brain'];
                $courseIndex = 0;
                ?>
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <?php
                        $hasImage = !empty($course['image']) && $course['image'] !== '[]';
                        $gradient = $gradients[$courseIndex % count($gradients)];
                        $icon = $icons[$courseIndex % count($icons)];
                        $isWaitlist = !empty($course['waiting_list']) && (int) $course['waiting_list'] === 1;
                        $isActivePhase = !$isWaitlist;
                        $courseActionUrl = base_url('courses/course_details/' . $course['slug']);
                        $courseIndex++;
                        ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">
                            <div class="course-card h-100" style="<?= $isActivePhase ? 'border: 2px solid rgba(19,106,213,.25); box-shadow: 0 18px 40px rgba(19,106,213,.12);' : 'opacity: .92; filter: grayscale(.15); border: 1px solid #d9e2ef;' ?>">
                                <?php if ($hasImage): ?>
                                    <div class="course-card-image" style="height: auto; aspect-ratio: 16/9; <?= $isWaitlist ? 'position: relative;' : '' ?>">
                                        <img src="<?= thumb($course['image'], 400, 225) ?>" alt="<?= esc($course['course_title']) ?>" class="course-img" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover; object-position: top center; border-top-left-radius: inherit; border-top-right-radius: inherit;">
                                        <span class="course-badge"><?= $isActivePhase ? 'متاحة الآن' : 'قريباً' ?></span>
                                        <?php if ($isWaitlist): ?>
                                            <span class="course-badge" style="left: 12px; right: auto; background: rgba(15, 23, 42, 0.85);"><i class="fas fa-lock"></i></span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="course-card-image" style="height: auto; background: <?= $gradient ?>; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; border-top-left-radius: inherit; border-top-right-radius: inherit; <?= $isWaitlist ? 'position: relative;' : '' ?>">

                                        <div class="course-icon"><i class="fas <?= $icon ?>" style="font-size: 3rem; color: rgba(255,255,255,0.8);"></i></div>
                                        <span class="course-badge"><?= $isActivePhase ? 'متاحة الآن' : 'قريباً' ?></span>
                                        <?php if ($isWaitlist): ?>
                                            <span class="course-badge" style="left: 12px; right: auto; background: rgba(15, 23, 42, 0.85);"><i class="fas fa-lock"></i></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="course-card-body">
                                    <h5 class="course-title" style="font-size: 1.1rem; line-height: 1.5; min-height: 55px; font-weight: 800; color: #1e293b;"><?= esc($course['course_title']) ?></h5>
                                    <div class="course-codes" style="font-size: 0.85rem; color: #136ad5; font-weight: 700; margin-bottom: 10px;"><?= esc($course['short_desc'] ?? '') ?></div>
                                    <p class="course-value" style="font-size: 0.92rem; line-height: 1.7; min-height: 140px; color: #64748b;"><?= esc($course['course_desc'] ?? '') ?></p>
                                    <div class="course-footer">
                                        <div class="course-price">
                                            <?php if (!empty($course['is_free']) && $course['is_free']): ?>
                                                <span class="price-amount text-success">مجاني</span>
                                            <?php else: ?>
                                                <?php
                                                $discountedPrice = $course['course_price'] ?? 135;
                                                ?>
                                                <span class="price-amount"><?= number_format($discountedPrice) ?></span>
                                                <span class="text-muted mr-1" style="font-size: 0.8rem; font-weight: bold;">$</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($isWaitlist): ?>
                                            <button type="button" class="btn btn-light btn-block border text-secondary d-flex align-items-center justify-content-center" style="font-weight: bold; gap: 8px;" onclick="handleSubscribe('<?= esc($course['course_title']) ?>')">
                                                <i class="fas fa-lock"></i> قائمة الانتظار
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= esc($courseActionUrl) ?>" class="btn btn-primary btn-block text-white shadow-sm" style="font-weight: bold;">
                                                اشترك الآن
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="untree_co-section py-5" style="background-color: #fffdf2; border-top: 1px solid #fef3c7; border-bottom: 1px solid #fef3c7;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center">
                    <div style="border: 2px dashed #d4af37; padding: 50px 30px; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(212,175,55,0.08);">
                        <i class="fas fa-shield-alt fa-3x mb-3" style="color: #d4af37;"></i>
                        <h2 style="color: #8a6d1c; font-weight: 900; margin-bottom: 15px; font-size: 2rem;">ضمان استرداد أموالك لمدة 30 يومًا</h2>
                        <p class="lead mb-4" style="color: #4a5568; font-weight: 600; line-height: 1.8;">
                            اشترك في المرحلة الأولى الآن بدون مخاطرة. إذا لم تشعر أن المحتوى أضاف لك قيمة عملية حقيقية خلال أول 30 يومًا، يمكنك طلب استرداد كامل لاشتراكك فوراً.
                        </p>
                        <div class="d-inline-block px-4 py-2 mb-4 rounded-pill" style="background-color: #fef3c7; color: #92400e; font-weight: 700; font-size: 0.95rem;">
                            ✨ ميزة حصرية: المشتركون الأوائل يحصلون على خصم 25% على جميع المراحل القادمة.
                        </div>
                        <br>
                        <a href="<?= esc(base_url('courses/course_details/' . ($primaryCourse['slug'] ?? 'phase-1-ai-communication-prompt-mastery'))) ?>" class="btn btn-primary btn-lg shadow" style="font-weight: bold; padding: 14px 35px;">
                            احجز مكانك في المرحلة الأولى الآن
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="untree_co-section bg-primary text-white text-center py-5">
        <div class="container">
            <h2 class="text-white mb-4" style="font-weight: 900; font-size: 2.2rem;">لا تنتظر حتى تتحول الفوضى إلى عبء مكلف</h2>
            <p class="mb-5 text-white-50" style="font-size: 1.15rem;">ابدأ من المرحلة الأولى الآن، واحجز أسبقيتك في المراحل التالية قبل فتحها لباقي الجمهور.</p>
            <a href="<?= esc(base_url('courses/course_details/' . ($primaryCourse['slug'] ?? 'phase-1-ai-communication-prompt-mastery'))) ?>" class="btn btn-light btn-lg font-weight-bold shadow-lg text-primary" style="padding: 16px 45px; border-radius: 50px;">
                ابدأ رحلتك الآن
            </a>
        </div>
    </div>

    <div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content subscribe-modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="modal-icon mb-4">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4 class="modal-title mb-3" id="subscribeModalLabel">هذه المرحلة ستفتح قريبًا</h4>
                    <p class="modal-message mb-4">
                        سجّل بريدك الآن لتكون من أوائل من يصله إشعار فتح المرحلة الجديدة، وتحصل على أولوية الانضمام والعروض المبكرة.<br>
                        <strong class="text-success">الانضمام المبكر يمنحك أفضلية حقيقية قبل الإطلاق الرسمي.</strong>
                    </p>
                    <div class="selected-course-name mb-3" id="selectedCourseName"></div>
                    <form id="interestForm" class="interest-form">
                        <div class="form-group mb-3">
                            <input type="email" class="form-control form-control-lg" id="userEmail" placeholder="أدخل بريدك الإلكتروني" required dir="ltr">
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn">
                            <i class="fas fa-crown ml-2"></i> انضم لقائمة الانتظار
                        </button>
                    </form>
                    <div class="success-message d-none mt-4" id="successMessage">
                        <div class="success-icon mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="text-success">تم تسجيلك في قائمة الانتظار بنجاح</h5>
                        <p class="text-muted">سيصلك إشعار فور فتح المرحلة الجديدة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>window.leadsApiUrl = '<?= base_url('leads/save') ?>';</script>

<?= $this->endSection(); ?>
