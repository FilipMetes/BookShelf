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

class OrderController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->html();
    }

    public function shopCart(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$sessionUser) {
            throw new HttpException(401, "Musíš byť prihlásený.");
        }

        $userId = $sessionUser->getId();

        // nájdeme košík (objednávku v stave C)
        $orders = Order::getAll('id_user = ? AND state = ?', [$userId, 'C']);

        if (empty($orders)) {
            // prázdny košík
            return $this->html([
                'cartItems' => [],
                'totalPrice' => 0
            ]);
        }

        $order = $orders[0];

        // položky v košíku
        $items = OrderItem::getAll('id_order = ?', [$order->getId()]);

        $cartItems = [];
        $totalPrice = 0;

        foreach ($items as $item) {
            $book = Book::getOne($item->getIdBook());

            if (!$book) {
                continue;
            }

            $subtotal = $book->getPrice() * $item->getCountItems();
            $totalPrice += $subtotal;

            $cartItems[] = [
                'book' => $book,
                'count' => $item->getCountItems(),
                'subtotal' => $subtotal
            ];
        }

        return $this->html(compact('cartItems', 'totalPrice'));
    }


    public function add(Request $request): Response
    {
        $sessionUser = $this->app->getSession()->get(Configuration::IDENTITY_SESSION_KEY);

        if (!$sessionUser) {
            throw new HttpException(401, "Musíš byť prihlásený.");
        }

        $userId = $sessionUser->getId();

        // Získame ID knihy z GET alebo POST
        $bookId = (int)$request->value('book_id');
        if (!$bookId) {
            throw new HttpException(400, "Neplatné ID knihy.");
        }

        $book = Book::getOne($bookId);
        if (!$book) {
            throw new HttpException(404, "Kniha nenájdená.");
        }

        $formErrors = [];

        if ($request->isPost()) {
            $count = max(1, (int)$request->value('count', 1));

            // Získame existujúcu objednávku (košík)
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

            // Skontrolujeme, či už existuje OrderItem
            $existingItems = OrderItem::getAll('id_order = ? AND id_book = ?', [$order->getId(), $book->getId()]);

            if (!empty($existingItems)) {
                // Existuje → aktualizujeme countItems
                $orderItem = $existingItems[0];
                $orderItem->setCountItems($orderItem->getCountItems() + $count);

                // Explicitný UPDATE pre zložený PK
                $sql = "UPDATE `order_items` 
                    SET countItems=:countItems 
                    WHERE id_order=:id_order AND id_book=:id_book";
                $stmt = \Framework\DB\Connection::getInstance()->prepare($sql);
                $stmt->execute([
                    'countItems' => $orderItem->getCountItems(),
                    'id_order'   => $orderItem->getIdOrder(),
                    'id_book'    => $orderItem->getIdBook()
                ]);

            } else {
                // Neexistuje → vytvoríme nový OrderItem
                $orderItem = new OrderItem([
                    'id_order' => $order->getId(),
                    'id_book' => $book->getId(),
                    'countItems' => $count
                ]);
                $orderItem->save(); // INSERT
            }

            return $this->redirect($this->url("books.index"));
        }

        // GET → zobrazíme formulár
        return $this->html(compact('book', 'formErrors'));
    }


}
