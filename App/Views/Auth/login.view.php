<?php

/** @var string|null $error */
/** @var LinkGenerator $link */
/** @var View $view */

use Framework\Support\LinkGenerator;
use Framework\Support\View;

?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card my-5">
                <div class="card-header bg-dark text-white">
                    <h5 class="text-center mb-0">Prihlásenie</h5>
                </div>

                <div class="card-body">

                    <div class="text-center text-danger mb-3">
                        <?= @$error ?>
                    </div>
                    <form class="form-signin" id="signForm" method="post" action="<?= $link->url('auth.login') ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">E-mail</label>
                            <input name="username" type="text" id="username" class="form-control" placeholder="E-mail">
                            <small class="error" id="email-error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Heslo</label>
                            <input name="password" type="password" id="password" class="form-control"
                                   placeholder="Heslo">
                            <small class="error" id="password-error"></small>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Prihlásiť sa</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= $link->url('register.index') ?>">Zaregistrovať sa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $link->asset('js/login.js') ?>"></script>
