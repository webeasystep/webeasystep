<?php

use Config\Database;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= ADMIN_URL.'dashboard' ?>" class="nav-link"><?= lang("Admin.home") ?></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">اتصل</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-0 ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="بحث" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="flag-icon flag-icon-<?= session('lang') == "ar" ? "sa" : "us"  ?>"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0">
                <a href="<?= base_url('lang/ar') ?>" class="dropdown-item <?= session('lang') == "ar" ? "active" : ""  ?>">
                    <i class="flag-icon flag-icon-sa mr-2"></i> عربي
                </a>

                <a href="<?= base_url('lang/en') ?>" class="dropdown-item <?= session('lang') == "en" ? "active" : ""  ?>">
                    <i class="flag-icon flag-icon-us mr-2"></i> English
                </a>

            </div>
        </li>



        <!-- Messages Dropdown Menu -->

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <?php
                $this->db = Database::connect();
                $msgs = $this->db->query("SELECT id, contact_subject, contact_message, created_at FROM contact_us WHERE is_read = 0 ");
                ?>
                <span class="badge badge-warning navbar-badge"><?= $msgs->getNumRows() ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?= $msgs->getNumRows() ?> إشعار</span>
                <div class="dropdown-divider"></div>
                <?php
                $counter = 0;
                foreach ($msgs->getResultArray() as $msg) {
                    if ($counter >= 10) {
                        break; // Exit the loop after the first ten rows
                    }
                    ?>
                    <a href="<?= ADMIN_URL.'contact_us/edit/'.$msg['id'] ?>" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i><?= $msg['contact_subject'] ?>
                        <span class="float-right text-muted text-sm"><?= $msg['created_at'] ?></span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <?php
                    $counter++;
                }
                ?>
                <a href="<?= base_url(ADMIN_URL.'contact_us') ?>" class="dropdown-item dropdown-footer">مشاهدة كل اﻹشعارات</a>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
        <li class="nav-item">
            <div class="theme-switch-wrapper nav-link">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
