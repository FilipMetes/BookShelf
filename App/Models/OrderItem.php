<?php

namespace App\Models;

use Framework\Core\Model;
use Framework\DB\Connection;

class OrderItem extends Model
{
    protected static ?string $tableName = 'order_items';
    protected static ?string $primaryKey = null; // zložený PK – nech ORM nepoužíva id
    public int $id_order;
    public int $id_book;
    public int $countItems; // názov musí sedieť s DB

    public function __construct(array $data = [])
    {
        $this->id_order = $data['id_order'] ?? 0;
        $this->id_book = $data['id_book'] ?? 0;
        $this->countItems = $data['countItems'] ?? 0; // ✅ použijeme countItems
    }

    public function getIdOrder(): int { return $this->id_order; }
    public function setIdOrder(int $v): void { $this->id_order = $v; }

    public function getIdBook(): int { return $this->id_book; }
    public function setIdBook(int $v): void { $this->id_book = $v; }

    public function getCountItems(): int { return $this->countItems; } // ✅
    public function setCountItems(int $v): void { $this->countItems = $v; } // ✅
}
