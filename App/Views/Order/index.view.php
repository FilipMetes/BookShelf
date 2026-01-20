<?php
/** @var Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
/** @var array $errors */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6 d-flex gap-4 flex-column">

        <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4>Objednávka</h4>
                </div>

                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars(($err ?? '')) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $link->url('order.checkout') ?>" method="post" id="orderForm">

                        <?php if ($user->isLoggedIn()): ?>
                            <!-- Meno -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Meno</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control"
                                       value="<?= htmlspecialchars(($user->getName() ?? '')) ?>">
                                <small class="error" id="name-error"></small>
                            </div>

                            <!-- Priezvisko -->
                            <div class="mb-3">
                                <label for="surname" class="form-label">Priezvisko</label>
                                <input type="text"
                                       name="surname"
                                       id="surname"
                                       class="form-control"
                                       value="<?= htmlspecialchars(($user->getSurname() ?? '')) ?>">
                                <small class="error" id="surname-error"></small>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="e_mail" class="form-label">Email</label>
                                <input type="text"
                                       name="e_mail"
                                       id="e_mail"
                                       class="form-control"
                                       value="<?= htmlspecialchars(($user->getEmail() ?? '')) ?>">
                                <small class="error" id="e_mail-error"></small>
                            </div>
                        <?php else: ?>
                            <!-- Meno -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Meno</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control">
                                <small class="error" id="name-error"></small>
                            </div>


                            <!-- Priezvisko -->
                            <div class="mb-3">
                                <label for="surname" class="form-label">Priezvisko</label>
                                <input type="text"
                                       name="surname"
                                       id="surname"
                                       class="form-control">
                                <small class="error" id="surname-error"></small>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       id="e_mail"
                                       class="form-control">
                                <small class="e_mail" id="e_mail-error"></small>
                            </div>
                        <?php endif; ?>

                        <!-- Telefón -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefón</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control">
                            <small class="error" id="phone-error"></small>
                        </div>

                        <hr>

                        <!-- Adresa -->
                        <div class="mb-3">
                            <label for="street" class="form-label">Ulica</label>
                            <input type="text" name="street" id="street" class="form-control">
                            <small class="error" id="street-error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">Mesto</label>
                            <input type="text" name="city" id="city" class="form-control">
                            <small class="error" id="city-error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="PSC" class="form-label">PSČ</label>
                            <input type="text" name="PSC" id="PSC" class="form-control">
                            <small class="error" id="PSC-error"></small>
                        </div>

                        <hr>

                        <!-- Spôsob dopravy -->
                        <div class="mb-3">
                            <label class="form-label">Spôsob dopravy</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery" id="delivery-k" value="kurier">
                                <label for="delivery-k" class="form-check-label">Kuriér</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery" id="delivery-p" value="posta">
                                <label for="delivery-p" class="form-check-label">Slovenská pošta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery" id="delivery-o" value="osobne">
                                <label for="delivery-o" class="form-check-label">Osobný odber</label>
                            </div>
                            <div id="delivery-error" class="error"></div>
                        </div>


                        <!-- Spôsob platby -->
                        <div class="mb-3">
                            <label class="form-label">Spôsob platby</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id='payment-h' value="hotovost">
                                <label for='payment-h' class="form-check-label">Hotovosť</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id='payment-k' value="karta">
                                <label for='payment-k' class="form-check-label">Platba kartou</label>
                            </div>
                            <div id="payment-error" class="error"></div>
                        </div>

                        <!-- Údaje k platbe kartou -->
                        <div id="cardPaymentFields" style="display: none;">
                            <hr>

                            <div class="mb-3">
                                <label for="card_number" class="form-label">Číslo karty</label>
                                <input type="number"
                                       name="card_number"
                                       id="card_number"
                                       class="form-control"
                                       placeholder="1234 5678 9012 3456">
                                <small class="error" id="card_number-error"></small>
                            </div>

                            <div class="mb-3">
                                <label for="card_expiry" class="form-label">Platnosť (MM/RR)</label>
                                <input type="text"
                                       name="card_expiry"
                                       id="card_expiry"
                                       class="form-control"
                                       placeholder="MM/RR">
                                <small class="error" id="card_expiry-error"></small>
                            </div>

                            <div class="mb-3">
                                <label for="card_cvc" class="form-label">CVC</label>
                                <input type="text"
                                       name="card_cvc"
                                       id="card_cvc"
                                       class="form-control"
                                       placeholder="123">
                                <small class="error" id="card_cvc-error"></small>
                            </div>
                        </div>

                        <!-- Súhlas -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="terms" id="terms" class="form-check-input">
                            <label for="terms" class="form-check-label">
                                Súhlasím s obchodnými podmienkami
                            </label>
                            <div id="terms-error" class="error"></div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-success w-100">
                            Potvrdiť objednávku
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $link->asset('js/personalInfoValidate.js') ?>"></script>
<script src="<?= $link->asset('js/order.js') ?>"></script>
<script src="<?= $link->asset('js/cardCheck.js') ?>"></script>