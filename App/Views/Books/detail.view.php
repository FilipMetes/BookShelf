<?php
/** @var \App\Models\Book $book */
/** @var \Framework\Support\LinkGenerator $link */
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
                        <div class="mt-3">
                            <?php if ($book->getNumberAvailible() > 0): ?>
                                <a href="<?= $link->url('order.add', ['book_id' => $book->getId()]) ?>" class="btn btn-success mt-3">
                                    Pridať do košíka
                                </a>
                            <?php else: ?>
                                <span class="text-danger fw-bold">Nie je na sklade</span>
                            <?php endif; ?>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
