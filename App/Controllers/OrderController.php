<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Order;
use App\Models\Book;
use App\Models\OrderItem;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class OrderController extends BaseController
{
    public function index(Request $request): Response
    {
        $user = $this->user;

        // flash errors
        $errors = $this->app->getSession()->get('errors') ?? [];
        $this->app->getSession()->remove('errors');

        return $this->html(compact('user', 'errors'));
    }

    public function checkout(Request $request): Response
    {
        $user = $this->user;

        if (!$user->isLoggedIn()) {
            $this->app->getSession()->remove('cart');
            return $this->redirect($this->url('shopcart.index'));
        }

        $cart = $this->app->getSession()->get('cart');

        if (empty($cart)) {
            $this->app->getSession()->set('errors', [
                'Košík je prázdny.'
            ]);
            return $this->redirect($this->url('order.index'));
        }

        // validácia formulára
        $errors = $this->formErrors($request);

        if (!empty($errors)) {
            $this->app->getSession()->set('errors', $errors);
            return $this->redirect($this->url('order.index'));
        }

        // kontrola skladu
        foreach ($cart as $bookId => $count) {
            $book = Book::getOne($bookId);
            if (!$book) continue;

            if ($book->getNumberAvailible() < $count) {
                $this->app->getSession()->set('errors', [
                    'Nie je dostatok kusov knihy: ' . $book->getTitle()
                ]);
                return $this->redirect($this->url('order.index'));
            }
        }

        // vytvorenie objednávky
        $order = new Order([
            'id_user' => $user->getId(),
            'date' => date('Y-m-d'),
            'delivery' => $request->value('delivery'),
            'state' => 'P'
        ]);
        $order->save();

        foreach ($cart as $bookId => $count) {
            $book = Book::getOne($bookId);
            if (!$book) continue;

            (new OrderItem([
                'id_order' => $order->getId(),
                'id_book' => $bookId,
                'countItems' => $count
            ]))->save();

            $book->setNumberAvailible(
                $book->getNumberAvailible() - $count
            );
            $book->save();
        }

        // vyčistenie košíka
        $this->app->getSession()->remove('cart');

        return $this->redirect($this->url('shopcart.index'));
    }

    private function formErrors(Request $request): array
    {
        $errors = [];

        $requiredFields = [
            'name' => 'Meno je povinné.',
            'surname' => 'Priezvisko je povinné.',
            'email' => 'Email je povinný.',
            'phone' => 'Telefón je povinný.',
            'street' => 'Ulica je povinná.',
            'city' => 'Mesto je povinné.',
            'psc' => 'PSČ je povinné.',
        ];

        foreach ($requiredFields as $field => $message) {
            if (trim($request->value($field)) === '') {
                $errors[] = $message;
            }
        }

        if (!filter_var($request->value('email'), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Neplatný email.';
        }

        if (!$request->value('delivery')) {
            $errors[] = 'Nie je zvolený spôsob dopravy.';
        }

        if (!$request->value('payment')) {
            $errors[] = 'Nie je zvolený spôsob platby.';
        }

        if ($request->value('payment') === 'karta') {
            if (trim($request->value('card_number')) === '') {
                $errors[] = 'Číslo karty je povinné.';
            }
            if (trim($request->value('card_expiry')) === '') {
                $errors[] = 'Platnosť karty je povinná.';
            }
            if (trim($request->value('card_cvc')) === '') {
                $errors[] = 'CVC je povinné.';
            }
        }

        if (!$request->value('terms')) {
            $errors[] = 'Musíte súhlasiť s obchodnými podmienkami.';
        }

        return $errors;
    }
}
