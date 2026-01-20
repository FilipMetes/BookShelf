<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\FavouriteBook;
use App\Models\Book;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\HttpException;

class ProfileController extends BaseController
{
    public function index(Request $request): Response
    {
        $user = $this->user;

        if (!$user->isLoggedIn()) {
            return $this->redirect($this->url('home.index'));
        }

        $userId = $user->getId();


        $orders = Order::getAll('id_user = ? AND state = ?', [$userId, 'P']);

        $orderedBooks = [];

        foreach ($orders as $order) {
            $orderitems = OrderItem::getAll('id_order = ?', [$order->getId()]);

            foreach ($orderitems as $item) {
                $book = Book::getOne($item->getIdBook());
                if ($book) {
                    $orderedBooks[] = [
                        'book' => $book,
                        'count' => $item->getCountItems(),
                        'orderDate' => $order->getDate()
                    ];
                }
            }
        }

        $favourites = FavouriteBook::getAll('id_user = ?', [$userId]);

        $favouriteBooks = [];

        foreach ($favourites as $fav) {
            $book = Book::getOne($fav->getBookId());
            if ($book) {
                $favouriteBooks[] = [
                    'book' => $book,
                    'date' => $fav->getDate()
                ];
            }
        }


        return $this->html(compact(
            'user',
            'orderedBooks',
            'favouriteBooks'
        ));

    }

    public function edit(Request $request): Response
    {
        $sessionUser = $this->user;

        if (!$sessionUser->isLoggedIn()) {
            return $this->redirect($this->url('home.index'));
        }

        $user = User::getOne($sessionUser->getId());
        if (!$user) {
            return $this->redirect($this->url('home.index'));
        }

        $session = $this->app->getSession();

        $errors = $session->get('errors') ?? [];
        $session->remove('errors'); // 🔥 flash

        return $this->html(compact('user', 'errors'), 'form');
    }


    public function save(Request $request): Response
    {
        $sessionUser = $this->user;

        if (!$sessionUser->isLoggedIn()) {
            throw new HttpException(401, "Musíš byť prihlásený.");
        }

        $user = User::getOne($sessionUser->getId());
        if (!$user) {
            throw new HttpException(404, "Používateľ nenájdený.");
        }

        $errors = $this->formErrors($request);

        if (!empty($errors)) {
            $this->app->getSession()->set('errors', $errors);

            return $this->redirect($this->url('profile.edit'));
        }

        // ukladanie
        $user->setName($request->value('name'));
        $user->setSurname($request->value('surname'));
        $user->setCity($request->value('city'));
        $user->setPSC($request->value('PSC'));
        $user->setStreet($request->value('street'));
        $user->setEmail($request->value('e_mail'));

        if ($request->value('password')) {
            $user->setPassword($request->value('password'));
        }

        $user->save();

        // aktualizuj session usera
        $this->app->getSession()->set(Configuration::IDENTITY_SESSION_KEY, $user);

        return $this->redirect($this->url('profile.index'));
    }


    private function formErrors(Request $request): array
    {
        $errors = [];

        $sessionUser = $this->user;
        $currentUserId = $sessionUser->getId();

        $name = trim((string)$request->value('name'));
        $surname = trim((string)$request->value('surname'));
        $email = trim((string)$request->value('e_mail'));

        if ($name === '') {
            $errors[] = "Meno je povinné.";
        }
        if ($surname === '') {
            $errors[] = "Priezvisko je povinné.";
        }

        // Email - najprv formát
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Zadaný e-mail nie je platný.";
        } else {
            // Kontrola unikátnosti: ignoruj aktuálneho používateľa (id <> ?)
            if ($currentUserId !== null) {
                $count = User::getCount('e_mail = ? AND id <> ?', [$email, $currentUserId]);
            } else {
                $count = User::getCount('e_mail = ?', [$email]);
            }

            if ($count > 0) {
                $errors[] = 'Používateľ s týmto emailom už existuje.';
            }
        }

        // PSČ
        if ($PSC = $request->value('PSC')) {
            if (!preg_match('/^\d{5}$/', $PSC)) {
                $errors[] = "PSČ musí byť presne 5 číslic.";
            }
        }

        // Heslo
        $pass = $request->value('password');
        if ($pass) {
            if (strlen($pass) < 6) $errors[] = "Heslo musí mať aspoň 6 znakov.";
        }

        return $errors;
    }

}
