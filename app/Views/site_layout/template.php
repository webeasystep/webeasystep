<!DOCTYPE html>
<html lang="ar"  dir="rtl">
<?= $this->include('site_layout/header'); ?>
<!-- render Head here -->
<body>
<?= $this->include('site_layout/navbar'); ?>

<?= $this->renderSection('content'); ?>

<?= $this->include('site_layout/footer'); ?>

<?= $this->include('site_layout/js'); ?>
</body>

</html>
