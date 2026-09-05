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

                <?php
                    $wordCount = mb_strlen(strip_tags($article->content ?? '')) / 5;
                    $readTimeMinutes = max(2, (int)ceil($wordCount / 150));
                ?>
                <!-- Main Article Card -->
                <article class="article-main-card" itemscope itemtype="https://schema.org/Article">
                    <h1 class="article-single-title" itemprop="headline"><?= esc($article->title) ?></h1>

                    <div class="article-single-meta">
                        <span><i class="far fa-calendar-alt ml-1 text-primary"></i> <time itemprop="datePublished" datetime="<?= date('Y-m-d', strtotime($article->created_at)) ?>"><?= $dateFormatted ?></time></span>
                        <span><i class="far fa-user ml-1 text-primary"></i> <span itemprop="author">م. أحمد فخر الدين</span></span>
                        <span><i class="fas fa-graduation-cap ml-1 text-primary"></i> الجامعة السعودية الإلكترونية</span>
                        <span><i class="far fa-clock ml-1 text-primary"></i> قراءة: <?= $readTimeMinutes ?> دقائق</span>
                    </div>

                    <?php if (!empty($imageUrl)): ?>
                        <img src="<?= esc($imageUrl) ?>" alt="<?= esc($article->title) ?>" class="article-featured-img" itemprop="image">
                    <?php endif; ?>

                    <!-- Content -->
                    <div class="article-content-body" itemprop="articleBody">
                        <?= $article->content ?>
                    </div>

                    <!-- Author Bio Card -->
                    <div class="author-bio-card mt-5 p-4 d-flex align-items-center" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; gap: 15px;">
                        <div class="author-avatar" style="width: 65px; height: 65px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg, #136ad5, #0b5cbf); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 26px;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold mb-1" style="color: #0f172a; font-size: 1.1rem;">م. أحمد فخر الدين</h5>
                            <p class="text-muted small mb-2" style="line-height: 1.6;">مؤسس منصة فخر CS وخبير تدريس مناهج كلية الحوسبة والمعلوماتية والسنة الأولى المشتركة في الجامعة السعودية الإلكترونية.</p>
                            <a href="<?= base_url('الجامعة-السعودية-الالكترونية-السنة-الاولى-المشتركة-التحضيرية') ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 20px; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-graduation-cap ml-1"></i> تصفح كورسات وباقات SEU
                            </a>
                        </div>
                    </div>

                    <!-- Social Share -->
                    <div class="article-share-box">
                        <span class="font-weight-bold text-dark"><i class="fas fa-share-alt ml-1 text-primary"></i> مشاركة المقال:</span>
                        <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article->title . ' ' . $currentUrl) ?>" target="_blank" class="share-btn share-wa" rel="noopener" aria-label="مشاركة عبر واتساب">
                                <i class="fab fa-whatsapp ml-1"></i> واتساب
                            </a>
                            <a href="https://t.me/share/url?url=<?= urlencode($currentUrl) ?>&text=<?= urlencode($article->title) ?>" target="_blank" class="share-btn share-tg" rel="noopener" aria-label="مشاركة عبر تيليجرام">
                                <i class="fab fa-telegram-plane ml-1"></i> تيليجرام
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($article->title) ?>&url=<?= urlencode($currentUrl) ?>" target="_blank" class="share-btn share-tw" rel="noopener" aria-label="مشاركة عبر X">
                                <i class="fab fa-x-twitter ml-1"></i> X
                            </a>
                            <button type="button" class="share-btn share-copy" onclick="copyArticleLink()" aria-label="نسخ الرابط">
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
                </article>

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
