<?php
/** @var Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book $book */
/** @var array $formErrors */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><?= $book->getId() ? 'Upraviť knihu' : 'Pridať knihu' ?></h4>
                </div>
                <div class="card-body">

                    <?php if (!empty($formErrors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($formErrors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $link->url('books.save') ?>" method="post" enctype="multipart/form-data" id="bookForm">

                        <input type="hidden" name="id" value="<?= $book->getId() ?? '' ?>">

                        <div class="mb-3">
                            <label for="title" class="form-label">Názov knihy</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($book->getTitle() ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Autor</label>
                            <input type="text" name="author" id="author" class="form-control" value="<?= htmlspecialchars($book->getAuthor() ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="genre" class="form-label">Žáner</label>
                            <input type="text" name="genre" id="genre" class="form-control" value="<?= htmlspecialchars($book->getGenre() ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Formát knihy</label>
                            <div>
                                <input type="radio" id="formatE" name="format" value="E" <?= $book->getFormat() === 'E' ? 'checked' : '' ?>>
                                <label for="formatE">Elektronický</label>

                                <input type="radio" id="formatF" name="format" value="F" <?= $book->getFormat() === 'F' ? 'checked' : '' ?>>
                                <label for="formatF">Fyzický</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Cena (€)</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= htmlspecialchars($book->getPrice() ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="number_availible" class="form-label">Počet dostupných kusov</label>
                            <input type="number" name="number_availible" id="number_availible" class="form-control" value="<?= htmlspecialchars($book->getNumberAvailible() ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="pages" class="form-label">Počet strán</label>
                            <input type="number" name="pages" id="pages" class="form-control" value="<?= htmlspecialchars($book->getPages() ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="text" class="form-label">Popis / text knihy</label>
                            <textarea name="text" id="text" class="form-control"><?= htmlspecialchars($book->getText() ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="cover" class="form-label">Obálka knihy</label>
                            <input type="file" name="cover" id="cover" class="form-control">
                            <?php if ($book->getCoverPath()): ?>
                                <div class="mt-2">
                                    <div class="text-muted mb-1">Aktuálna obálka:</div>
                                    <img src="<?= $book->getCoverPath() ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" class="book-cover-img">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= $link->url('books.index') ?>" class="btn btn-secondary">Zrušiť</a>
                            <button type="submit" class="btn btn-success">Uložiť</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $link->asset('js/bookFormat.js') ?>"></script>
