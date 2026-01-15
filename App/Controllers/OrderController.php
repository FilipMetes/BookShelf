<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Order;
use App\Models\Book;
use App\Models\OrderItem;
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
        return $this->html();
    }

    public function checkout(Request $request): Response
    {
        $user = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$user) {
            // namiesto výnimky
            $this->app->getSession()->set('flash_message', 'Musíte byť prihlásený, aby ste mohli pokračovať v objednávke.');
            return $this->redirect($this->url('shopcart.index'));
        }


        $cart = $this->app->getSession()->get('cart');
        if (empty($cart)) {
            $this->app->getSession()->set('errors', [
                'Košík je prázdny. Nie je možné vytvoriť objednávku.'
            ]);
            return $this->redirect($this->url('shopcart.index'));
        }

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


        // vytvorenie objednávky
        $order = new Order([
            'id_user' => $user->getId(),
            'date' => date('Y-m-d'),
            'delivery' => '',
            'state' => 'P'
        ]);
        $order->save();

        // položky objednávky + odpis zo skladu
        foreach ($cart as $bookId => $count) {
            $book = Book::getOne($bookId);
            $orderItem = new OrderItem([
                'id_order' => $order->getId(),
                'id_book' => $bookId,
                'countItems' => $count
            ]);

            $orderItem->save();

            $book->setNumberAvailible($book->getNumberAvailible() - $count);
            $book->save();
        }

        // vymazanie session košíka
        $this->app->getSession()->remove('cart');

        return $this->redirect($this->url('shopcart.index'));
    }

}
