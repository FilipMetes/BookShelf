<?php
/** @var \App\Models\Book $book */
/** @var array $formErrors */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="card shadow-sm p-4">
    <h2 class="card-title mb-3">Pridať do košíka</h2>

    <p><strong><?= htmlspecialchars($book->getTitle()) ?></strong> od <?= htmlspecialchars($book->getAuthor()) ?></p>

    <?php if (!empty($formErrors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($formErrors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $link->url('shopcart.add') ?>">
        <input type="hidden" name="book_id" value="<?= $book->getId() ?>">

        <div class="mb-3">
            <label for="count" class="form-label">Počet kusov</label>
            <select name="count" id="count" class="form-select" required>
                <?php
                $maxCount = max(1, $book->getNumberAvailible()); // aspoň 1
                for ($i = 1; $i <= $maxCount; $i++):
                    ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>


        <button type="submit" class="btn btn-success">Pridať do košíka</button>
        <a href="<?= $link->url('books.index') ?>" class="btn btn-secondary">Späť na katalóg</a>
    </form>
</div>
