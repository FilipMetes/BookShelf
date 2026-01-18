<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Book;
use App\Models\Genres;
use App\Models\User;
use App\Models\FavouriteBook;
use App\Models\Review;


use Exception;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class BooksController extends BaseController
{
    /**
     * Zoznam knih
     */
    public function index(Request $request): Response
    {
        $perPage = 12;

        // aktuálna stránka z URL ?page=2
        $page = max(1, (int)$request->value('page'));

        $offset = ($page - 1) * $perPage;

        $books = Book::getPage($perPage, $offset);
        $total = Book::getTotalCount();

        $totalPages = (int)ceil($total / $perPage);

        return $this->html(compact('books', 'page', 'totalPages'));
    }

    /**
     * Formular na pridanie
     */
    public function add(Request $request): Response
    {
        $book = new Book(); // vytvoríme prázdny objekt, aby $book existovalo vo view
        return $this->html(compact('book'));
    }

    /**
     * Formular na editaciu
     */
    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $book = Book::getOne($id);

        if (!$book) {
            return $this->redirect($this->url('books.index'));
        }

        return $this->html(compact('book'));
    }

    public function detail(Request $request): Response
    {
        $id = (int)$request->value('id');
        if (!$id) {
            return $this->redirect($this->url('books.index'));
        }

        $book = Book::getOne($id);
        if (!$book) {
            return $this->redirect($this->url('books.index'));
        }

        $reviews = Review::getAll(
            'id_book = ?',
            [$id],
            'date DESC'
        );

        $reviewsData = [];

        foreach ($reviews as $review) {
            $user = User::getOne($review->getIdUser());

            $reviewsData[] = [
                'review' => $review,
                'user' => $user
            ];
        }

        return $this->html([
            'book' => $book,
            'reviewsData' => $reviewsData
        ]);
    }

    /**
     * Uloženie knihy (create / update)
     */
    public function save(Request $request): Response
    {
        $id = (int)$request->value('id');

        $oldCover = "";
        if ($id > 0) {
            $book = Book::getOne($id);
            $oldCover = $book->getCoverPath();
        } else {
            $book = new Book();
        }

        $formErrors = $this->formErrors($request);

        if (!empty($formErrors)) {
            return $this->html(['book' => $book, 'formErrors' => $formErrors], $id > 0 ? 'edit' : 'create');
        }


        // Naplnenie objektu až po validácii
        $book->setTitle($request->value('title'));
        $book->setAuthor($request->value('author'));
        $book->setGenre($request->value('genre'));
        $book->setFormat($request->value('format'));
        $book->setYear((int)$request->value('year'));
        $book->setPrice((float)$request->value('price'));
        $book->setNumberAvailible((int)$request->value('number_availible'));
        $book->setPages((int)$request->value('pages'));
        $book->setText($request->value('text'));

        // UPLOAD DIR
        if (!is_dir(Configuration::UPLOAD_DIR)) {
            mkdir(Configuration::UPLOAD_DIR, 0777, true);
        }

        // COVER OBRÁZOK
        $file = $request->file('cover');
        if ($file && $file->getName() != "") {

            if ($oldCover != "") {
                @unlink(Configuration::UPLOAD_DIR . $oldCover);
            }

            $unique = time() . '-' . $file->getName();
            $target = Configuration::UPLOAD_DIR . $unique;

            if (!$file->store($target)) {
                throw new HttpException(500, "Chyba pri ukladaní obrázka.");
            }

            $book->setCoverPath($unique);
        }

        // UKÁŽKA KNIHY (PDF)
        $sample = $request->file('sample');
        if ($sample && $sample->getName() !== '') {

            $uniqueSample = time() . '-' . $sample->getName();
            $targetSample = Configuration::UPLOAD_DIR . $uniqueSample;

            if (!$sample->store($targetSample)) {
                throw new HttpException(500, "Chyba pri ukladaní PDF ukážky.");
            }

            $book->setSamplePath($uniqueSample);
        }

        try {
            $book->save();
        } catch (Exception $e) {
            throw new Exception("DB Chyba: " . $e->getMessage());
        }

        return $this->redirect($this->url('books.index'));
    }

    /**
     * Zmazanie knihy
     */
    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $book = Book::getOne($id);

        if (!$book) {
            return $this->redirect($this->url('books.index'));
        }

        // zmaž obálku
        if ($book->getCoverPath()) {
            @unlink(Configuration::UPLOAD_DIR . $book->getCoverPath());
        }

        // zmaž PDF ukážku
        if ($book->getSamplePath()) {
            @unlink(Configuration::UPLOAD_DIR . $book->getSamplePath());
        }

        try {
            $book->delete();
        } catch (Exception $e) {
            return $this->redirect($this->url('books.index'));
        }

        return $this->redirect($this->url("books.index"));
    }

    /**
     * Validácia formulára
     */
    private function formErrors(Request $request): array
    {
        $errors = [];

        // Kontrola názvu
        if ($request->value('title') == "") {
            $errors[] = "Názov knihy musí byť vyplnený.";
        }

        // Kontrola žánru
        $genre = $request->value('genre');
        if (!$genre || !in_array($genre, Genres::all())) {
            $errors[] = "Vybraný žáner nie je platný.";
        }

        // Kontrola autora
        if ($request->value('author') == "") {
            $errors[] = "Autor musí byť vyplnený.";
        }

        // Unikátny názov + autor
        $title = $request->value('title');
        $author = $request->value('author');
        $id = (int)$request->value('id');

        if ($title && $author) {
            $existing = Book::getAll(
                "title = ? AND author = ? AND id <> ?",
                [$title, $author, $id]
            );

            if (!empty($existing)) {
                $errors[] = "Kniha s týmto názvom a autorom už existuje.";
            }
        }

        // Kontrola formátu
        $format = $request->value('format');
        if ($format === '' || !in_array($format, ['E','F'])) {
            $errors[] = "Formát musí byť vyplnený a platný.";
        }

        // Kontrola typu cover obrázka
        $file = $request->file('cover');
        if ($file && $file->getName() != "" && !in_array($file->getType(), ['image/jpeg', 'image/png'])) {
            $errors[] = "Obrázok obálky musí byť typu JPG alebo PNG!";
        }

        // Kontrola ukážky knihy (PDF)
        $sample = $request->file('sample');
        if ($sample && $sample->getName() !== '') {

            $ext = strtolower(pathinfo($sample->getName(), PATHINFO_EXTENSION));

            if ($ext !== 'pdf') {
                $errors[] = "Ukážka knihy musí byť vo formáte PDF.";
            }
        }

        return $errors;
    }

    public function rate(Request $request): Response
    {
        $bookId = (int)$request->value('book_id');
        $rating = (int)$request->value('rating');
        $reviewText = trim($request->value('review'));

        // musí byť prihlásený
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        // validácia ratingu
        if ($rating < 1 || $rating > 5) {
            return $this->redirect($this->url('books.detail', ['id' => $bookId]));
        }

        // voliteľná validácia textu
        if ($reviewText !== '' && strlen($reviewText) > 1000) {
            return $this->redirect($this->url('books.detail', ['id' => $bookId]));
        }

        $userId = $this->user->getId();

        // existuje už review?
        $existing = Review::getAll(
            'id_book = ? AND id_user = ?',
            [$bookId, $userId]
        );

        if ($existing) {
            $review = $existing[0];
            $review->setRating($rating);
            $review->setReview($reviewText ?: null);
            $review->setDate(date('Y-m-d'));
        } else {
            $review = new Review([
                'id_book' => $bookId,
                'id_user' => $userId,
                'rating'  => $rating,
                'review'  => $reviewText ?: null,
                'date'    => date('Y-m-d')
            ]);
        }

        $review->save();

        // PRG
        return $this->redirect($this->url('books.detail', ['id' => $bookId]));
    }


    public function addToFavourite(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        $bookId = (int)$request->value('book_id');
        $userId = $this->user->getId();

        $existing = FavouriteBook::getAll(
            'id_user = ? AND id_book = ?',
            [$userId, $bookId]
        );

        if (!$existing) {
            $fav = new FavouriteBook([
                'id_user' => $userId,
                'id_book' => $bookId,
                'date' => date('Y-m-d')
            ]);
            $fav->save();
        }

        return $this->json(['success' => true]);
    }


    public function removeFavourite(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            throw new HttpException(401, 'Neprihlásený');
        }

        $userId = $this->user->getId();
        $bookId = (int)$request->value('book_id');

        $existing = FavouriteBook::getAll(
            'id_user = ? AND id_book = ?',
            [$userId, $bookId]
        );

        if ($existing) {
            $existing[0]->delete();
        }

        // AJAX odpoveď
        return $this->json([
            'success' => true,
            'book_id' => $bookId
        ]);
    }

    public function removeSample(Request $request): Response
    {
        // iba admin
        if (!$this->user->isLoggedIn() || $this->user->getRole() !== 'A') {
            return $this->redirect($this->url('books.index'));
        }

        $id = (int)$request->value('id');
        $book = Book::getOne($id);

        if (!$book || !$book->getSamplePath()) {
            return $this->redirect($this->url('books.detail', ['id' => $id]));
        }

        // zmazanie PDF zo súborov
        $path = Configuration::UPLOAD_DIR . $book->getSamplePath();
        if (file_exists($path)) {
            @unlink($path);
        }

        // vymazanie z DB
        $book->setSamplePath(null);
        $book->save();

        return $this->redirect($this->url('books.detail', ['id' => $id]));
    }




}