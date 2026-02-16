<?php if (! empty($errors)): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button  type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
