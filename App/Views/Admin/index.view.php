<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
/** @var \App\Models\User[] $users */
/** @var string|null $success */
?>

<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Správa používateľov</h5>
        </div>

        <form method="post" action="<?= $link->url('admin.setRoles') ?>">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Meno</th>
                        <th>Priezvisko</th>
                        <th>Email</th>
                        <th class="text-center">Admin</th>
                        <th class="text-center">Akcia</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u->getId() ?></td>
                            <td><strong><?= htmlspecialchars($u->getName()) ?></strong></td>
                            <td><strong><?= htmlspecialchars($u->getSurname()) ?></strong></td>
                            <td><?= htmlspecialchars($u->getEmail()) ?></td>
                            <td class="text-center">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="admins[]"
                                       value="<?= $u->getId() ?>"
                                        <?= $u->getRole() === 'A' ? 'checked' : '' ?>>
                            </td>
                            <td class="text-center">
                                <a href="<?= $link->url('order.listOrders', ['id' => $u->getId()]) ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    Objednávky
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top d-flex flex-column align-items-center gap-2">
                <button type="submit" class="btn btn-success">
                    Uložiť zmeny
                </button>

                <?php if (!empty($success)): ?>
                    <div class="text-success fw-semibold">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <a href="<?= $link->url('books.index') ?>"
                   class="btn btn-primary">
                    Spravovať knihy
                </a>
            </div>
        </form>
    </div>
</div>
