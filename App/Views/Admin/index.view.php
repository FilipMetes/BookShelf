<?php

/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div>
                Vitaj, <strong><?= $user->getName() ?></strong>!<br><br>
                Boli ste prihlásený ako admin.
            </div>
        </div>
    </div>
</div>