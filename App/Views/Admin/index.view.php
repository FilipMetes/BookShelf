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

        <form id="rolesForm" data-url="<?= $link->url('admin.setRoles') ?>">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Meno</th>
                            <th>Priezvisko</th>
                            <th>Email</th>
                            <th>Rola</th>
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
                                <td class="role-text"><?= $u->getRole() === 'A' ? 'Admin' : 'Uživatel' ?></td>
                                <td class="text-center">
                                    <label>
                                        <input type="checkbox"
                                               class="form-check-input role-toggle"
                                               data-user-id="<?= $u->getId() ?>"
                                                <?= $u->getRole() === 'A' ? 'checked' : '' ?>>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <a href="<?= $link->url('order.listOrders', ['id' => $u->getId()]) ?>"
                                       class="btn btn-sm btn-dark">
                                        Objednávky
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-3 border-top d-flex flex-column align-items-center gap-2">
                <button type="submit" class="btn btn-success">Uložiť zmeny</button>
                <div id="rolesMsg" class="text-success fw-semibold"></div>
            </div>
        </form>
    </div>
</div>

<script src="<?= $link->asset('js/updateRoles.js') ?>"></script>