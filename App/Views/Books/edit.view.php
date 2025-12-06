<?php

/** @var Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book $book */
/** @var array $formErrors */

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-6 d-flex gap-4 flex-column">
            <h1>Úprava knihy</h1>

            <?php require 'form.view.php'; ?>

        </div>
    </div>
</div>