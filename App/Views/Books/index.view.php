<?php

use App\Configuration;

/** @var LinkGenerator $link */
/** @var Book[] $books */
/** @var AppUser $user */
/** @var int $page */

use App\Models\Genres;
use  App\Models\Book;
use Framework\Auth\AppUser;
use Framework\Support\LinkGenerator;

?>

<div class="container books-catalog mt-4 mb-5">

    <div class="row">
        <!-- Left filter sidebar -->
        <aside class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="card filter-panel">
                <div class="card-body">
                    <h5 class="filter-title mb-3">Filter</h5>

                    <!-- Žáner filter -->
                    <div class="filter-section">
                        <h6 class="filter-heading d-flex justify-content-between"
                            data-bs-toggle="collapse"
                            data-bs-target="#filter-genre"
                            role="button"
                            aria-expanded="false">
                            Žáner
                            <span class="small">⌄</span>
                        </h6>

                        <div id="filter-genre" class="collapse">
                            <?php foreach(Genres::all() as $genre): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           value="<?= $genre ?>"
                                           id="genre-<?= $genre ?>">
                                    <label class="form-check-label ms-1" for="genre-<?= $genre ?>">
                                        <?= $genre ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>


                    <!-- Autor filter -->
                    <div class="filter-section mt-3">
                        <h6 class="filter-heading d-flex justify-content-between"
                            data-bs-toggle="collapse"
                            data-bs-target="#filter-author"
                            role="button"
                            aria-expanded="false">
                            Autor
                            <span class="small">⌄</span>
                        </h6>

                        <div id="filter-author" class="collapse">
                            <?php foreach(Book::getDistinctAuthors() as $author): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox"
                                           value="<?= htmlspecialchars($author) ?>"
                                           id="author-<?= strtolower(str_replace(' ', '-', $author)) ?>">
                                    <label class="form-check-label ms-1"
                                           for="author-<?= strtolower(str_replace(' ', '-', $author)) ?>">
                                        <?= htmlspecialchars($author) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <!-- Formát filter -->
                    <div class="filter-section mt-3">
                        <h6 class="filter-heading d-flex justify-content-between"
                            data-bs-toggle="collapse"
                            data-bs-target="#filter-format"
                            role="button">
                            Formát
                            <span class="small">⌄</span>
                        </h6>

                        <div id="filter-format" class="collapse">
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" value="E" id="format-e">
                                <label class="form-check-label ms-1" for="format-e">Elektronický</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="F" id="format-p">
                                <label class="form-check-label ms-1" for="format-p">Fyzický</label>
                            </div>
                        </div>
                    </div>


                    <!-- Cena filter -->
                    <div class="filter-section mt-3 price-section">
                        <h6 class="filter-heading">Cena (€)</h6>
                        <div class="d-flex flex-column">
                            <input type="range" class="form-range" min="0" max="200" step="1" id="priceRange" value="50">
                            <label for="priceRange"></label>
                            <div class="d-flex justify-content-between mt-1">
                                <span id="priceCurrent">50€</span> <!-- Aktuálna hodnota slidera -->
                                <span>200€</span> <!-- Max -->
                            </div>
                        </div>
                    </div>


                    <div class="mt-3">
                        <button type="button" id="applyFilters" class="btn btn-primary btn-sm btn-dark">
                            Použiť filtre
                        </button>
                        <button type="button" id="resetFilters" class="btn btn-link btn-sm text-muted">
                            Vymazať
                        </button>
                    </div>

                </div>
            </div>
        </aside>

        <!-- Main content: search, actions and books grid -->
        <main class="col-12 col-md-9">
            <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 gap-md-3 mb-4">

            <h2 class="catalog-title mb-0 text-center">Katalóg kníh</h2>

                <div class="search-wrap d-flex align-items-center gap-2">
                    <label for="bookSearch" class="visually-hidden">Vyhľadať</label>
                    <input id="bookSearch" class="form-control" type="search" placeholder="Vyhľadať knihu podľa názvu">
                    <button type="button" id="searchButton" class="btn btn-primary btn-sm btn-dark">
                        Vyhľadať
                    </button>
                </div>

                <?php if (($user->isLoggedIn() && $user->isAdmin() ?? false)): ?>
                    <a href="<?= $link->url('books.add') ?>" class="btn btn-success ms-3">Pridať knihu</a>
                <?php endif; ?>
            </div>

            <div class="books-grid">

                <?php if (empty($books)): ?>
                    <p class="text-muted">Zatiaľ neboli pridané žiadne knihy.</p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <div class="card book-card w-100"
                             data-format="<?= htmlspecialchars($book->getFormat()) ?>"
                             data-genre="<?= htmlspecialchars($book->getGenre()) ?>"
                             data-author="<?= htmlspecialchars($book->getAuthor()) ?>"
                             data-price="<?= htmlspecialchars($book->getPrice()) ?>">

                            <img src="<?= $book->getCoverPath() ?>"
                                 alt="<?= htmlspecialchars($book->getTitle()) ?>"
                                 class="book-cover-img">

                            <div class="card-body">
                                <h5 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h5>
                                <p class="book-author mb-1"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                <p class="book-genre mb-1"><?= htmlspecialchars($book->getGenre()) ?></p>
                                <p class="book-format mb-1">
                                    <?= htmlspecialchars(
                                            $book->getFormat() === 'E' ? 'Elektronický' :
                                                    ($book->getFormat() === 'F' ? 'Fyzický' : $book->getFormat())
                                    ) ?>
                                </p>

                                <div>
                                    <strong class="book-price d-block mb-2">
                                        <?= htmlspecialchars($book->getPrice()) ?>€
                                    </strong>

                                    <div class="d-flex justify-content-center gap-1">
                                        <a class="btn btn-outline-secondary btn-sm"
                                           href="<?= $link->url('books.detail', ['id' => $book->getId()]) ?>">
                                            Detail
                                        </a>

                                        <?php if ($user->isLoggedIn() && $user->isAdmin()): ?>
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
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>


        </main>

        <div class="row">
            <div class="col-12 col-md-3"></div>

            <div class="col-12 col-md-9">
                <?php if (!empty($totalPages) && $totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">

                            <!-- späť -->
                            <li class="page-item <?= (!isset($page) || $page <= 1) ? 'disabled' : '' ?>">

                                <a class="page-link"
                                   href="<?= $link->url('books.index', ['page' => $page - 1]) ?>">
                                    &laquo;
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                       href="<?= $link->url('books.index', ['page' => $i]) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- ďalej -->
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                   href="<?= $link->url('books.index', ['page' => $page + 1]) ?>">
                                    &raquo;
                                </a>
                            </li>

                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

</div>
<script src="<?= $link->asset('js/filterBooks.js') ?>"></script>