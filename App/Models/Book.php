<?php

namespace App\Models;

use Framework\Core\Model;
use Framework\DB\Connection;
use Exception;

class Book extends Model
{
    public ?int $id = null;
    public string $name = '';
    public string $author = '';
    public string $genre = '';
    public string $format = '';
    public ?int $year = null;
    public float $price = 0.0;
    public int $number_availible = 0;
    public ?int $pages = null;
    public ?string $text = null;
    public ?string $sample_path = null;
    public ?string $cover_path = null;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->name = $data['name'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->genre = $data['genre'] ?? '';
        $this->format = $data['format'] ?? '';
        $this->year = isset($data['year']) && $data['year'] !== null ? (int)$data['year'] : null;
        $this->price = isset($data['price']) ? (float)$data['price'] : 0.0;
        $this->number_availible = isset($data['number_availible']) ? (int)$data['number_availible'] : 0;
        $this->pages = isset($data['pages']) ? (int)$data['pages'] : null;
        $this->text = $data['text'] ?? null;
        $this->sample_path = $data['sample_path'] ?? null;
        $this->cover_path = $data['cover_path'] ?? null;
    }


    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $v): void { $this->name = $v; }
    public function getAuthor(): string { return $this->author; }
    public function setAuthor(string $v): void { $this->author = $v; }
    public function getGenre(): string { return $this->genre; }
    public function setGenre(string $v): void { $this->genre = $v; }
    public function getFormat(): string { return $this->format; }
    public function setFormat(string $v): void { $this->format = $v; }
    public function getYear(): ?int { return $this->year; }
    public function setYear(?int $v): void { $this->year = $v; }
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $v): void { $this->price = $v; }
    public function getNumberAvailible(): int { return $this->number_availible; }
    public function setNumberAvailible(int $v): void { $this->number_availible = $v; }
    public function getPages(): ?int { return $this->pages; }
    public function setPages(?int $v): void { $this->pages = $v; }
    public function getText(): ?string { return $this->text; }
    public function setText(?string $v): void { $this->text = $v; }
    public function getSamplePath(): ?string { return $this->sample_path; }
    public function setSamplePath(?string $v): void { $this->sample_path = $v; }
    public function getCoverPath(): ?string { return $this->cover_path; }
    public function setCoverPath(?string $v): void { $this->cover_path = $v; }

}

