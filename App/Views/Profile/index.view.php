<?php
/** @var \Framework\Auth\AppUser $user */
/** @var array $orderedBooks */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Profil používateľa</h4>
                </div>
                <div class="card-body">
                    <?php if ($user->isLoggedIn()): ?>
                        <div class="mb-2"><strong>Meno:</strong> <?= htmlspecialchars($user->getName() ?? '-') ?></div>
                        <div class="mb-2"><strong>Priezvisko:</strong> <?= htmlspecialchars($user->getSurname() ?? '-') ?></div>
                        <div class="mb-2"><strong>Ulica:</strong> <?= htmlspecialchars($user->getStreet() ?? '-') ?></div>
                        <div class="mb-2"><strong>Mesto:</strong> <?= htmlspecialchars($user->getCity() ?? '-') ?></div>
                        <div class="mb-2"><strong>PSČ:</strong> <?= htmlspecialchars($user->getPSC() ?? '-') ?></div>
                        <div class="mb-2"><strong>E-mail:</strong> <?= htmlspecialchars($user->getEmail() ?? '-') ?></div>
                        <div class="mb-2"><strong>Rola:</strong> <?= $user->isAdmin() ? 'Admin' : 'Bežný používateľ' ?></div>
                    <?php else: ?>
                        <p class="text-muted">Nie ste prihlásený.</p>
                    <?php endif; ?>
                </div>

                <?php if ($user->isLoggedIn()): ?>
                    <div class="text-center mb-4">
                        <a href="<?= $link->url('profile.edit') ?>" class="btn btn-dark">Upraviť profil</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($orderedBooks)): ?>
                <div class="card shadow-sm mt-4">

                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Vaše objednané knihy</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Názov</th>
                                    <th>Autor</th>
                                    <th style="width: 120px;">Počet</th>
                                    <th style="width: 160px;">Dátum</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($orderedBooks as $item): ?>
                                    <?php $book = $item['book']; ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($book->getTitle()) ?></strong>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($book->getAuthor()) ?>
                                        </td>

                                        <td>
                                            <?= (int)$item['count'] ?> ks
                                        </td>

                                        <td class="text-muted">
                                            <?= htmlspecialchars($item['orderDate']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            <?php else: ?>
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Vaše objednané knihy</h5>
                    </div>
                    <div class="card-body text-center text-muted">
                        Žiadne prebiehajúce objednávky
                    </div>
                </div>
            <?php endif; ?>


            <?php if (!empty($favouriteBooks)): ?>
                <div class="card shadow-sm mt-4">

                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Vaše obľúbené knihy</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table
                                    class="table table-hover table-striped mb-0 align-middle"
                                    id="favouriteTable"
                                    data-remove-url="<?= $link->url('books.removeFavourite') ?>"
                            >
                            <thead class="table-light">
                                <tr>
                                    <th>Názov</th>
                                    <th>Autor</th>
                                    <th style="width: 160px;">Pridané</th>
                                    <th class="text-center" style="width: 120px;">Akcia</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($favouriteBooks as $item): ?>
                                    <?php $book = $item['book']; ?>
                                    <tr id="fav-row-<?= $book->getId() ?>">
                                        <td>
                                            <strong><?= htmlspecialchars($book->getTitle()) ?></strong>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($book->getAuthor()) ?>
                                        </td>

                                        <td class="text-muted text-nowrap">
                                            <?= htmlspecialchars($item['date']) ?>
                                        </td>

                                        <td class="text-end text-nowrap">
                                            <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger remove-fav"
                                                    data-book-id="<?= $book->getId() ?>">
                                                Odstrániť
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            <?php else: ?>
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Vaše obľúbené knihy</h5>
                    </div>
                    <div class="card-body text-center text-muted">
                        Zatiaľ nemáte žiadne obľúbené knihy
                    </div>
                </div>
            <?php endif; ?>


        </div>

    </div>
</div>
<script src="<?= $link->asset('js/removeFavourite.js') ?>"></script>
