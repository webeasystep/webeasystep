<?php
    foreach ($recentPages as $page) { ?>
        <div class="item">
            <img src="<?= thumb($page["images"], 259, 145); ?>"
                 class="client" alt="<?= $page['title_ar'] ?>">
        </div>
    <?php }
 ?>
 <!--the condition of check image is removed-->
