<!DOCTYPE html>
<html lang="ar"  dir="rtl">
<?= $this->include('site_layout/header'); ?>
<!-- render Head here -->
<body class="<?= lang("Site.dir") ?>" >

<?= $this->include('site_layout/navbar'); ?>

<main role="main" id="main-content">
<?= $this->renderSection('content'); ?>
</main>

<?= $this->include('site_layout/footer'); ?>

<?= $this->include('site_layout/js'); ?>
<!-- render Javascript here -->
<?= $this->renderSection('js'); ?>
<!-- Javascript -->
</body>

</html>
