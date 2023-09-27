<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class User
{
    private int $id;
    private string $lastName;
    private string $firstName;
    private string $login;
    private string $phone;

    /**
     * @param string $login    Login de l'utilisateur
     * @param string $password Mot de passe en clair
     *
     * @throws EntityNotFoundException
     */
    public static function findByCredentials(string $login, string $password) : User
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            SELECT id, lastName, firstName, login, phone
            FROM user
            WHERE login = :login
            AND sha512pass = SHA2(:password, 512)
        SQL
        );
        if (!$stmt->execute([':login' => $login, ':password' => $password])) {
            throw new EntityNotFoundException("L'utilisateur n'existe pas !");
        }

        if (($utilisateur = $stmt->fetchObject(User::class)) === false) {
            throw new EntityNotFoundException("Impossible de charger l'utilisateur en mémoire !");
        }

        return $utilisateur;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
