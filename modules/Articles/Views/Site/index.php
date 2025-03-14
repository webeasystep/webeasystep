<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<!--Banner-->
<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row align-items-stretch">
            <?php
            foreach ($articles as $article): ?>
                <div class="col-lg-6 mb-4" data-aos="fade-up">
                    <div class="media-h d-flex h-100">
                        <figure>
                            <img src="<?= thumb($article->image,170,249) ?>" alt="Image">
                        </figure>
                        <div class="media-h-body">
                            <h2 class="mb-3"><a href="#"><?= $article->title_ar ?></a></h2>
                            <div class="meta">
                                <span class="icon-calendar mr-2"></span><span><?= date('F d, Y', strtotime($article->created_at)) ?></span>
                                <span class="icon-person mr-2"></span>msarlink.com
                            </div>
                            <p><?= $article->desc_ar ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <ul class="list-unstyled custom-pagination">
                    <?= $pager->links() ?> <!-- This will create pagination links -->
                </ul>
            </div>
        </div>
    </div>
</div> <!-- /.untree_co-section -->


<?= $this->endSection(); ?>
