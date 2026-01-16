<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Order;
use App\Models\Book;
use App\Models\OrderItem;
use App\Models\User;
use Exception;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\DB\Connection;

class OrderController extends BaseController
{
    public function index(Request $request): Response
    {

        $user = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        return $this->html(compact('user'));
    }

    public function checkout(Request $request): Response
    {
        $user = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$user) {
            $this->app->getSession()->remove('cart');
            return $this->redirect($this->url('shopcart.index'));
        }


        $cart = $this->app->getSession()->get('cart');
        if (empty($cart)) {
            $this->app->getSession()->set('errors', [
                'Košík je prázdny. Nie je možné vytvoriť objednávku.'
            ]);
            return $this->redirect($this->url('shopcart.index'));
        }

        // ====== VALIDÁCIA FORMULÁRA ======
        $delivery = $request->value('delivery');

        if (!$delivery) {
            $this->app->getSession()->set('errors', [
                'Nie je zvolený spôsob dopravy.'
            ]);
            return $this->redirect($this->url('order.index'));
        }

        // ====== KONTROLA SKLADU ======
        $errors = [];

        foreach ($cart as $bookId => $count) {
            $book = Book::getOne($bookId);
            if (!$book) continue;

            if ($book->getNumberAvailible() < $count) {
                $errors[] = 'Nie je dostatok kusov knihy: ' . $book->getTitle();
            }
        }

        if (!empty($errors)) {
            $this->app->getSession()->set('errors', $errors);
            return $this->redirect($this->url('shopcart.index'));
        }

        // ====== VYTVORENIE OBJEDNÁVKY ======
        $order = new Order([
            'id_user' => $user->getId(),
            'date' => date('Y-m-d'),
            'delivery' => $delivery,
            'state' => 'P'
        ]);

        $order->save();

        // ====== POLOŽKY OBJEDNÁVKY ======
        foreach ($cart as $bookId => $count) {
            $book = Book::getOne($bookId);
            if (!$book) continue;

            $orderItem = new OrderItem([
                'id_order' => $order->getId(),
                'id_book' => $bookId,
                'countItems' => $count
            ]);

            $orderItem->save();

            $book->setNumberAvailible(
                $book->getNumberAvailible() - $count
            );
            $book->save();
        }

        // ====== VYČISTENIE KOŠÍKA ======
        $this->app->getSession()->remove('cart');

        return $this->redirect($this->url('shopcart.index'));
    }


}
