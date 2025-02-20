<?php
$session = \Config\Services::session();

if ($session->getFlashdata('msg_type') != "") {
    ?>
    <style>
        .alert p {
            margin: 0px;
        }
    </style>
    <div class="col-xs-12">
        <div class="alert alert-<?php echo $session->getFlashdata('msg_type'); ?> alert-dismissible" role="alert" style="margin-bottom: 0px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div style="margin-right: 15px;">
                <?php if ($session->getFlashdata('msg_title') != '') { ?>
                    <strong><?php echo $session->getFlashdata('msg_title'); ?> : </strong>
                <?php } ?>

                <?php
                $msgText = $session->getFlashdata('msg_text');
                if (is_array($msgText)) {
                    foreach ($msgText as $message) {
                        echo "<p>$message</p>";
                    }
                } else {
                    echo $msgText;
                }
                ?>

            </div>
        </div>
    </div>
    <?php
    if ($session->getFlashdata('msg_timeout') != "") {
        ?>
        <script>
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, '<?php echo $session->getFlashdata('msg_timeout'); ?>');
        </script>
        <?php
    }

    // Manually clear the flashdata
    $session->remove('msg_type');
    $session->remove('msg_title');
    $session->remove('msg_text');
    $session->remove('msg_timeout');
}
?>
