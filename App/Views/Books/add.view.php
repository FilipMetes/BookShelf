<?php
/** @var Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book $book */
/** @var array $formErrors */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-6 d-flex gap-4 flex-column">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4>Pridať knihu</h4>
                </div>
                <div class="card-body">
                    <?php require 'form.view.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

