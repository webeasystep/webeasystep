<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<!--Banner-->

<div class="untree_co-hero overlay" style="background-image: url('site/images/img-school-6-min.jpg');">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12">
                <div class="row justify-content-center ">
                    <div class="col-lg-6 text-center ">
                        <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">Courses</h1>
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
            <div class="container">
                <h1><?= lang('Courses.Courses') ?></h1>
                <div class="row">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <img src="<?= base_url('uploads/' . $course->image) ?>" class="card-img-top" alt="<?= $course->course_name ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $course->course_name ?></h5>
                                    <p class="card-text"><?= $course->course_desc ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="<?= site_url('courses/view/' . $course->id) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                        </div>
                                        <small class="text-muted"><?= $course->price ?> <?= lang('Courses.is_free') ? 'Free' : '' ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?= $pager->links() ?>
            </div>
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
