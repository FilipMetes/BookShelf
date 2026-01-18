<?php
/** @var array $cartItems */
/** @var float $totalPrice */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container mt-4">

    <h1 class="mb-4">🛒 Nákupný košík </h1>

    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $e) { ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            Košík je momentálne prázdny.
        </div>

        <a href="<?= $link->url('books.index') ?>" class="btn btn-secondary">
            Späť na knihy
        </a>

    <?php else: ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Kniha</th>
                    <th>Autor</th>
                    <th class="text-end">Cena</th>
                    <th class="text-center">Množstvo</th>
                    <th class="text-end">Spolu</th>
                    <th class="text-center">Akcia</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <?php $book = $item['book']; ?>
                    <tr>
                        <td><b><?= htmlspecialchars($book->getTitle()) ?></b></td>
                        <td><?= htmlspecialchars($book->getAuthor()) ?></td>
                        <td class="text-end text-nowrap"><?= number_format($book->getPrice(), 2) ?> €</td>

                        <td class="text-center text-nowrap">
                            <!-- mínus -->
                            <form method="post"
                                  action="<?= $link->url('shopcart.update') ?>"
                                  style="display:inline">
                                <input type="hidden" name="book_id" value="<?= $book->getId() ?>">
                                <input type="hidden" name="action" value="minus">
                                <button class="btn btn-sm btn-outline-secondary"
                                        <?= $item['count'] <= 1 ? 'disabled' : '' ?>>
                                    −
                                </button>
                            </form>

                            <span class="mx-2"><?= $item['count'] ?></span>

                            <!-- plus -->
                            <form method="post"
                                  action="<?= $link->url('shopcart.update') ?>"
                                  style="display:inline">
                                <input type="hidden" name="book_id" value="<?= $book->getId() ?>">
                                <input type="hidden" name="action" value="plus">
                                <button class="btn btn-sm btn-outline-secondary"
                                        <?= $item['count'] >= $book->getNumberAvailible() ? 'disabled' : '' ?>>
                                    +
                                </button>
                            </form>
                        </td>

                        <td class="text-end text-nowrap">
                            <b><?= number_format($item['subtotal'], 2) ?> €</b>
                        </td>

                        <td class="text-center text-nowrap">
                            <form method="post" action="<?= $link->url('shopcart.remove') ?>">
                                <input type="hidden" name="book_id" value="<?= $book->getId() ?>">
                                <button class="btn btn-danger btn-sm">
                                    Odstrániť
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>

        <div class="row mt-4 align-items-center border-top pt-3">

            <!-- VĽAVO – pokračovať -->
            <div class="col-12 col-md-6 mb-3 mb-md-0 text-center text-md-start">
                <a href="<?= $link->url('books.index') ?>"
                   class="btn btn-outline-secondary btn-sm btn-mobile-full">
                    ← Pokračovať v nákupe
                </a>

            </div>

            <!-- VPRAVO – suma + objednať -->
            <div class="col-12 col-md-6 text-center text-md-end">
                <div class="d-flex flex-column flex-md-row
                justify-content-md-end
                align-items-center
                gap-2">
                    <div class="fw-semibold mb-0">
                        Celková cena:
                        <span class="text-success fs-5">
                <?= number_format($totalPrice, 2) ?> €
            </span>
                    </div>
                    <a href="<?= $link->url('order.index') ?>"
                       class="btn btn-success btn-sm btn-mobile-full">
                        Objednať
                    </a>
                </div>
            </div>

        </div>



    <?php endif; ?>

</div>
