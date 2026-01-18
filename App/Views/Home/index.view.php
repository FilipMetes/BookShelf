<?php

use App\Configuration;

/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book[] $books */
/** @var \Framework\Auth\AppUser $user */

use App\Models\Genres;
use  App\Models\Book;

?>
<div class="container home-page my-5">

    <div class="row mb-5">
        <div class="col text-center">
            <h1 class="display-4">Vitajte v BookShelf</h1>
            <p class="lead">
                Objavujte a nakupujte svoje obľúbené knihy na jednom mieste.
            </p>
        </div>
    </div>
    <!-- About section -->
    <div class="row mb-5">
        <div class="col text-center">
            <h2>O BookShelf</h2>
            <p class="mt-3">
                BookShelf je e-shop, ktorý vám umožní prehľadne nakupovať,
                vyhľadávať a objavovať nové tituly. Vytvorená pre čitateľov a nadšencov kníh.
            </p>
        </div>
    </div>


    <div class="row mb-5">
        <div class="col">
            <h2 class="mb-3 text-center">Najnovšie knihy</h2>

            <div class="book-scroller d-flex gap-3 px-2">
                <?php foreach ($books as $book): ?>
                    <div class="card book-card flex-shrink-0">
                        <img
                                src="<?= $book->getCoverPath() ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($book->getTitle()) ?>"
                        >

                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">
                                <?= htmlspecialchars($book->getTitle()) ?>
                            </h6>
                            <small class="text-muted">
                                <?= htmlspecialchars($book->getAuthor()) ?>
                            </small>

                            <div class="mt-2">
                                <strong><?= number_format($book->getPrice(), 2) ?> €</strong>
                            </div>

                            <a href="<?= $link->url('books.detail', ['id' => $book->getId()]) ?>"
                               class="btn btn-sm btn-outline-primary mt-2">
                                Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

</div>
