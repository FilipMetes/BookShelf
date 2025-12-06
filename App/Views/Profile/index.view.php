<?php
use App\Configuration;

/** @var \Framework\Support\LinkGenerator $link */

$user = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);
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
        </div>
    </div>
</div>
