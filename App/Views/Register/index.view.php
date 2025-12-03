<?php

/** @var array|null $errors */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('auth');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto">
            <div class="card my-5">
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

                    <form method="post" action="<?= $link->url('register.register') ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Meno</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="surname" class="form-label">Priezvisko</label>
                                <input type="text" id="surname" name="surname" class="form-control" required>
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
                                <input type="text" id="PSC" name="PSC" class="form-control" maxlength="5">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="e_mail" class="form-label">E-mail</label>
                            <input type="email" id="e_mail" name="e_mail" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Heslo</label>
                            <input type="password" id="password" name="password" class="form-control" required>
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
