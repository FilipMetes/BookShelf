<?php
/** @var \App\Models\User $user */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Upraviť profil</h4>
                </div>

                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= $link->url('profile.save') ?>" id="registerForm">

                        <div class="mb-3">
                            <label for="name" class="form-label">Meno</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   value="<?= htmlspecialchars($user->getName()) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="surname" class="form-label">Priezvisko</label>
                            <input type="text" id="surname" name="surname" class="form-control"
                                   value="<?= htmlspecialchars($user->getSurname()) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="street" class="form-label">Ulica</label>
                            <input type="text" id="street" name="street" class="form-control"
                                   value="<?= htmlspecialchars($user->getStreet()) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">Mesto</label>
                            <input type="text" id="city" name="city" class="form-control"
                                   value="<?= htmlspecialchars($user->getCity()) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="PSC" class="form-label">PSČ</label>
                            <input type="text" id="PSC" name="PSC" class="form-control"
                                   value="<?= htmlspecialchars($user->getPSC()) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="e_mail" class="form-label">E-mail</label>
                            <input type="text" id="e_mail" name="e_mail" class="form-control"
                                   value="<?= htmlspecialchars($user->getEmail()) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nové heslo (nepovinné)</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= $link->url('profile.index') ?>" class="btn btn-secondary">Späť</a>
                            <button type="submit" class="btn btn-success">Uložiť zmeny</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $link->asset('js/profile.js') ?>"></script>
