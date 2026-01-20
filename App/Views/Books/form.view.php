<?php
/** @var Framework\Support\LinkGenerator $link */
/** @var \App\Models\Book $book */
/** @var array $formErrors */

use App\Models\Genres;
?>

<?php if (!empty($formErrors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($formErrors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= $link->url('books.save') ?>" method="post" enctype="multipart/form-data" id="bookForm">
    <input type="hidden" name="id" value="<?= $book->getId() ?? '' ?>">


    <div class="mb-3">
        <label for="title" class="form-label">Názov knihy</label>
        <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($book->getTitle() ?? '') ?>">
        <small class="error" id="title-error"></small>
    </div>

    <div class="mb-3">
        <label for="author" class="form-label">Autor</label>
        <input type="text" name="author" id="author" class="form-control" value="<?= htmlspecialchars($book->getAuthor() ?? '') ?>">
        <small class="error" id="author-error"></small>
    </div>

    <div class="mb-3">
        <label for="genre" class="form-label">Žáner</label>
        <select name="genre" id="genre" class="form-select">
            <option value="">Vyberte žáner</option>
            <?php foreach(Genres::all() as $g): ?>
                <option value="<?= htmlspecialchars($g) ?>" <?= $book->getGenre() === $g ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div id="genre-error" class="error"></div>
    </div>

    <div class="mb-3">
        <label class="form-label">Formát knihy</label>
        <div>
            <input type="radio" id="formatE" name="format" value="E" <?= $book->getFormat() === 'E' ? 'checked' : '' ?>>
            <label for="formatE">Elektronický</label>

            <input type="radio" id="formatF" name="format" value="F" <?= $book->getFormat() === 'F' ? 'checked' : '' ?>>
            <label for="formatF">Fyzický</label>
        </div>
        <div id="format-error" class="error"></div>
    </div>

    <div class="mb-3">
        <label for="year" class="form-label">Rok vydania</label>
        <input type="number" name="year" id="year" class="form-control" value="<?= htmlspecialchars($book->getNumberAvailible() ?? '') ?>">
        <div id="year-error" class="error"></div>
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
        <label for="sample" class="form-label">Ukážka knihy (PDF)</label>
        <input type="file" name="sample" id="sample" class="form-control" accept="application/pdf">
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

<script src="<?= $link->asset('js/bookFormat.js') ?>"></script>
