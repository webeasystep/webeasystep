<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        <span>{elapsed_time} second</span> |
        <span>Dev : <a href="<?= esc("Ahmed fakhr") ?>" target="blank" rel="nofollow"><?= esc("Ahmed Fakhr") ?></a></span> |
        <span>Version : <?= esc(2.1) ?></span>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2021 - <?= date('Y') ?> <a href="<?= site_url() ?>"><?= setting('App.site_name'); ?></a>.</strong> All rights reserved.
</footer>
