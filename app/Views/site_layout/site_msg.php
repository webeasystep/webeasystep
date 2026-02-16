<?php if (session()->getFlashdata('msg_type')) : ?>
    <div class="alert alert-<?php echo session()->getFlashdata('msg_type'); ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php if (session()->getFlashdata('msg_title') != '') : ?>
            <h5><i class="icon fas fa-check"></i> <?php echo session()->getFlashdata('msg_title'); ?></h5>
        <?php endif; ?>
        <?php
        $msg_text = session()->getFlashdata('msg_text');
        if (is_array($msg_text)) {
            foreach ($msg_text as $item) {
                echo $item . "<br>";
            }
        } else {
            echo $msg_text;
        }
        ?>
    </div>
    <?php if (session()->getFlashdata('msg_timeout')) : ?>
        <script>
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, <?= session()->getFlashdata('msg_timeout'); ?>);
        </script>
    <?php endif; ?>
<?php endif; ?>
