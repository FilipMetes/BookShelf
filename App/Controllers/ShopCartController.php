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

class ShopCartController extends BaseController
{
    public function index(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$sessionUser) {
            throw new HttpException(401, "Musíš byť prihlásený.");
        }

        $userId = $sessionUser->getId();
        $orders = Order::getAll('id_user = ? AND state = ?', [$userId, 'C']);

        if (empty($orders)) {
            return $this->html([
                'cartItems' => [],
                'totalPrice' => 0,
                'order' => null
            ]);
        }

        $order = $orders[0];
        $items = OrderItem::getAll('id_order = ?', [$order->getId()]);

        $cartItems = [];
        $totalPrice = 0;

        foreach ($items as $item) {
            $book = Book::getOne($item->getIdBook());
            if (!$book) continue;

            $subtotal = $book->getPrice() * $item->getCountItems();
            $totalPrice += $subtotal;

            $cartItems[] = [
                'book' => $book,
                'count' => $item->getCountItems(),
                'subtotal' => $subtotal
            ];
        }

        return $this->html(compact('cartItems', 'totalPrice', 'order'));
    }

    public function removeFromCart(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);
        if (!$sessionUser) throw new HttpException(401, "Musíš byť prihlásený.");

        $userId = $sessionUser->getId();
        $bookId = (int)$request->value('id_book');
        $orderId = (int)$request->value('id_order');

        if (!$bookId || !$orderId) throw new HttpException(400, "Neplatná kniha alebo objednávka.");

        $book = Book::getOne($bookId);
        $order = Order::getOne($orderId);

        if (!$book || !$order || $order->getIdUser() != $userId || $order->getState() != 'C') {
            throw new HttpException(400, "Neplatná kniha alebo objednávka.");
        }

        $sql = "DELETE FROM `order_items` WHERE id_order=:id_order AND id_book=:id_book";
        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute([
            'id_order' => $orderId,
            'id_book'  => $bookId
        ]);

        return $this->redirect($this->url('shopcart.index'));
    }

    /**
     * Aktualizácia počtu položky v košíku (+ alebo -)
     */
    public function updateCartItem(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);
        if (!$sessionUser) throw new HttpException(401, "Musíš byť prihlásený.");

        $userId = $sessionUser->getId();
        $bookId = (int)$request->value('id_book');
        $orderId = (int)$request->value('id_order');
        $action = $request->value('action'); // "plus" alebo "minus"

        if (!$bookId || !$orderId || !in_array($action, ['plus','minus'])) {
            throw new HttpException(400, "Neplatná požiadavka.");
        }

        $orderItemList = OrderItem::getAll('id_order = ? AND id_book = ?', [$orderId, $bookId]);
        if (empty($orderItemList)) throw new HttpException(400, "Položka nenájdená.");

        $orderItem = $orderItemList[0];
        $newCount = $orderItem->getCountItems() + ($action === 'plus' ? 1 : -1);
        $newCount = max(1, $newCount); // minimum 1

        $sql = "UPDATE `order_items` SET countItems=:countItems 
                WHERE id_order=:id_order AND id_book=:id_book";
        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute([
            'countItems' => $newCount,
            'id_order' => $orderId,
            'id_book' => $bookId
        ]);

        return $this->redirect($this->url('shopcart.index'));
    }
}