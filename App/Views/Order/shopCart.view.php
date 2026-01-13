<?php

use App\Configuration;

/** @var array $cartItems */
/** @var float $totalPrice */

/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book[] $books */
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
                        <?= $item['count'] ?>
                    </td>
                    <td class="text-end">
                        <b><?= number_format($item['subtotal'], 2) ?> €</b>
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

                <a href="#" class="btn btn-success btn-lg mt-2">
                    Objednať
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>
