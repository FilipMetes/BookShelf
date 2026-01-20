<?php

/** @var array|null $errors */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

?>

<div class="container-fluid px-2 px-md-3 py-3 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

        <div class="card my-4 shadow-sm">
            <div class="card-body">
                    <h5 class="card-title text-center">Registrácia</h5>

                    <?php if (!empty($errors)) { ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $e) { ?>
                                    <li><?= htmlspecialchars($e) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <form method="post" id="registerForm" action="<?= $link->url('register.register') ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Meno</label>
                                <input type="text" id="name" name="name" class="form-control">
                                <small class="error" id="name-error"></small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="surname" class="form-label">Priezvisko</label>
                                <input type="text" id="surname" name="surname" class="form-control">
                                <small class="error" id="surname-error"></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="street" class="form-label">Ulica</label>
                            <input type="text" id="street" name="street" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Mesto</label>
                                <input type="text" id="city" name="city" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="PSC" class="form-label">PSC</label>
                                <input type="number" id="PSC" name="PSC" class="form-control">
                                <small class="error" id="PSC-error"></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="e_mail" class="form-label">E-mail</label>
                            <input type="text" id="e_mail" name="e_mail" class="form-control">
                            <small class="error" id="e_mail-error"></small>

                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Heslo</label>
                            <input type="password" id="password" name="password" class="form-control">
                            <small class="error" id="password-error"></small>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-primary">Registrovať</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $link->asset('js/register.js') ?>"></script>
<script src="<?= $link->asset('js/personalInfoValidate.js') ?>"></script>
