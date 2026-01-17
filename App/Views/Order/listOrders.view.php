<?php
/** @var Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
/** @var array $errors */
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    Objednávka #<?= $order->getId() ?> |
                    <?= htmlspecialchars($order->getDate()) ?>
                </div>

                <?php if ($order->getState() === 'P'): ?>
                    <form method="post"
                          action="<?= $link->url('order.deleteOrder') ?>"
                          onsubmit="return confirm('Naozaj chcete zrušiť objednávku?');"
                          class="m-0">
                        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Zrušiť
                        </button>
                    </form>
                <?php endif; ?>
            </div>


            <div class="card-body p-0">
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
    <?php endforeach; ?>

    <a href="<?= $link->url('admin.index') ?>" class="btn btn-secondary">
        Späť
    </a>
</div>

