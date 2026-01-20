<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Order;
use App\Models\Book;
use App\Models\OrderItem;
use App\Models\User;
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

        if (!$user->isLoggedIn()) {
            $this->app->getSession()->remove('cart');
            return $this->redirect($this->url('shopcart.index'));
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
            'e_mail' => 'Email je povinný.',
            'phone' => 'Telefón je povinný.',
            'street' => 'Ulica je povinná.',
            'city' => 'Mesto je povinné.',
            'PSC' => 'PSČ je povinné.',
        ];

        foreach ($requiredFields as $field => $message) {
            if (trim($request->value($field) ?? '') === '') {
                $errors[] = $message;
            }

        }

        if (!filter_var($request->value('e_mail'), FILTER_VALIDATE_EMAIL)) {
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

    public function listOrders(Request $request): Response
    {
        $user = $this->user;

        // len admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            return $this->redirect($this->url('home.index'));
        }

        $userId = (int)$request->value('id');
        $user = User::getOne($userId);
        if (!$userId) {
            return $this->redirect($this->url('admin.index'));
        }

        // objednávky daného používateľa
        $orders = Order::getAll(
            'id_user = ?',
            [$userId],
            'date DESC'
        );

        $ordersWithItems = [];

        foreach ($orders as $order) {
            $items = OrderItem::getAll(
                'id_order = ?',
                [$order->getId()]
            );

            $books = [];

            foreach ($items as $item) {
                $book = Book::getOne($item->getIdBook());
                if ($book) {
                    $books[] = [
                        'book' => $book,
                        'count' => $item->getCountItems()
                    ];
                }
            }

            $ordersWithItems[] = [
                'order' => $order,
                'books' => $books
            ];
        }

        return $this->html(compact('ordersWithItems', 'user'), 'listOrders');
    }

    public function deleteOrder(Request $request): Response
    {
        $user = $this->user;

        // len admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            return $this->redirect($this->url('home.index'));
        }

        $orderId = (int)$request->value('order_id');
        if (!$orderId) {
            return $this->redirect($this->url('admin.index'));
        }

        $order = Order::getOne($orderId);
        if (!$order) {
            return $this->redirect($this->url('admin.index'));
        }

        // vrátenie kníh späť na sklad
        $items = OrderItem::getAll('id_order = ?', [$orderId]);

        foreach ($items as $item) {
            $book = Book::getOne($item->getIdBook());
            if ($book) {
                $book->setNumberAvailible(
                    $book->getNumberAvailible() + $item->getCountItems()
                );
                $book->save();
            }

            // zmazanie položky objednávky
            $item->delete();
        }

        // zmazanie objednávky
        $order->delete();

        return $this->redirect(
            $this->url('order.listOrders', ['id' => $order->getIdUser()])
        );
    }


}
