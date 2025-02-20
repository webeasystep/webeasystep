<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<!--Banner-->

<div class="untree_co-hero overlay" style="background-image: url('images/img-school-6-min.jpg');">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12">
                <div class="row justify-content-center ">
                    <div class="col-lg-6 text-center ">
                        <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">News</h1>
                        <div class="mb-5 text-white desc mx-auto" data-aos="fade-up" data-aos-delay="200">
                            <p>Another free template by <a href="https://untree.co/" target="_blank" class="link-highlight">Untree.co</a>. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live.</p>
                        </div>

                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300"><a href="#" class="btn btn-secondary">Explore courses</a></p>

                    </div>


                </div>

            </div>

        </div> <!-- /.row -->
    </div> <!-- /.container -->

</div> <!-- /.untree_co-hero -->


<div class="untree_co-section bg-light">
    <div class="container">
        <div class="row align-items-stretch">
            <?php
            foreach ($plans as $plan): ?>
                <div class="col-lg-6 mb-4" data-aos="fade-up">
                    <div class="media-h d-flex h-100">
                        <figure>
                            <img src="<?= thumb($plan->image,170,249) ?>" alt="Image">
                        </figure>
                        <div class="media-h-body">
                            <h2 class="mb-3"><a href="#"><?= $plan->title_ar ?></a></h2>
                            <div class="meta">
                                <span class="icon-calendar mr-2"></span><span><?= date('F d, Y', strtotime($plan->created_at)) ?></span>
                                <span class="icon-person mr-2"></span>msarlink.com
                            </div>
                            <p><?= $plan->desc_ar ?></p>
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
