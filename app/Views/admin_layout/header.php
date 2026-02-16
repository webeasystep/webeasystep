<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?= setting('App.title'); ?> | <?= esc($title) ?? "-------"; ?></title>
    <link rel="icon" type="image/png" href="<?= base_url(); ?>site/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= base_url(); ?>site/favicon-16x16.png" sizes="16x16" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('admin/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Ionicons -->
<!--    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
-->    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <!-- overlayScrollbars -->
    <!--    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">-->
       <!-- Daterange picker -->
    <!-- <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.min.css">-->
   <!-- summernote -->
    <!--  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">-->
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url('admin/plugins/sweetalert2/bootstrap-4.min.css') ?>">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url('admin/plugins/toastr/toastr.min.css') ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('admin/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('admin/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/css/style.css') ?>">

    <?php if(session('lang') == "ar"): ?>
    <!-- Start styles for RTL -->
    <link rel="stylesheet" href="<?= base_url('admin/dist/css-rtl/bootstrap-rtl.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/dist/css-rtl/custom-rtl.min.css') ?>">
    <!-- End styles for RTL -->
    <?php endif; ?>
</head>
