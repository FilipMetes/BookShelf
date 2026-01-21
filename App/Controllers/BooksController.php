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
     * @throws Exception
     */
    public function index(Request $request): Response
    {
        $perPage = 12;

        /*
            Vypracované s pomocou AI
        */
        $page = max(1, (int)$request->value('page'));

        $offset = ($page - 1) * $perPage; // od ktorej knihy zaciname

        $books = self::getPage($perPage, $offset);
        $total = self::getTotalCount();

        $totalPages = (int)ceil($total / $perPage);
        /*
            koniec AI
        */

        return $this->html(compact('books', 'page', 'totalPages'));
    }

    /**
     * Formular na pridanie
     * @throws Exception
     */
    public function add(Request $request): Response
    {
        if (!$this->user->isAdmin()) {
            return $this->redirect($this->url('books.index'));
        }

        $book = new Book(); // vytvoríme prázdny objekt, aby $book existovalo vo view
        return $this->html(compact('book'));
    }

    /**
     * Formular na editaciu
     * @throws Exception
     */
    public function edit(Request $request): Response
    {
        if (!$this->user->isAdmin()) {
            return $this->redirect($this->url('books.index'));
        }

        $id = (int)$request->value('id');
        $book = Book::getOne($id);

        if (!$book) {
            return $this->redirect($this->url('books.index'));
        }

        return $this->html(compact('book'));
    }

    /**
     * @throws Exception
     */
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

        return $this->html(compact('book', 'reviewsData'));
    }

    /**
     * Uloženie knihy (create / update)
     * @throws Exception
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
            return $this->html(compact('book', 'formErrors'), $id > 0 ? 'edit' : 'add');
        }

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

        if ($file && $file->isOk()) {

            if ($oldCover !== '') {
                @unlink(Configuration::UPLOAD_DIR . $oldCover);
            }

            /*
                Vypracované s pomocou AI
            */
            $ext = pathinfo($file->getName(), PATHINFO_EXTENSION);
            $uniqueName = uniqid('cover_', true) . '.' . $ext;

            $target = Configuration::UPLOAD_DIR . $uniqueName;

            /*
                koniec AI
            */

            if (!$file->store($target)) {
                throw new HttpException(500, "Chyba pri ukladaní obrázka.");
            }

            $book->setCoverPath($uniqueName);
        }


        // UKÁŽKA KNIHY (PDF)
        $sample = $request->file('sample');

        if ($sample && $sample->isOk()) {

            /*
                Vypracované s pomocou AI
             */
            $ext = pathinfo($sample->getName(), PATHINFO_EXTENSION);
            $uniqueSample = uniqid('sample_', true) . '.' . $ext;

            $target = Configuration::UPLOAD_DIR . $uniqueSample;

            /*
                koniec AI
            */

            if (!$sample->store($target)) {
                throw new HttpException(500, "Chyba pri ukladaní PDF.");
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
     * @throws Exception
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
     * @throws Exception
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

        /*
            Vypracované s pomocou AI
        */
        // Kontrola typu cover obrázka
        $file = $request->file('cover');
        if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$file->isOk()) {
                $errors[] = $file->getErrorMessage();
            } else {
                $allowedTypes = ['image/jpeg', 'image/png'];

                if (!in_array($file->getType(), $allowedTypes)) {
                    $errors[] = "Obrázok obálky musí byť JPG alebo PNG.";
                }
            }
        }


        // Kontrola ukážky knihy (PDF)
        $sample = $request->file('sample');

        if ($sample && $sample->getError() !== UPLOAD_ERR_NO_FILE) {

            if (!$sample->isOk()) {
                $errors[] = $sample->getErrorMessage();
            } else {
                $ext = strtolower(pathinfo($sample->getName(), PATHINFO_EXTENSION));

                if ($ext !== 'pdf') {
                    $errors[] = "Ukážka knihy musí byť PDF.";
                }
            }
        }

        /*
            koniec AI
        */

        $year = $request->value('year');
        if (!is_numeric($year) || (int)$year <= 0) {
            $errors[] = "Rok musí byť kladné číslo.";
        }

        $price = $request->value('price');
        if (!is_numeric($price) || (float)$price < 0) {
            $errors[] = "Cena musí byť platné číslo >= 0.";
        }

        $numberAvailible = $request->value('number_availible');
        if (!is_numeric($numberAvailible) || (int)$numberAvailible < 0) {
            $errors[] = "Počet dostupných kusov musí byť celé číslo >= 0.";
        }

        $pages = $request->value('pages');
        if (!is_numeric($pages) || (int)$pages <= 0) {
            $errors[] = "Počet strán musí byť kladné číslo.";
        }

        return $errors;
    }

    /**
     * @throws Exception
     */
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


    /**
     * @throws Exception
     */
    /**
     * @throws Exception
     */
    public function addToFavourite(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        $bookId = (int)$request->post('book_id');
        $userId = $this->user->getId();

        if (!$bookId) {
            // ak je neplatné ID, redirect späť
            return $this->redirect($this->url('books.index'));
        }

        // kontrola, či už existuje
        $existing = FavouriteBook::getAll('id_user = ? AND id_book = ?', [$userId, $bookId]);

        if (empty($existing)) {
            $fav = new FavouriteBook([
                'id_user' => $userId,
                'id_book' => $bookId,
                'date' => date('Y-m-d')
            ]);
            $fav->save();
        }

        // PRG – redirect späť na detail knihy
        return $this->redirect($this->url('books.detail', ['id' => $bookId]));
    }




    /**
     * @throws Exception
     */
    public function removeFavourite(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('home.index'));
        }

        /*
            Vypracované s pomocou AI
        */
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: [];
        $bookId = (int)($data['book_id'] ?? 0);
        /*
            koniec AI
        */

        if (!$bookId) {
            return $this->json(['success' => false]);
        }

        $userId = $this->user->getId();
        $existing = FavouriteBook::getAll('id_user = ? AND id_book = ?', [$userId, $bookId]);

        if ($existing) {
            $existing[0]->delete();
        }

        return $this->json(['success' => true, 'book_id' => $bookId]);
    }



    /**
     * @throws Exception
     */
    public function removeSample(Request $request): Response
    {
        if (!$this->user->isLoggedIn() || !$this->user->isAdmin()) {
            return $this->redirect($this->url('books.index'));
        }

        $id = (int)$request->value('id');
        $book = Book::getOne($id);

        if (!$book || !$book->getSamplePath()) {
            return $this->redirect($this->url('books.detail', ['id' => $id]));
        }

        // zmazanie PDF zo súborov
        /*
            Vypracované s pomocou AI
        */
        $path = Configuration::UPLOAD_DIR . $book->getSamplePath();
        if (file_exists($path)) {
            @unlink($path);
        }
        /*
            koniec AI
        */

        // vymazanie z DB
        $book->setSamplePath(null);
        $book->save();

        return $this->redirect($this->url('books.detail', ['id' => $id]));
    }


    /**
     * @throws Exception
     */
    public static function getPage(int $limit, int $offset): array
    {

        return Book::getAll('', [], 'id DESC', $limit, $offset);
    }

    /**
     * @throws Exception
     */
    public static function getTotalCount(): int
    {
        return Book::getCount();
    }

}