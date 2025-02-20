<?=$this->extend('admin_layout/template');?>
<?=$this->section('content');?>

<div class="container-fluid">
    <div id="message" data-message="<?=session()->getFlashdata('message')?>"></div>
    <div class="row mb-2">

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-orange">
                <div class="inner">
                    <h3><?=esc($pending_orders)  ?></h3>
                    <p><?= lang("Admin.pending_orders") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-bell"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders#pending_orders' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-green">
                <div class="inner">
                    <h3><?=esc($accepted_orders)?></h3>
                    <p><?= lang("Admin.accepted_orders") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-check-circle"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders#accepted_orders'?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-cyan">
                <div class="inner">
                    <h3><?=esc($taken_orders)?></h3>
                    <p><?= lang("Admin.taken_orders") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-box"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders#taken'?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <!-- finished_orders card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-blue">
                <div class="inner">
                    <h3><?=esc($finished_orders)?></h3>
                    <p><?= lang("Admin.finished_orders") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-thumbs-up"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders#finished'?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-gray">
                <div class="inner">
                    <h3><?=esc($canceled_orders)?></h3>
                    <p><?= lang("Admin.canceled_orders") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-ban"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders#canceled' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-yellow">
                <div class="inner">
                    <h3><?=esc($all_orders)?></h3>
                    <p><?= lang("Admin.all_orders") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-receipt"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-navy">
                <div class="inner">
                    <h3><?=esc($total_orders_today)?></h3>
                    <p>مجموع الطلبات المنتهية اليوم</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-calendar-day"></i>
                </div>
                <a href="<?= ADMIN_URL.'orders' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-purple">
                <div class="inner">
                    <h3><?=esc($attended_drivers)?></h3>
                    <p>مجموع الحاضرين اليوم</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-user-check"></i>
                </div>
                <a href="<?= ADMIN_URL.'AttendRecords' ?>" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- ./col -->
    </div>
    <!-- /.row -->
<!--    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Total Sale</h3>
                        <a href="javascript:void(0);">View Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="report-sale" height="100"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>-->
</div><!-- /.container-fluid -->
<?=$this->endSection();?>

<?=$this->section('admin_layout/js');?>
<script src="<?=base_url('js/dashboard.js')?>"></script>
<?=$this->endSection();?>
