<!DOCTYPE html>
<html lang="en">
<?= $this->include('site_layout/header'); ?>
<!-- render Head here -->
<body>

<?= $this->renderSection('content'); ?>

<?= $this->include('site_layout/footer'); ?>

<?= $this->include('site_layout/js'); ?>
</body>

</html>
