<?php

use App\Configuration;

/** @var array $cartItems */
/** @var float $totalPrice */
/** @var \App\Models\Order $order */

/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container mt-4">

    <h1 class="mb-4">🛒 Nákupný košík</h1>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            Košík je momentálne prázdny.
        </div>

        <a href="<?= $link->url('books.index') ?>" class="btn btn-primary">
            Späť na knihy
        </a>

    <?php else: ?>

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
                <?php
                /** @var \App\Models\Book $book */
                $book = $item['book'];
                ?>
                <tr>
                    <td>
                        <b><?= htmlspecialchars($book->getTitle()) ?></b>
                    </td>
                    <td>
                        <?= htmlspecialchars($book->getAuthor()) ?>
                    </td>
                    <td class="text-end">
                        <?= number_format($book->getPrice(), 2) ?> €
                    </td>
                    <td class="text-center">
                        <!-- plus/minus tlačidlá -->
                        <form method="post" action="<?= $link->url('shopcart.updateCartItem') ?>" style="display:inline">
                            <input type="hidden" name="id_order" value="<?= $order->getId() ?>">
                            <input type="hidden" name="id_book" value="<?= $book->getId() ?>">
                            <input type="hidden" name="action" value="minus">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                <?= $item['count'] <= 1 ? 'disabled' : '' ?>> − </button>
                        </form>


                        <span class="mx-2"><?= $item['count'] ?></span>

                        <form method="post" action="<?= $link->url('shopcart.updateCartItem') ?>" style="display:inline">
                            <input type="hidden" name="id_order" value="<?= $order->getId() ?>">
                            <input type="hidden" name="id_book" value="<?= $book->getId() ?>">
                            <input type="hidden" name="action" value="plus">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                <?= $item['count'] >= $book->getNumberAvailible() ? 'disabled' : '' ?>> + </button>
                        </form>

                    </td>
                    <td class="text-end">
                        <b><?= number_format($item['subtotal'], 2) ?> €</b>
                    </td>
                    <td class="text-center">
                        <form method="post" action="<?= $link->url('shopcart.removeFromCart') ?>">
                            <input type="hidden" name="id_order" value="<?= $order->getId() ?>">
                            <input type="hidden" name="id_book" value="<?= $book->getId() ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                Odstrániť
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <a href="<?= $link->url('books.index') ?>" class="btn btn-outline-secondary">
                    ← Pokračovať v nákupe
                </a>
            </div>

            <div class="col-md-6 text-end">
                <h4>
                    Celková cena:
                    <span class="text-success">
                        <?= number_format($totalPrice, 2) ?> €
                    </span>
                </h4>

                <form method="post" action="<?= $link->url('order.checkout') ?>">
                    <input type="hidden" name="id_order" value="<?= $order->getId() ?>">
                    <button type="submit" class="btn btn-success btn-lg mt-2">
                        Objednať
                    </button>
                </form>
            </div>
        </div>

    <?php endif; ?>

</div>
