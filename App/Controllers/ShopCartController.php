<?php

namespace App\Controllers;

use App\Models\Book;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class ShopCartController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        $cart = $this->app->getSession()->get('cart') ?? [];

        $errors = $this->app->getSession()->get('errors') ?? [];
        $this->app->getSession()->remove('errors');

        $cartItems = [];
        $totalPrice = 0;

        foreach ($cart as $bookId => $count) {
            $book = Book::getOne($bookId);
            if (!$book) {
                continue;
            }

            $subtotal = $book->getPrice() * $count;
            $totalPrice += $subtotal;

            $cartItems[] = [
                'book' => $book,
                'count' => $count,
                'subtotal' => $subtotal
            ];
        }

        return $this->html(compact('cartItems', 'totalPrice', 'errors'));
    }


    /**
     * @throws \Exception
     */
    public function add(Request $request): Response
    {
        $bookId = (int)$request->value('book_id');
        $count  = max(1, (int)$request->value('count', 1));

        if (!$bookId) {
            return $this->redirect($this->url("books.index"));
        }

        $book = Book::getOne($bookId);
        if (!$book) {
            return $this->redirect($this->url("books.index"));
        }

        $cart = $this->app->getSession()->get('cart') ?? [];
        $cart[$bookId] = ($cart[$bookId] ?? 0) + $count;

        $this->app->getSession()->set('cart', $cart);

        return $this->redirect($this->url('shopcart.index'));
    }

    /**
     * @throws \Exception
     */
    public function remove(Request $request): Response
    {
        $bookId = (int)$request->value('book_id');

        $cart = $this->app->getSession()->get('cart') ?? [];
        unset($cart[$bookId]);

        $this->app->getSession()->set('cart', $cart);

        return $this->redirect($this->url('shopcart.index'));
    }

    /**
     * @throws \Exception
     */
    public function update(Request $request): Response
    {
        $bookId = (int)$request->value('book_id');
        $action = $request->value('action');

        $cart = $this->app->getSession()->get('cart') ?? [];

        if (!isset($cart[$bookId])) {
            return $this->redirect($this->url('shopcart.index'));
        }

        if ($action === 'plus') {
            $cart[$bookId]++;
        } else if ($action === 'minus') {
            $cart[$bookId] = max(1, $cart[$bookId] - 1);
        }

        $this->app->getSession()->set('cart', $cart);

        return $this->redirect($this->url('shopcart.index'));
    }
}
