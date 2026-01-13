<?php
/** @var \App\Models\User $user */
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
                    <?php if ($user): ?>
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

                <?php if ($user): ?>
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
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($orderedBooks as $item): ?>
                                <?php $book = $item['book']; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($book->getTitle()) ?></strong>
                                        od <?= htmlspecialchars($book->getAuthor()) ?>
                                    </div>
                                    <div>
                                        <?= $item['count'] ?> ks
                                        <span class="text-muted ms-2">(<?= $item['orderDate'] ?>)</span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
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
        </div>
    </div>
</div>
