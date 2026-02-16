<?php $this->extend('site_layout/template'); ?>
<?php $this->section('content'); ?>
<!--Banner-->
<div class="article-banner" style="background-color: #700036 ">
    <div class="container">
        <a href="<?= site_url() ?>"> <img src="<?= base_url(); ?>site/imgs/emptyLogo.png"/></a>
        <h1>تواصل معنا</h1>
    </div>
</div>
<!--Banner-->
<!--form-->

<div class="container">

    <section class="hotel-items">
        <div class="row">
            <?php if (!empty($pages)) {
                foreach ($pages as $page) {
                    ?>
                    <div class="col-sm-12 col-md-6">
                        <div class="hotel-item">
                            <div class="hotel-item-image">
                                <img alt="thumb" src="<?= thumb($page->images, 361, 114) ?>"/>
                            </div>
                            <div class="hotel-item-contain">
                                <h2><?= localized_field('title', $page); ?></h2>
                                <p><?= localized_field('desc', $page); ?></p>
                            </div>
                            <a href="<?= base_url('pages/'.$page->page_link)?>">زيارة الموقع &nbsp;<i class="fa-solid fa-left-long"></i></a>
                        </div>
                    </div>
                <?php }
            } ?>

        </div>

        <?= $pager->links() ?>
    </section>

    <section class="hotels-list">
        <h3>فنادقنا</h3>
        <div class="hotels-list-filter">
            <div>
                <label>التصميف</label>
                <select>
                    <option>-- اختر --</option>
                </select>
            </div>

            <div>
                <label>بحث</label>
                <input type="text"/>
            </div>
        </div>

        <div class="hotels-list-containt">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 1.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 2.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 3.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 4.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 8.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 7.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 6.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-4">
                    <a href="#">
                        <div class="cart-review-img">
                            <img src="../imgs/Hotel 5.png"/>
                            <span>فنادف الرياض</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination li {
        margin-left: 5px;
        margin-right: 5px;
    }

    .pagination li.active {
        background-color: #ccc;
    }

    .pagination li a {
        display: block;
        padding: 8px 16px;
        text-decoration: none;
        color: #000;
    }

    .pagination li a:hover {
        background-color: #eee;
    }

</style>

<?= $this->endSection(); ?>
