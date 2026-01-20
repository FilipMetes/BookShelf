<?php

namespace App\Models;

use App\Configuration;
use Framework\Core\Model;
use Framework\DB\Connection;
use Exception;

class Order extends Model
{
    public ?int $id = null;
    public int $id_user;
    public string $date;
    public string $delivery;
    public string $state;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->id_user = isset($data['id_user']) ? (int)$data['id_user'] : 0;
        $this->date = $data['date'] ?? date('Y-m-d');  // default hodnota, keď nie je nastavená
        $this->delivery = $data['delivery'] ?? '';     // default prázdny string
        $this->state = $data['state'] ?? '';           // default prázdny string
    }

    // Gettery a settery
    public function getId(): ?int { return $this->id; }

    public function getIdUser(): int { return $this->id_user; }
    public function setIdUser(int $v): void { $this->id_user = $v; }

    public function getDate(): string { return $this->date; }
    public function setDate(string $v): void { $this->date = $v; }

    public function getDelivery(): string { return $this->delivery; }
    public function setDelivery(string $v): void { $this->delivery = $v; }

    public function getState(): string { return $this->state; }
    public function setState(string $v): void { $this->state = $v; }
}
