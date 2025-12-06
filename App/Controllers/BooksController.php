<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Book;
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
        try {
            return $this->html([
                'books' => Book::getAll()
            ]);
        } catch (Exception $e) {
            throw new HttpException(500, "DB Chyba: " . $e->getMessage());
        }
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

        if (is_null($book)) {
            throw new HttpException(404);
        }

        return $this->html(compact('book'));
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

        // VALIDÁCIA formulára
        $formErrors = $this->formErrors($request);
        if (count($formErrors) > 0) {
            return $this->html(
                compact('book', 'formErrors'),
                ($id > 0 ? 'edit' : 'add')
            );
        }

        // TEXTOVÉ POLIA až po validácii
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
            throw new HttpException(404);
        }

        if ($book->getCoverPath()) {
            @unlink(Configuration::UPLOAD_DIR . $book->getCoverPath());
        }

        $book->delete();

        return $this->redirect($this->url("books.index"));
    }

    /**
     * Validácia formulára
     */
    private function formErrors(Request $request): array
    {
        $errors = [];

        if ($request->value('title') == "") {
            $errors[] = "Názov knihy musí byť vyplnený.";
        }

        if ($request->value('author') == "") {
            $errors[] = "Autor musí byť vyplnený.";
        }

        $format = $request->value('format');
        if ($format === null || $format === '') {
            $errors[] = "Formát musí byť vyplnený.";
        }

        // kontrola typu cover obrázka
        $file = $request->file('cover');
        if ($file && $file->getName() != "" &&
            !in_array($file->getType(), ['image/jpeg', 'image/png'])) {
            $errors[] = "Obrázok obálky musí byť typu JPG alebo PNG!";
        }

        return $errors;
    }
}
