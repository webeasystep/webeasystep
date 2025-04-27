<!-- Replace this: -->
<div style="margin-bottom: 32px; border-radius: 8px; overflow: hidden; box-shadow: 0 0 15px rgba(0,0,0,0.05); transition: all 0.3s ease;">
    <div style="position: relative; overflow: hidden; height: 200px;">
        <img style="width: 100%; height: 100%; object-fit: cover;" src="<?= $article_image ?>" alt="<?= $article_title ?>">
    </div>
    <div style="padding: 24px;">
        <h3><?= $article_title ?></h3>
    </div>
</div>

<!-- With this: -->
<div class="article-card">
    <div class="article-image">
        <img src="<?= $article_image ?>" alt="<?= $article_title ?>">
    </div>
    <div class="article-content">
        <h3><?= $article_title ?></h3>
    </div>
</div>