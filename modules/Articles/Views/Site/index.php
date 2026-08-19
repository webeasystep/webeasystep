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

<style>
    .blog-hero {
        background: linear-gradient(135deg, rgba(19, 106, 213, 0.95), rgba(10, 45, 95, 0.92)), url('<?= base_url('site/images/main_banner.webp') ?>') center/cover no-repeat;
        padding: 70px 0 60px;
        color: #ffffff;
        position: relative;
        text-align: center;
    }
    .blog-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
        color: #ffffff;
    }
    .blog-hero p {
        font-size: 1.15rem;
        max-width: 750px;
        margin: 0 auto 25px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.7;
    }
    .blog-search-box {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }
    .blog-search-box .form-control {
        border-radius: 30px;
        padding: 14px 25px 14px 120px;
        height: auto;
        font-size: 1rem;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .blog-search-box button {
        position: absolute;
        left: 5px;
        top: 5px;
        bottom: 5px;
        border-radius: 25px;
        padding: 0 25px;
        font-weight: 600;
    }
    .article-grid-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }
    .article-grid-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(19, 106, 213, 0.12);
    }
    .article-thumb-wrapper {
        position: relative;
        overflow: hidden;
        height: 220px;
        background: #eef2f6;
    }
    .article-thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .article-grid-card:hover .article-thumb-wrapper img {
        transform: scale(1.06);
    }
    .article-category-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(19, 106, 213, 0.9);
        backdrop-filter: blur(4px);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .article-body-content {
        padding: 22px 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .article-meta-info {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .article-title-link {
        color: #1a202c;
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1.5;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .article-title-link:hover {
        color: #136ad5;
        text-decoration: none;
    }
    .article-desc-text {
        color: #4a5568;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    .article-footer-cta {
        border-top: 1px solid #edf2f7;
        padding-top: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .read-more-link {
        color: #136ad5;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: transform 0.2s ease;
    }
    .read-more-link:hover {
        color: #0b50a8;
        transform: translateX(-4px);
        text-decoration: none;
    }
    .custom-pagination .pagination {
        justify-content: center;
        gap: 6px;
    }
    .custom-pagination .page-item .page-link {
        border-radius: 8px;
        color: #136ad5;
        padding: 8px 16px;
        border: 1px solid #e2e8f0;
        font-weight: 600;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #136ad5;
        border-color: #136ad5;
        color: #fff;
    }
</style>

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
