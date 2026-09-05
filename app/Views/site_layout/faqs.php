<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Hero Section -->
<div class="untree_co-hero overlay compact-hero" style="background-image: url('<?= base_url() ?>site/images/main_banner.webp');">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h1 class="mb-3 heading text-white" data-aos="fade-up" data-aos-delay="100">
                    الأسئلة الشائعة
                </h1>
                <p class="mb-0 text-white" style="font-size: 1.15rem; line-height: 1.6; max-width: 800px; margin: 0 auto; opacity: 0.95;" data-aos="fade-up" data-aos-delay="200">
                    إجابات على أكثر الأسئلة شيوعاً حول منصة فخر CS ومقررات الجامعة السعودية الإلكترونية.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- FAQs Section -->
<div class="untree_co-section" id="faqs-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8" data-aos="fade-up" data-aos-delay="100">
                
                <?php if (empty($faqs)): ?>
                    <div class="alert alert-info text-center py-4" style="border-radius: 12px;">
                        لا توجد أسئلة شائعة في الوقت الحالي.
                    </div>
                <?php else: ?>
                    <div class="accordion" id="accordionFaqs">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <div class="card faq-accordion-card <?= $index === 0 ? 'is-open' : '' ?>">
                                <div class="card-header faq-card-header" id="heading<?= $index ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block faq-toggle-btn <?= $index === 0 ? '' : 'collapsed' ?>"
                                                type="button"
                                                data-toggle="collapse"
                                                data-target="#collapse<?= $index ?>"
                                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>"
                                                aria-controls="collapse<?= $index ?>">
                                            <span class="faq-question-title"><?= esc($faq['question']) ?></span>
                                            <span class="faq-chevron-badge">
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse<?= $index ?>"
                                     class="collapse <?= $index === 0 ? 'show' : '' ?>"
                                     aria-labelledby="heading<?= $index ?>"
                                     data-parent="#accordionFaqs">
                                    <div class="card-body faq-answer-body">
                                        <?= $faq['answer'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Telegram Contact Box -->
                    <div class="faq-telegram-box d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-right" style="gap: 16px;" data-aos="fade-up" data-aos-delay="200">
                        <div>
                            <h5 class="font-weight-bold mb-1">هل لديك سؤال آخر؟</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">تواصل معنا مباشرة عبر قناة وتيليجرام الدعم الفني والأكاديمي.</p>
                        </div>
                        <a href="https://t.me/fakhrcs" target="_blank" rel="noopener noreferrer" class="btn btn-primary px-4 py-2" style="border-radius: 10px; font-weight: 600; white-space: nowrap;">
                            <i class="fab fa-telegram-plane ml-1"></i> تواصل عبر تيليجرام
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined') {
        $('#accordionFaqs').on('show.bs.collapse', function (e) {
            $(e.target).closest('.faq-accordion-card').addClass('is-open');
        });
        $('#accordionFaqs').on('hide.bs.collapse', function (e) {
            $(e.target).closest('.faq-accordion-card').removeClass('is-open');
        });
    }
});
</script>

<?= $this->endSection(); ?>
