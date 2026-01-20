<?php

/** @var string $contentHTML */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title><?= App\Configuration::APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $link->asset('favicons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $link->asset('favicons/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $link->asset('favicons/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= $link->asset('favicons/site.webmanifest') ?>">
    <link rel="shortcut icon" href="<?= $link->asset('favicons/favicon.ico') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?= $link->asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= $link->asset('css/books.css') ?>">
    <link rel="stylesheet" href="<?= $link->asset('css/home.css') ?>">
    <link rel="stylesheet" href="<?= $link->asset('css/detail.css') ?>">
    <link rel="stylesheet" href="<?= $link->asset('css/shopcart.css') ?>">
    <link rel="stylesheet" href="<?= $link->asset('css/form.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $link->url('home.index') ?>">BookShelf</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto d-flex align-items-start">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url('books.index') ?>">Knihy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url('home.contact') ?>">Kontakt</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto d-flex align-items-start">
                <!--Košík-->
                <li class="nav-item me-3">
                    <a class="nav-link" href="<?= $link->url('shopCart.index') ?>">🛒 Košík</a>
                </li>

                <?php if ($user->isLoggedIn()) { ?>
                    <li class="nav-item me-3 d-flex">
                        <span class="navbar-text">Prihlásený: <b><?= htmlspecialchars($user->getName()) ?></b></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url('profile.index') ?>"> Profil </a>
                    </li>
                    <?php if ($user->isAdmin()) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?= $link->url('admin.index') ?>"> Admin </a>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url('auth.logout') ?>">Log out</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= App\Configuration::LOGIN_URL ?>">Log in</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>
</body>
</html>
