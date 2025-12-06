<?php

use App\Configuration;

/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book[] $books */

?>

<div class="container books-catalog my-4">

    <div class="row">
        <!-- Left filter sidebar -->
        <aside class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="card filter-panel">
                <div class="card-body">
                    <h5 class="filter-title">Filter</h5>

                    <div class="filter-section">
                        <h6 class="filter-heading">Žáner</h6>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Horror" id="genre-horror">
                            <label class="form-check-label" for="genre-horror">Horror</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Akcne" id="genre-akcne">
                            <label class="form-check-label" for="genre-akcne">Akčné</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Romanticke" id="genre-romanticke">
                            <label class="form-check-label" for="genre-romanticke">Romantické</label>
                        </div>

                        <!-- Additional genres -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Sci-Fi" id="genre-scifi">
                            <label class="form-check-label" for="genre-scifi">Sci‑Fi</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Fantasy" id="genre-fantasy">
                            <label class="form-check-label" for="genre-fantasy">Fantasy</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Mystery" id="genre-mystery">
                            <label class="form-check-label" for="genre-mystery">Mystery</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Non-fiction" id="genre-nonfiction">
                            <label class="form-check-label" for="genre-nonfiction">Non‑fiction</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Thriller" id="genre-thriller">
                            <label class="form-check-label" for="genre-thriller">Thriller</label>
                        </div>

                    </div>

                    <div class="filter-section mt-3">
                        <h6 class="filter-heading">Autor</h6>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Stephen King" id="author-king">
                            <label class="form-check-label" for="author-king">Stephen King</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Agatha Christie" id="author-christie">
                            <label class="form-check-label" for="author-christie">Agatha Christie</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="J.K. Rowling" id="author-rowling">
                            <label class="form-check-label" for="author-rowling">J.K. Rowling</label>
                        </div>

                        <!-- Additional authors -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="George R.R. Martin" id="author-martin">
                            <label class="form-check-label" for="author-martin">George R.R. Martin</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Isaac Asimov" id="author-asimov">
                            <label class="form-check-label" for="author-asimov">Isaac Asimov</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Dan Brown" id="author-brown">
                            <label class="form-check-label" for="author-brown">Dan Brown</label>
                        </div>

                    </div>

                    <!-- Format section (elektronicky / fyzicky) -->
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

                    <!-- Price slider -->
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

                <div class="search-wrap">
                    <label for="bookSearch" class="visually-hidden">Vyhľadať</label>
                    <input id="bookSearch" class="form-control" type="search" placeholder="Vyhľadať knihu alebo autora...">
                </div>

                <a href="<?= $link->url('books.add') ?>" class="btn btn-success ms-3">Pridať knihu</a>
            </div>

            <div class="row g-3 books-grid">

                <?php if (empty($books)): ?>
                    <p class="text-muted">Zatiaľ neboli pridané žiadne knihy.</p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card book-card h-100">
                                <div class="book-cover"
                                     style="background-image: url('<?= $link->asset(Configuration::UPLOAD_URL . $book->getCoverPath()) ?>')">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h5>
                                    <p class="book-author mb-1"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                    <p class="book-genre text-muted mb-2">
                                        <?= $book->getGenre() ? 'Žáner: ' . htmlspecialchars($book->getGenre()) : '' ?>
                                    </p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <?php if ($book->getPrice()): ?>
                                            <strong class="book-price">€<?= htmlspecialchars($book->getPrice()) ?></strong>
                                        <?php endif; ?>

                                        <div class="d-flex gap-1">
                                            <a class="btn btn-outline-primary btn-sm"
                                               href="<?= $link->url('books.edit', ['id' => $book->getId()]) ?>">
                                                Upraviť
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm"
                                               href="<?= $link->url('books.delete', ['id' => $book->getId()]) ?>">
                                                Zmazať
                                            </a>
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
