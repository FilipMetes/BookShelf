<?php
/** @var \App\Models\Book $book */
/** @var \App\Models\Review[] $reviews */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Http\Session $session */
?>

<div class="container my-4">
    <div class="row mb-3">
        <div class="col-12">
            <a href="<?= $link->url('books.index') ?>" class="btn btn-secondary">← Späť na katalóg</a>
        </div>
    </div>

    <div class="book-detail-frame p-4">
        <div class="row g-4">
            <div class="col-md-4">
                <img src="<?= $book->getCoverPath() ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" class="img-fluid rounded shadow-sm">
            </div>

            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h2 class="card-title mb-0"><?= htmlspecialchars($book->getTitle()) ?></h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-1"><strong>Autor:</strong> <?= htmlspecialchars($book->getAuthor()) ?></p>
                        <?php if ($book->getGenre()): ?>
                            <p class="text-muted mb-1"><strong>Žáner:</strong> <?= htmlspecialchars($book->getGenre()) ?></p>
                        <?php endif; ?>
                        <?php if ($book->getFormat()): ?>
                            <p class="text-muted mb-1"><strong>Formát:</strong> <?= htmlspecialchars($book->getFormat()) ?></p>
                        <?php endif; ?>
                        <?php if ($book->getYear()): ?>
                            <p class="text-muted mb-1"><strong>Rok vydania:</strong> <?= $book->getYear() ?></p>
                        <?php endif; ?>
                        <?php if ($book->getPages()): ?>
                            <p class="text-muted mb-1"><strong>Počet strán:</strong> <?= $book->getPages() ?></p>
                        <?php endif; ?>
                        <p class="text-muted mb-1"><strong>Dostupné kusy:</strong> <?= $book->getNumberAvailible() ?></p>
                        <?php if ($book->getPrice() > 0): ?>
                            <p class="text-muted mb-3"><strong>Cena:</strong> €<?= number_format($book->getPrice(), 2) ?></p>
                        <?php endif; ?>

                        <?php if ($book->getText()): ?>
                            <div class="mb-3">
                                <h5>Popis knihy</h5>
                                <p><?= nl2br(htmlspecialchars($book->getText())) ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Tlačidlo pre košík -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mt-4">
                        <div>
                                <?php if ($book->getNumberAvailible() > 0): ?>
                                    <form method="post"
                                          action="<?= $link->url('shopcart.add', ['book_id' => $book->getId()]) ?>"
                                          class="m-0">
                                        <button type="submit" class="btn btn-success">
                                            Pridať do košíka
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-danger fw-bold">Nie je na sklade</span>
                                <?php endif; ?>
                            </div>

                            <!-- PRAVÁ STRANA – obľúbené -->
                            <?php if ($user->isLoggedIn()): ?>
                                <div class="text-end">

                                    <div id="favMsg" class="text-success mb-2" style="display:none;">
                                        ✔ Pridané
                                    </div>

                                    <button
                                            type="button"
                                            id="favBtn"
                                            data-book-id="<?= $book->getId() ?>"
                                            data-url="<?= $link->url('books.addToFavourite') ?>"
                                            class="btn btn-outline-danger">
                                        Pridať do obľúbených
                                    </button>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php if ($book->getSamplePath()): ?>
                    <div class="text-center mt-3">
                        <a href="<?= htmlspecialchars($book->getSamplePath()) ?>"
                           target="_blank"
                           class="btn btn-outline-primary btn-lg">
                            Zobraziť ukážku knihy
                        </a>
                    </div>

                    <?php if ($user->isLoggedIn() && $user->getRole() === 'A'): ?>
                        <form method="post"
                              action="<?= $link->url('books.removeSample', ['id' => $book->getId()]) ?>"
                              class="text-center mt-2"
                              onsubmit="return confirm('Naozaj chcete odstrániť ukážku?');">

                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Odstrániť ukážku
                            </button>
                        </form>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center mt-3 text-muted">
                        Ukážka knihy nie je dostupná
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if ($user->isLoggedIn()): ?>
        <hr>
        <h5>Hodnotenie knihy</h5>

        <form method="post" action="<?= $link->url('books.rate') ?>">
            <input type="hidden" name="book_id" value="<?= $book->getId() ?>">

            <div class="mb-2">
                <div class="btn-group" role="group">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio"
                               class="btn-check"
                               name="rating"
                               id="rate<?= $i ?>"
                               value="<?= $i ?>"
                               required>
                        <label class="btn btn-outline-warning" for="rate<?= $i ?>">
                            <?= $i ?> ★
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="mb-3">

                    <textarea
                         name="review"
                         class="form-control"
                         rows="3"
                         placeholder="Napíšte krátku recenziu..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Pridať hodnotenie
            </button>
        </form>
    <?php else: ?>
        <p class="text-muted">
            Pre hodnotenie sa musíte <a href="<?= $link->url('auth.login') ?>">prihlásiť</a>.
        </p>
    <?php endif; ?>

    <hr>
    <h5>Hodnotenia čitateľov</h5>

    <?php if (empty($reviewsData)): ?>
        <p class="text-muted">Zatiaľ žiadne hodnotenia.</p>
    <?php else: ?>
        <div class="list-group" id="reviewsList">

            <?php foreach ($reviewsData as $item): ?>
                <?php
                $review = $item['review'];
                $user = $item['user'];
                ?>

                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <strong>
                            <?= htmlspecialchars($user?->getName() . ' ' . $user?->getSurname()) ?>
                        </strong>
                        <small class="text-muted">
                            <?= htmlspecialchars($review->getDate()) ?>
                        </small>
                    </div>

                    <div class="mb-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $review->getRating() ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </div>

                    <?php if ($review->getReview()): ?>
                        <p class="mb-0">
                            <?= htmlspecialchars($review->getReview()) ?>
                        </p>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>
<script src="<?= $link->asset('js/addedToFavourite.js') ?>"></script>
