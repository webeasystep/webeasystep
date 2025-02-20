<!DOCTYPE html>
<html lang="en">
<?= $this->include('admin_layout/header'); ?>
<!-- render Head here -->
<?= $this->renderSection('header') ?>
<!-- Head -->

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
    <div class="preloader">
        <div class="loading">
            <div class="spinner-grow text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div id="base-url" data-url="<?= base_url(); ?>"></div>
    <div id="admin-url" data-url="<?= base_url('dt_admin'); ?>"></div>
    <div class="wrapper">
        <!-- Navbar -->
        <?= $this->include('admin_layout/navbar'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('admin_layout/sidebar'); ?>

        <?= $this->include('admin_layout/js'); ?>

        <!-- render Javascript here -->
        <?= $this->renderSection('js'); ?>
        <!-- Javascript -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper " >
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= esc($title); ?></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">الرئيسية</a></li>
                                <li class="breadcrumb-item active"><?= ucfirst(uri_string()) ?></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content transition-fade" id="swup">
                <?= $this->renderSection('content'); ?>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <div class="modal fade" id="modal-logout">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">تأكيد الخروج</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من  تسجيل الخروج؟</p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <a class="btn btn-primary" href="<?= site_url('/dt_admin/logout') ?>">تأكيد</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Main Footer -->
        <?= $this->include('admin_layout/footer'); ?>

    </div>
    <!-- ./wrapper -->
</body>

</html>
