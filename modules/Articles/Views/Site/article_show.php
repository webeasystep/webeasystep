<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<?php
if (!function_exists('getArticleImageUrl')) {
    function getArticleImageUrl($imageData, $defaultFallback = null) {
        if (empty($imageData)) {
            return $defaultFallback;
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
        return $defaultFallback;
    }
}

$imageUrl = getArticleImageUrl($article->image);
$currentUrl = current_url();
$articleTitle = esc($article->title);
$dateFormatted = date('d M Y', strtotime($article->created_at));
?>

<style>
    .article-single-section {
        padding: 50px 0 80px;
        background-color: #f8fafc;
    }
    .article-main-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        padding: 45px 50px;
        border: 1px solid #edf2f7;
    }
    .article-breadcrumb {
        margin-bottom: 25px;
        font-size: 0.95rem;
    }
    .article-breadcrumb a {
        color: #136ad5;
        font-weight: 600;
        text-decoration: none;
    }
    .article-breadcrumb a:hover {
        text-decoration: underline;
    }
    .article-single-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1a202c;
        line-height: 1.4;
        margin-bottom: 20px;
    }
    .article-single-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        padding-bottom: 25px;
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 30px;
        font-size: 0.95rem;
        color: #718096;
    }
    .article-single-meta span {
        display: inline-flex;
        align-items: center;
    }
    .article-featured-img {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 35px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    .article-content-body {
        font-size: 1.12rem;
        line-height: 2;
        color: #2d3748;
    }
    .article-content-body h2,
    .article-content-body h3,
    .article-content-body h4 {
        color: #1a202c;
        font-weight: 700;
        margin-top: 35px;
        margin-bottom: 18px;
    }
    .article-content-body p {
        margin-bottom: 20px;
    }
    .article-content-body ul,
    .article-content-body ol {
        padding-right: 25px;
        margin-bottom: 25px;
    }
    .article-content-body li {
        margin-bottom: 10px;
    }
    .article-content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 20px 0;
    }
    .article-share-box {
        background: #f1f5f9;
        border-radius: 14px;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 50px;
    }
    .share-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff !important;
        text-decoration: none !important;
        transition: opacity 0.2s ease;
    }
    .share-btn:hover {
        opacity: 0.9;
    }
    .share-wa { background-color: #25D366; }
    .share-tg { background-color: #0088cc; }
    .share-tw { background-color: #000000; }
    .share-copy { background-color: #4a5568; cursor: pointer; }

    .related-card {
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid #edf2f7;
        transition: transform 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .related-card:hover {
        transform: translateY(-4px);
    }
    .related-thumb {
        height: 160px;
        overflow: hidden;
    }
    .related-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .related-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .related-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1.5;
        margin-bottom: 8px;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .related-title:hover {
        color: #136ad5;
    }

    @media (max-width: 768px) {
        .article-main-card {
            padding: 25px 20px;
        }
        .article-single-title {
            font-size: 1.6rem;
        }
        .article-content-body {
            font-size: 1rem;
            line-height: 1.85;
        }
    }
</style>

<div class="article-single-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Breadcrumb -->
                <div class="article-breadcrumb">
                    <a href="<?= site_url() ?>">الرئيسية</a>
                    <span class="mx-2 text-muted">/</span>
                    <a href="<?= site_url('blog') ?>">المدونة</a>
                    <span class="mx-2 text-muted">/</span>
                    <span class="text-muted"><?= esc(mb_substr($article->title, 0, 40)) ?>...</span>
                </div>

                <!-- Main Article Card -->
                <div class="article-main-card">
                    <h1 class="article-single-title"><?= esc($article->title) ?></h1>

                    <div class="article-single-meta">
                        <span><i class="far fa-calendar-alt ml-1 text-primary"></i> <?= $dateFormatted ?></span>
                        <span><i class="far fa-user ml-1 text-primary"></i> منصة فخر CS</span>
                        <span><i class="fas fa-graduation-cap ml-1 text-primary"></i> الجامعة السعودية الإلكترونية</span>
                    </div>

                    <?php if (!empty($imageUrl)): ?>
                        <img src="<?= esc($imageUrl) ?>" alt="<?= esc($article->title) ?>" class="article-featured-img">
                    <?php endif; ?>

                    <!-- Content -->
                    <div class="article-content-body">
                        <?= $article->content ?>
                    </div>

                    <!-- Social Share -->
                    <div class="article-share-box">
                        <span class="font-weight-bold text-dark"><i class="fas fa-share-alt ml-1 text-primary"></i> مشاركة المقال:</span>
                        <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article->title . ' ' . $currentUrl) ?>" target="_blank" class="share-btn share-wa" rel="noopener">
                                <i class="fab fa-whatsapp ml-1"></i> واتساب
                            </a>
                            <a href="https://t.me/share/url?url=<?= urlencode($currentUrl) ?>&text=<?= urlencode($article->title) ?>" target="_blank" class="share-btn share-tg" rel="noopener">
                                <i class="fab fa-telegram-plane ml-1"></i> تيليجرام
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($article->title) ?>&url=<?= urlencode($currentUrl) ?>" target="_blank" class="share-btn share-tw" rel="noopener">
                                <i class="fab fa-x-twitter ml-1"></i> X
                            </a>
                            <button type="button" class="share-btn share-copy" onclick="copyArticleLink()">
                                <i class="fas fa-link ml-1"></i> <span id="copyLinkText">نسخ الرابط</span>
                            </button>
                        </div>
                    </div>

                    <!-- Back to Blog Button -->
                    <div class="text-center mt-5 pt-3">
                        <a href="<?= site_url('blog') ?>" class="btn btn-outline-primary px-4 py-2 font-weight-bold" style="border-radius: 25px;">
                            <i class="fas fa-arrow-right ml-2"></i> العودة إلى جميع المقالات
                        </a>
                    </div>
                </div>

                <!-- Related / Recent Articles Section -->
                <?php if (!empty($recentArticles)): ?>
                    <div class="mt-5 pt-3">
                        <h4 class="font-weight-bold text-dark mb-4">
                            <i class="fas fa-bookmark text-primary ml-1"></i> مقالات أخرى قد تهمك
                        </h4>
                        <div class="row">
                            <?php foreach ($recentArticles as $recent): ?>
                                <?php
                                    $recentUrl = site_url('blog/' . ($recent->slug ?: $recent->id));
                                    $recentImg = getArticleImageUrl($recent->image, base_url('site/images/main_banner.webp'));
                                ?>
                                <div class="col-md-4 mb-3">
                                    <div class="related-card">
                                        <div class="related-thumb">
                                            <a href="<?= $recentUrl ?>">
                                                <img src="<?= esc($recentImg) ?>" alt="<?= esc($recent->title) ?>" loading="lazy">
                                            </a>
                                        </div>
                                        <div class="related-body">
                                            <span class="text-muted small mb-2"><i class="far fa-calendar-alt ml-1"></i> <?= date('d M Y', strtotime($recent->created_at)) ?></span>
                                            <a href="<?= $recentUrl ?>" class="related-title">
                                                <?= esc($recent->title) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
function copyArticleLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const textSpan = document.getElementById('copyLinkText');
        textSpan.innerText = 'تم النسخ!';
        setTimeout(() => {
            textSpan.innerText = 'نسخ الرابط';
        }, 2500);
    });
}
</script>

<?= $this->endSection(); ?>
