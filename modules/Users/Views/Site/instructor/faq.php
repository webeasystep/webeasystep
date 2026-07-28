<?= $this->extend('Modules\Users\Views\Site\instructor\layout'); ?>

<?= $this->section('instructor_page_content'); ?>
<div class="instructor-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">الأسئلة الشائعة</h2>
            <p class="text-muted mb-0">أكثر الأسئلة شيوعًا للمحاضرين مع إجابات واضحة يمكن تحديثها لاحقًا.</p>
        </div>
        <span class="badge text-bg-primary"><?= count($faq_items ?? []) ?> سؤال</span>
    </div>

    <?php if (!empty($faq_items)) : ?>
        <?php foreach ($faq_items as $item) : ?>
            <div class="faq-item" data-page-search="<?= esc(($item['question'] ?? '') . ' ' . ($item['answer'] ?? '')) ?>">
                <div class="faq-question"><?= esc($item['question'] ?? '') ?></div>
                <div class="faq-answer"><?= esc($item['answer'] ?? '') ?></div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="text-muted mb-0" data-page-search="لا توجد أسئلة">لا توجد أسئلة متاحة حاليًا.</p>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>
