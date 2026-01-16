<?php

namespace App\Models;

use Framework\Core\Model;

class FavouriteBook extends Model
{
    protected static ?string $tableName = 'favourite_books';

    public ?int $id = null;
    public int $id_user;
    public int $id_book;
    public string $date;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_user = $data['id_user'] ?? 0;
        $this->id_book = $data['id_book'] ?? 0;
        $this->date = $data['date'] ?? date('Y-m-d');
    }

    // ===== GETTERY / SETTERY =====

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->id_user;
    }

    public function setUserId(int $id): void
    {
        $this->id_user = $id;
    }

    public function getBookId(): int
    {
        return $this->id_book;
    }

    public function setBookId(int $id): void
    {
        $this->id_book = $id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}
