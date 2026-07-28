<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="untree_co-section py-5">
    <div class="container">
        <div class="consumer-page-hero mb-5" style="background: url('<?= base_url('site/images/students_learn.jpg') ?>') center center/cover;">
            <span class="consumer-page-badge"><i class="fas fa-star"></i> لماذا تختار FakhrCS؟</span>
            <h1 class="consumer-page-title">تجربة تعليمية متكاملة… تُحوّل غموض المقررات إلى نجاح أكاديمي باهر!</h1>
            <p class="consumer-page-subtitle">في <strong>FakhrCS</strong>، نعلم أن التميز في الجامعة السعودية الإلكترونية (SEU) لا يتطلب فقط حضور المحاضرات، بل يحتاج إلى فهم عميق، مراجعة مركزة، وتدريب ذكي على أساليب الاختبارات.</p>
        </div>

        <div class="consumer-section-card">
            <h3 class="consumer-section-title">ماذا يقدم لك اشتراكك في FakhrCS؟</h3>
            <ul class="consumer-check-list">
                <li><strong>محتوى مخصص 100% لـ SEU:</strong> جميع الشروحات والتطبيقات صُممت خصيصاً لتطابق المنهج الرسمي للجامعة وتوزيع الخطط الدراسية.</li>
                <li><strong>شرح مبسط وعملي:</strong> نبتعد عن التعقيد الأكاديمي ونركز على المفاهيم الأساسية، طريقة حل المشكلات، والتطبيقات البرمجية خطوة بخطوة.</li>
                <li><strong>ملخصات وتجميعات شاملة:</strong> نوفر لك ملخصات مركزة للمراجعة السريعة، مع تجميعات محلولة لأسئلة الاختبارات السابقة لتدخل الاختبار بجهوزية كاملة.</li>
                <li><strong>مراجعات الميدتيرم والفاينل:</strong> بث مباشر ومراجعات مكثفة مخصصة قبل الاختبارات النصية والنهائية لضمان تثبيت أهم الأفكار المتوقعة.</li>
                <li><strong>تدريبات واختبارات بعد كل Module:</strong> لتقييم فهمك أولاً بأول والتأكد من استيعاب التطبيقات العملية قبل الانتقال للوحدة التالية.</li>
                <li><strong>متابعة وتحديث مستمر:</strong> نحدث محتوانا فوراً مع أي تغيير منهج رسمي صادر من الجامعة لتدرس دائماً من أحدث نسخة.</li>
                <li><strong>مجتمع تليجرام تفاعلي:</strong> انضم لمجموعة مقررك على Telegram، اسأل المحاضر، تناقش مع زملائك، وكن على اطلاع بأحدث التنبيهات.</li>
            </ul>
        </div>

        <div class="row consumer-grid-row">
            <div class="col-md-4">
                <div class="consumer-stat-card">
                    <div class="consumer-stat-icon"><i class="fas fa-bullseye"></i></div>
                    <div class="consumer-stat-title">محتوى مخصص</div>
                    <p class="consumer-stat-text">كل ما نقدمه مبني لطلاب الجامعة السعودية الإلكترونية ويطابق المنهج الرسمي.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="consumer-stat-card">
                    <div class="consumer-stat-icon"><i class="fas fa-rocket"></i></div>
                    <div class="consumer-stat-title">مراجعات مركزة</div>
                    <p class="consumer-stat-text">ميدتيرم، فاينل، وتجميعات محلولة لتدخل الاختبار بوضوح أكبر وثقة أعلى.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="consumer-stat-card">
                    <div class="consumer-stat-icon"><i class="fas fa-comments"></i></div>
                    <div class="consumer-stat-title">متابعة مستمرة</div>
                    <p class="consumer-stat-text">قناة تليجرام وتحديثات دائمة حتى تبقى قريبًا من كل جديد في المقرر.</p>
                </div>
            </div>
        </div>

        <div class="consumer-helper-note mb-4">
            <p class="mb-0">ادرس بذكاء… وفي الوقت الذي يناسبك! انضم إلى آلاف الطلاب الذين اختاروا <strong>FakhrCS</strong> طريقاً للتمكّن الأكاديمي والدرجات المرتفعة.</p>
        </div>

        <div class="consumer-cta-card">
            <h3 class="consumer-section-title">ابدأ الآن</h3>
            <p class="mb-4">استكشف المقررات المتاحة وراجع الشروط والأحكام الخاصة بالطالب قبل التسجيل.</p>
            <a href="<?= site_url('terms-conditions') ?>" class="btn btn-outline-primary mr-2 mb-2">الشروط والأحكام</a>
            <a href="<?= site_url('register') ?>" class="btn btn-primary mb-2">تسجيل طالب</a>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
