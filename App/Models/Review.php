<?php

namespace App\Models;

use Framework\Core\Model;

class Review extends Model
{
    protected static ?string $tableName = 'reviews';

    public ?int $id = null;
    public int $id_book;
    public int $id_user;
    public int $rating;
    public ?string $review = null;
    public string $date;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->id_book = isset($data['id_book']) ? (int)$data['id_book'] : 0;
        $this->id_user = isset($data['id_user']) ? (int)$data['id_user'] : 0;
        $this->rating = isset($data['rating']) ? (int)$data['rating'] : 0;
        $this->review = $data['review'] ?? null;
        $this->date = $data['date'] ?? date('Y-m-d');
    }

    // ===== GETTERY / SETTERY =====

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdBook(): int
    {
        return $this->id_book;
    }

    public function setIdBook(int $v): void
    {
        $this->id_book = $v;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function setIdUser(int $v): void
    {
        $this->id_user = $v;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $v): void
    {
        $this->rating = $v;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $v): void
    {
        $this->review = $v;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $v): void
    {
        $this->date = $v;
    }
}
