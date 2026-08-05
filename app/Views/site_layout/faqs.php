<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Hero Section -->
<div class="untree_co-hero overlay" style="background-image: url('<?= base_url() ?>site/images/main_banner.webp'); padding: 120px 0 80px 0;">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">
                    الأسئلة الشائعة
                </h1>
                <p class="mb-4 text-white" style="font-size: 1.1rem; line-height: 1.6; max-width: 800px; margin: 0 auto;" data-aos="fade-up" data-aos-delay="200">
                    إجابات على أكثر الأسئلة شيوعاً حول منصة فخر CS ومقررات الجامعة السعودية الإلكترونية.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- FAQs Section -->
<div class="untree_co-section bg-light" id="faqs-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                
                <?php if (empty($faqs)): ?>
                    <div class="alert alert-info text-center">
                        لا توجد أسئلة شائعة في الوقت الحالي.
                    </div>
                <?php else: ?>
                    <div class="accordion" id="accordionFaqs">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <div class="card mb-3" style="border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                <div class="card-header bg-white" id="heading<?= $index ?>" style="border-radius: 10px; padding: 0; border-bottom: none;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-right font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>" style="color: #1a202c; text-decoration: none; padding: 20px;">
                                            <?= esc($faq['question']) ?>
                                            <i class="fas fa-chevron-down float-left mt-1" style="color: #007bff; font-size: 0.9rem;"></i>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse<?= $index ?>" class="collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-parent="#accordionFaqs">
                                    <div class="card-body" style="color: #555; line-height: 1.7; padding: 0 20px 20px 20px; border-top: 1px solid #eee;">
                                        <?= $faq['answer'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
