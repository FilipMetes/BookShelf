<?php

namespace App\Models;

use Framework\Core\Model;

class OrderItem extends Model
{
    protected static ?string $tableName = 'order_items';

    public ?int $id = null;
    public int $id_order;
    public int $id_book;
    public int $countItems;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->id_order = isset($data['id_order']) ? (int)$data['id_order'] : 0;
        $this->id_book = isset($data['id_book']) ? (int)$data['id_book'] : 0;
        $this->countItems = isset($data['countItems']) ? (int)$data['countItems'] : 0;
    }

    // Gettery / settery
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): int
    {
        return $this->id_order;
    }

    public function setIdOrder(int $v): void
    {
        $this->id_order = $v;
    }

    public function getIdBook(): int
    {
        return $this->id_book;
    }

    public function setIdBook(int $v): void
    {
        $this->id_book = $v;
    }

    public function getCountItems(): int
    {
        return $this->countItems;
    }

    public function setCountItems(int $v): void
    {
        $this->countItems = $v;
    }
}
