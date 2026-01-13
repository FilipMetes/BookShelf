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

    public function add(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);
        if (!$sessionUser) throw new HttpException(401, "Musíš byť prihlásený.");

        $userId = $sessionUser->getId();
        $bookId = (int)$request->value('book_id');
        if (!$bookId) throw new HttpException(400, "Neplatné ID knihy.");

        $book = Book::getOne($bookId);
        if (!$book) throw new HttpException(404, "Kniha nenájdená.");

        if ($request->isPost()) {
            $count = max(1, (int)$request->value('count', 1));

            $orders = Order::getAll('id_user = ? AND state = ?', [$userId, 'C']);
            if (!empty($orders)) {
                $order = $orders[0];
            } else {
                $order = new Order([
                    'id_user' => $userId,
                    'date' => date('Y-m-d'),
                    'delivery' => '',
                    'state' => 'C'
                ]);
                $order->save();
            }

            $existingItems = OrderItem::getAll('id_order = ? AND id_book = ?', [$order->getId(), $book->getId()]);
            if (!empty($existingItems)) {
                $orderItem = $existingItems[0];
                $orderItem->setCountItems($orderItem->getCountItems() + $count);

                $sql = "UPDATE `order_items` 
                        SET countItems=:countItems 
                        WHERE id_order=:id_order AND id_book=:id_book";
                $stmt = Connection::getInstance()->prepare($sql);
                $stmt->execute([
                    'countItems' => $orderItem->getCountItems(),
                    'id_order'   => $orderItem->getIdOrder(),
                    'id_book'    => $orderItem->getIdBook()
                ]);
            } else {
                $orderItem = new OrderItem([
                    'id_order' => $order->getId(),
                    'id_book' => $book->getId(),
                    'countItems' => $count
                ]);
                $orderItem->save();
            }

            return $this->redirect($this->url("books.index"));
        }

        return $this->html(compact('book'));
    }

    public function checkout(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);
        if (!$sessionUser) throw new HttpException(401, "Musíš byť prihlásený.");

        $userId = $sessionUser->getId();
        $orderId = (int)$request->value('id_order');
        $order = Order::getOne($orderId);

        if (!$order || $order->getIdUser() != $userId || $order->getState() != 'C') {
            throw new HttpException(400, "Neplatná objednávka.");
        }

        $items = OrderItem::getAll('id_order = ?', [$orderId]);

        foreach ($items as $item) {
            $book = Book::getOne($item->getIdBook());
            if (!$book) continue;

            if ($book->getNumberAvailible() < $item->getCountItems()) {
                throw new HttpException(400, "Nie je dostatok kusov knihy: " . $book->getTitle());
            }

            $book->setNumberAvailible($book->getNumberAvailible() - $item->getCountItems());
            $book->save();
        }

        $order->setState('P'); // zmena stavu na prebiehajúcu
        $order->save();

        return $this->redirect($this->url('shopcart.index'));
    }


}
