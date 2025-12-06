<?php

use App\Configuration;

/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book[] $books */

$user = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

?>

<div class="container books-catalog my-4">

    <div class="row">
        <!-- Left filter sidebar -->
        <aside class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="card filter-panel">
                <div class="card-body">
                    <h5 class="filter-title">Filter</h5>

                    <!-- Žáner filter -->
                    <div class="filter-section">
                        <h6 class="filter-heading">Žáner</h6>
                        <?php
                        $genres = ['Horror', 'Akčné', 'Romantické', 'Sci‑Fi', 'Fantasy', 'Mystery', 'Non‑fiction', 'Thriller'];
                        foreach ($genres as $genre):
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $genre ?>" id="genre-<?= strtolower($genre) ?>">
                                <label class="form-check-label" for="genre-<?= strtolower($genre) ?>"><?= $genre ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Autor filter -->
                    <div class="filter-section mt-3">
                        <h6 class="filter-heading">Autor</h6>
                        <?php
                        $authors = ['Stephen King', 'Agatha Christie', 'J.K. Rowling', 'George R.R. Martin', 'Isaac Asimov', 'Dan Brown'];
                        foreach ($authors as $author):
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $author ?>" id="author-<?= strtolower(str_replace(' ', '-', $author)) ?>">
                                <label class="form-check-label" for="author-<?= strtolower(str_replace(' ', '-', $author)) ?>"><?= $author ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Formát filter -->
                    <div class="filter-section mt-3 format-section">
                        <h6 class="filter-heading">Formát</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Elektronicky" id="format-e">
                            <label class="form-check-label" for="format-e">Elektronický</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Fyzicky" id="format-p">
                            <label class="form-check-label" for="format-p">Fyzický</label>
                        </div>
                    </div>

                    <!-- Cena filter -->
                    <div class="filter-section mt-3 price-section">
                        <h6 class="filter-heading">Cena (€)</h6>
                        <div class="price-slider">
                            <label for="priceRange" class="visually-hidden">Maximálna cena</label>
                            <input type="range" class="form-range" min="0" max="200" step="1" id="priceRange" value="50">
                            <div class="d-flex justify-content-between mt-1 small text-muted">
                                <span>0€</span>
                                <span>200€</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary btn-sm">Použiť filtre</button>
                        <button type="button" class="btn btn-link btn-sm text-muted">Vymazať</button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main content: search, actions and books grid -->
        <main class="col-12 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="catalog-title">Katalóg kníh</h2>

                <div class="search-wrap d-flex align-items-center gap-2">
                    <label for="bookSearch" class="visually-hidden">Vyhľadať</label>
                    <input id="bookSearch" class="form-control" type="search" placeholder="Vyhľadať knihu podľa názvu">
                    <button type="button" id="searchButton" class="btn btn-primary btn-sm">Vyhľadať</button>
                </div>

                <?php if ($user?->isAdmin()): ?>
                    <a href="<?= $link->url('books.add') ?>" class="btn btn-success ms-3">Pridať knihu</a>
                <?php endif; ?>
            </div>

            <div class="row g-3 books-grid">

                <?php if (empty($books)): ?>
                    <p class="text-muted">Zatiaľ neboli pridané žiadne knihy.</p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card book-card h-100">
                                <img src="<?= $book->getCoverPath() ?>"
                                     alt="<?= htmlspecialchars($book->getTitle()) ?>"
                                     class="book-cover-img">

                                <div class="card-body d-flex flex-column">
                                    <h5 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h5>
                                    <p class="book-author mb-1"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                    <p class="book-genre text-muted mb-2">
                                        <?= $book->getGenre() ? 'Žáner: ' . htmlspecialchars($book->getGenre()) : '' ?>
                                    </p>

                                    <div class="mt-auto">
                                        <?php if ($book->getPrice()): ?>
                                            <strong class="book-price d-block mb-2">€<?= htmlspecialchars($book->getPrice()) ?></strong>
                                        <?php endif; ?>

                                        <div class="d-flex flex-wrap gap-1">
                                            <a class="btn btn-outline-secondary btn-sm"
                                               href="<?= $link->url('books.detail', ['id' => $book->getId()]) ?>">
                                                Detail
                                            </a>

                                            <?php if ($user?->isAdmin()): ?>
                                                <a class="btn btn-outline-primary btn-sm"
                                                   href="<?= $link->url('books.edit', ['id' => $book->getId()]) ?>">
                                                    Upraviť
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm"
                                                   href="<?= $link->url('books.delete', ['id' => $book->getId()]) ?>">
                                                    Zmazať
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </main>
    </div>

</div>

<script src="<?= $link->asset('js/searchBook.js') ?>"></script>
