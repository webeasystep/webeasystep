<?= $this->extend('site_layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Example styling for the article detail page */
    .article-section {
        padding: 60px 0;
        background-color: #f9fafb;
    }
    .article-container {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 30px;
    }
    .article-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .article-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .article-header h1::after {
        content: "";
        position: absolute;
        bottom: -6px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background-color: #136ad5;
        border-radius: 2px;
    }
    .article-header .article-desc {
        color: #777;
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Updated styling for the article image (smaller) */
    .article-image {
        display: block;          /* Make it a block-level element */
        margin: 0 auto;          /* Center horizontally */
        max-width: 400px;        /* Restrict maximum width */
        width: 70%;              /* Take ~70% of container on large screens */
        height: auto;
        border-radius: 6px;
        object-fit: cover;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .article-content {
        margin-top: 20px;
        line-height: 1.7;
        color: #444;
        /*
          If your CKEditor content includes headings, paragraphs, etc.,
          you can style them here. For example:
          .article-content h2 { ... }
          .article-content p { ... }
        */
    }

    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .article-container {
            padding: 15px;
        }
        .article-header h1 {
            font-size: 1.6rem;
        }
        .article-image {
            max-width: 100%;
            width: 100%;
        }
    }
</style>

<div class="article-section">
    <div class="container">
        <div class="article-container">

            <!-- Article Header: Title & Description -->
            <div class="article-header">
                <!-- Title -->
                <h1><?= esc($article->title ?? 'بدون عنوان') ?></h1>
                <!-- Short Description (if any) -->
                <?php if (!empty($article->description)): ?>
                    <p class="article-desc"><?= esc($article->description) ?></p>
                <?php endif; ?>
            </div>

            <!-- Article Image (if any) -->
            <?php if (!empty($article->image)): ?>
                <img
                        src="<?= thumb($article->image, 600, 400) ?>"
                        alt="<?= esc($article->title ?? 'صورة المقال') ?>"
                        class="article-image"
                >
            <?php endif; ?>

            <!-- Main Article Content -->
            <div class="article-content">
                <!--
                  If your article content is stored as HTML (from CKEditor),
                  you can echo it without `esc()` so the formatting is preserved:
                -->
                <?= $article->content ?? '' ?>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>
