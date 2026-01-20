<?php

namespace App\Models;

use Framework\Core\IIdentity;
use Framework\Core\Model;
use Framework\DB\Connection;
use Exception;

class User extends Model implements IIdentity
{
    public ?int $id = null;
    public string $name = '';
    public string $surname = '';

    public ?string $phone = null;
    public ?string $city = null;
    public ?string $PSC = null; // postal code
    public ?string $street = null;
    public string $e_mail = '';
    public string $password = '';
    public string $role = 'U'; // default role: U (user)

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->name = $data['name'] ?? '';
        $this->surname = $data['surname'] ?? '';
        $this->phone = $data['phone'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->PSC = $data['PSC'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->e_mail = $data['e_mail'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'U';
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPSC(): ?string
    {
        return $this->PSC;
    }

    public function setPSC(?string $PSC): void
    {
        $this->PSC = $PSC;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getEmail(): string
    {
        return $this->e_mail;
    }

    public function setEmail(string $email): void
    {
        $this->e_mail = $email;
    }

    /**
     * Returns hashed password (do not expose raw password)
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set password (raw password will be hashed automatically)
     */
    public function setPassword(string $rawPassword): void
    {
        $this->password = password_hash($rawPassword, PASSWORD_DEFAULT);
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    // --- persistence helpers ---

    /**
     * Find a user by email address.
     * @param string $email
     * @return self|null
     */
    public static function findByEmail(string $email): ?self
    {
        $users = self::getAll("e_mail = ?", [$email], null, 1);

        return $users[0] ?? null;
    }


    public function isAdmin(): bool
    {
        return $this->role === 'A';
    }

    /**
     * Verify a raw password against the stored hashed password.
     * @param string $raw
     * @return bool
     */
    public function verifyPassword(string $raw): bool
    {
        if (empty($this->password)) return false;
        return password_verify($raw, $this->password);
    }
}
