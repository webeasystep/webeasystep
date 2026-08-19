<!DOCTYPE html>
<html lang="en">
<?= $this->include('site_layout/header'); ?>
<!-- render Head here -->
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDV5ZX7Z"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?= $this->renderSection('content'); ?>

<?= $this->include('site_layout/footer'); ?>

<?= $this->include('site_layout/js'); ?>
</body>

</html>
