<!-- jQuery -->
<script src="<?= base_url('admin/plugins/jquery/jquery.min.js') ?>"></script>


<!-- daterangepicker -->
 <script src="<?= base_url('admin/plugins/moment/moment.min.js') ?>"></script>
<!--<script src="plugins/daterangepicker/daterangepicker.min.js"></script>-->
<!-- Tempusdominus Bootstrap 4 -->
<!-- <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>-->
<!-- Summernote -->
<!--<script src="plugins/summernote/summernote-bs4.min.js"></script>-->
<!-- overlayScrollbars -->
<!--<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>-->
<script src="<?= base_url() ?>admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url() ?>admin/plugins/select2/js/select2.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= base_url() ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url() ?>admin/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>admin/plugins/toastr/toastr.min.js"></script>

<script src="<?= base_url() ?>admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- bs-custom-file-input -->
<script src="<?= base_url() ?>admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<!-- AdminLTE App -->
<script src="<?= base_url() ?>admin/dist/js/adminlte.min.js"></script>



<?php if (session('lang') == "ar") { ?>
    <!-- Start styles for RTL -->
    <script src="<?= base_url() ?>admin/dist/js-rtl/bootstrap-rtl.bundle.min.js"></script>
    <!-- End styles for RTL -->
<?php } else { ?>
    <!-- Script for LTR -->
    <script src="<?= base_url() ?>admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Script for RTL -->
<?php }; ?>

<!-- Bootstrap 4 -->
<script defer src="<?= base_url() ?>admin/js/swup.js"></script>

<script src="<?= base_url() ?>admin/js/script.js"></script>
