<?php

/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card shadow-sm rounded">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">BookShelf Library</h4>
                </div>
                <div class="card-body">

                    <p><strong>Adresa:</strong> Oravská Polhora, Slovakia</p>
                    <p><strong>Tel. číslo:</strong> +421/915 123 456</p>
                    <p><strong>GPS:</strong> 49°27'30"N 19°34'00"E</p>

                    <div class="mt-3">
                        <iframe width="100%" height="300"
                                src="https://www.openstreetmap.org/export/embed.html?bbox=19.565%2C49.45%2C19.58%2C49.465&amp;layer=mapnik&amp;marker=49.4583%2C19.5667"
                                style="border:0;">
                        </iframe>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="<?= $link->url("home.index") ?>" class="btn btn-dark">Späť na domovskú stránku</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
