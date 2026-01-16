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

                        <?php if ($book->getSamplePath()): ?>
                            <a href="<?= htmlspecialchars($book->getSamplePath()) ?>" class="btn btn-outline-primary" target="_blank">Ukážka knihy</a>
                        <?php endif; ?>

                        <!-- Tlačidlo pre košík alebo správa o nedostupnosti -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
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
                                <form method="post"
                                      action="<?= $link->url('books.addToFavourite') ?>"
                                      class="m-0">
                                    <input type="hidden" name="book_id" value="<?= $book->getId() ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        Pridať do obľúbených
                                    </button>
                                </form>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($user->isLoggedIn()): ?>
        <hr>
        <h5>Hodnotenie knihy</h5>

        <form method="post" action="<?= $link->url('books.rate') ?>">
            <input type="hidden" name="book_id" value="<?= $book->getId() ?>">

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

            <div class="mt-2">
                <button type="submit" class="btn btn-primary">
                    Uložiť hodnotenie
                </button>
            </div>
        </form>
    <?php else: ?>
        <p class="text-muted">
            Pre hodnotenie sa musíte <a href="<?= $link->url('auth.login') ?>">prihlásiť</a>.
        </p>
    <?php endif; ?>

    <hr>
    <h5>Hodnotenia čitateľov</h5>

    <?php if (empty($reviews)): ?>
        <p class="text-muted">Zatiaľ žiadne hodnotenia.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($reviews as $review): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <strong>
                            Hodnotenie:
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $review->getRating() ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </strong>

                        <small class="text-muted">
                            <?= htmlspecialchars($review->getDate()) ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


</div>
