<?php
/** @var Framework\Support\LinkGenerator $link */
/** @var AppUser $user */
/** @var array $errors */
/** @var array $ordersWithItems */

use Framework\Auth\AppUser;

?>

<div class="container mt-4">
    <h4>
        Objednávky používateľa
        <strong>
            <?= htmlspecialchars(
                $user->getName() . ' ' . $user->getSurname()
            ) ?>
        </strong>
    </h4>


    <?php if (empty($ordersWithItems)): ?>
        <p class="text-muted">Používateľ nemá žiadne objednávky.</p>
    <?php endif; ?>

    <?php foreach ($ordersWithItems as $data): ?>
        <?php $order = $data['order']; ?>

        <div class="card mb-3">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">

            <div>
                    Objednávka #<?= $order->getId() ?> |
                    <?= htmlspecialchars($order->getDate()) ?> |
                    <strong>
                        <?= $order->getState() === 'P' ? 'Čaká sa' : 'Vybavená' ?>
                    </strong>
                </div>

                <?php if ($order->getState() === 'P'): ?>
                    <form method="post"
                          action="<?= $link->url('order.markDelivered') ?>"
                          class="d-flex align-items-center gap-2 m-0">
                        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="delivered"
                                   value="1"
                                   id="delivered<?= $order->getId() ?>"
                                   onchange="this.form.submit()">
                            <label class="form-check-label"
                                   for="delivered<?= $order->getId() ?>">
                                Označiť ako vybavené
                            </label>
                        </div>
                    </form>

                    <form method="post"
                          action="<?= $link->url('order.deleteOrder') ?>"
                          onsubmit="return confirm('Naozaj chcete zrušiť túto objednávku?');"
                          class="m-0">
                        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Zrušiť
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Kniha</th>
                            <th>Počet</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['books'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['book']->getTitle()) ?></td>
                                <td><?= (int)$item['count'] ?> ks</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <a href="<?= $link->url('admin.index') ?>" class="btn btn-secondary">
        Späť
    </a>
</div>

