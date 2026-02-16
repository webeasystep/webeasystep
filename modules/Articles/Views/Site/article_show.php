<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<div class="article-section py-5">
    <div class="container">
        <!-- Back Link -->
        <a href="<?= site_url('articles') ?>" class="back-to-articles mb-4 d-inline-flex align-items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            العودة للمقالات
        </a>

        <div class="article-container">
            <?php if (!empty($article->image)): ?>
            <!-- Featured Image -->
            <div class="article-featured-image">
                <img src="<?= thumb($article->image, 900, 400) ?>" 
                     alt="<?= esc($article->title ?? 'صورة المقال') ?>"
                     loading="lazy">
            </div>
            <?php endif; ?>

            <!-- Article Header -->
            <div class="article-header">
                <h1><?= esc($article->title ?? 'بدون عنوان') ?></h1>
                <div class="article-meta">
                    <?php if (!empty($article->created_at)): ?>
                    <span>
                        <i class="far fa-calendar-alt"></i>
                        <?= date('d M Y', strtotime($article->created_at)) ?>
                    </span>
                    <?php endif; ?>
                    <span>
                        <i class="far fa-clock"></i>
                        <?= ceil(mb_strlen(strip_tags($article->content ?? '')) / 1000) ?> دقائق للقراءة
                    </span>
                </div>
            </div>

            <!-- Article Content -->
            <div class="article-content">
                <?= $article->content ?? '' ?>
            </div>

            <!-- Article Footer -->
            <?php if (!empty($article->meta_tags)): ?>
            <div class="article-footer">
                <div class="article-tags">
                    <?php foreach (explode(',', $article->meta_tags) as $tag): ?>
                        <span class="tag"><?= esc(trim($tag)) ?></span>
                    <?php endforeach; ?>
                </div>

                <!-- Share Buttons -->
                <div class="share-buttons">
                    <span>شارك المقال:</span>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($article->title ?? '') ?>" 
                       class="twitter" target="_blank" rel="noopener">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" 
                       class="facebook" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://wa.me/?text=<?= urlencode($article->title . ' - ' . current_url()) ?>" 
                       class="whatsapp" target="_blank" rel="noopener">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode($article->title ?? '') ?>" 
                       class="linkedin" target="_blank" rel="noopener">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>
