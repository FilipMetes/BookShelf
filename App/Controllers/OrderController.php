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
use Exception;

class OrderController extends BaseController
{
    public function index(Request $request): Response
    {
        $user = $this->user;

        $errors = $this->app->getSession()->get('errors') ?? [];
        $this->app->getSession()->remove('errors');

        return $this->html(compact('user', 'errors'));
    }

    /**
     * @throws Exception
     */
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
            if (!$book) {
                continue;
            }

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
            if (!$book) {
                continue;
            }

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

        // spôsob platby
        $payment = $request->value('payment');

        if (!$payment) {
            $errors[] = 'Nie je zvolený spôsob platby.';
        }

        $PSC = $request->value('PSC');
            if (!ctype_digit($PSC)) {
                $errors[] = "PSČ musí byť číslo.";
            } elseif (strlen($PSC) !== 5) {
                $errors[] = "PSČ musí byť presne 5 číslic.";
            }

        $phone = trim($request->value('phone'));
            $digits = preg_replace('/[^\d]/', '', $phone);

            if (!ctype_digit($digits)) {
                $errors[] = "Telefón musí byť číslo.";
            } elseif (strlen($digits) < 7 || strlen($digits) > 15) {
                $errors[] = "Telefón musí obsahovať 7 až 15 číslic.";
            }


        if ($payment === 'karta') {

            // ČÍSLO KARTY
            $cardNumberRaw = $request->value('card_number');
            $cardNumber = preg_replace('/\D/', '', $cardNumberRaw);

            if ($cardNumber === '') {
                $errors[] = 'Číslo karty je povinné.';
            } elseif (!ctype_digit($cardNumber)) {
                $errors[] = 'Číslo karty musí obsahovať iba číslice.';
            } elseif (strlen($cardNumber) !== 16) {
                $errors[] = 'Číslo karty musí mať 16 číslic.';
            }

            // EXPIRÁCIA (MM/YY alebo MM/YYYY)
            $expiry = trim($request->value('card_expiry'));

            if ($expiry === '') {
                $errors[] = 'Platnosť karty je povinná.';
            } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2,4}$/', $expiry)) {
                $errors[] = 'Neplatný formát dátumu platnosti (MM/YY).';
            }

            // CVC
            $cvcRaw = $request->value('card_cvc');
            $cvc = preg_replace('/\D/', '', $cvcRaw);

            if ($cvc === '') {
                $errors[] = 'CVC kód je povinný.';
            } elseif (!ctype_digit($cvc)) {
                $errors[] = 'CVC musí obsahovať iba číslice.';
            } elseif (strlen($cvc) < 3 || strlen($cvc) > 4) {
                $errors[] = 'CVC musí mať 3 alebo 4 číslice.';
            }
        }


        if (!$request->value('terms')) {
            $errors[] = 'Musíte súhlasiť s obchodnými podmienkami.';
        }

        return $errors;
    }

    /**
     * @throws Exception
     */
    public function listOrders(Request $request): Response
    {
        $user = $this->user;

        // len admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            return $this->redirect($this->url('home.index'));
        }

        $userId = (int)$request->value('id');
        $userOrder = User::getOne($userId);
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

        return $this->html(compact('ordersWithItems', 'userOrder'), 'listOrders');
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public function markDelivered(Request $request): Response
    {
        // len admin
        if (!$this->user->isLoggedIn() || !$this->user->isAdmin()) {
            return $this->redirect($this->url('home.index'));
        }

        $orderId = (int) $request->value('order_id');
        if (!$orderId) {
            return $this->redirect($this->url('admin.index'));
        }

        $order = Order::getOne($orderId);
        if (!$order) {
            return $this->redirect($this->url('admin.index'));
        }


        if ($request->value('delivered')) {
            $order->setState('D');
            $order->save();
        }

        return $this->redirect(
            $this->url('order.listOrders', ['id' => $order->getIdUser()])
        );
    }
}
