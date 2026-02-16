<?php
/**
 * DataTable Template for Admin Layout
 * 
 * This template provides a standard DataTable structure for admin modules.
 */
?>

<?= $this->extend('admin_layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?? '' ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dt_admin') ?>"><?= lang('Admin.dashboard') ?></a></li>
                        <li class="breadcrumb-item active"><?= $breadcrumb ?? '' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= $card_title ?? $title ?? '' ?></h3>
                            <div class="card-tools">
                                <?php if (isset($create_url)): ?>
                                <a href="<?= $create_url ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> <?= lang('Admin.create') ?>
                                </a>
                                <?php endif; ?>
                                <?php if (isset($additional_buttons)): ?>
                                    <?= $additional_buttons ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="jq-table" class="table table-bordered table-striped">
                                <thead>
                                    <!-- Headers will be generated dynamically by DtTable -->
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?= $this->include('admin_layout/index_js') ?>
<?= $this->endSection() ?>