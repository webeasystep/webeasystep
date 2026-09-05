<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<?php
if (!function_exists('getArticleImageUrl')) {
    function getArticleImageUrl($imageData, $defaultFallback = null) {
        if (empty($imageData)) {
            return $defaultFallback ?: base_url('site/images/main_banner.webp');
        }
        if (is_string($imageData)) {
            $decoded = json_decode($imageData, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['files'][0]['full_path'])) {
                $path = $decoded['files'][0]['full_path'];
                return base_url($path);
            }
            if (!str_contains($imageData, '{') && !str_contains($imageData, '[')) {
                return base_url($imageData);
            }
        }
        return $defaultFallback ?: base_url('site/images/main_banner.webp');
    }
}
?>

<!-- Hero Section -->
<div class="blog-hero">
    <div class="container">
        <h1 data-aos="fade-up">المدونة الأكاديمية</h1>
        <p data-aos="fade-up" data-aos-delay="100">
            شروحات، مقالات، ودليل شامل لكل ما يخص مقررات وأنظمة الجامعة السعودية الإلكترونية
        </p>

        <!-- Search Form -->
        <div class="blog-search-box" data-aos="fade-up" data-aos-delay="200">
            <form action="<?= site_url('blog') ?>" method="GET">
                <div class="position-relative">
                    <input type="text" name="q" class="form-control" placeholder="ابحث في المقالات والشروحات..." value="<?= esc($searchQuery ?? '') ?>" aria-label="بحث في المدونة">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Articles Listing -->
<div class="untree_co-section bg-light py-5">
    <div class="container">

        <?php if (!empty($searchQuery)): ?>
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="m-0 font-weight-bold text-dark">
                    نتائج البحث عن: <span class="text-primary">"<?= esc($searchQuery) ?>"</span>
                </h5>
                <a href="<?= site_url('blog') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times ml-1"></i> عرض جميع المقالات
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div class="text-center py-5">
                <div class="mb-4 text-muted">
                    <i class="fas fa-book-open" style="font-size: 60px; opacity: 0.4;"></i>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">لم يتم العثور على مقالات</h4>
                <p class="text-muted mb-4">جرّب البحث بكلمات أخرى أو تصفح المقالات المتاحة.</p>
                <a href="<?= site_url('blog') ?>" class="btn btn-primary px-4 py-2" style="border-radius: 20px;">
                    العودة للمدونة
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($articles as $article): ?>
                    <?php
                        $articleUrl = site_url('blog/' . ($article->slug ?: $article->id));
                        $imageUrl = getArticleImageUrl($article->image, base_url('site/images/main_banner.webp'));
                        $dateFormatted = date('d M Y', strtotime($article->created_at));
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                        <div class="article-grid-card">
                            <div class="article-thumb-wrapper">
                                <a href="<?= $articleUrl ?>">
                                    <img src="<?= esc($imageUrl) ?>" alt="<?= esc($article->title) ?>" loading="lazy">
                                </a>
                                <span class="article-category-badge">الجامعة السعودية الإلكترونية</span>
                            </div>
                            <div class="article-body-content">
                                <div class="article-meta-info">
                                    <span><i class="far fa-calendar-alt ml-1 text-primary"></i> <?= $dateFormatted ?></span>
                                    <span><i class="far fa-user ml-1 text-primary"></i> فخر CS</span>
                                </div>
                                <h3 class="h5">
                                    <a href="<?= $articleUrl ?>" class="article-title-link">
                                        <?= esc($article->title) ?>
                                    </a>
                                </h3>
                                <p class="article-desc-text">
                                    <?= esc($article->description ?: strip_tags(mb_substr($article->content ?? '', 0, 160)) . '...') ?>
                                </p>
                                <div class="article-footer-cta">
                                    <a href="<?= $articleUrl ?>" class="read-more-link">
                                        اقرأ المقال <i class="fas fa-arrow-left mr-2"></i>
                                    </a>
                                    <span class="badge badge-light text-muted font-weight-normal px-2 py-1">
                                        <i class="far fa-clock ml-1"></i> 3 دقائق
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                <div class="row mt-5">
                    <div class="col-12 text-center custom-pagination">
                        <?= $pager->links() ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>
