<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;

class User
{
    private int $id;
    private string $lastName;
    private string $firstName;
    private string $login;
    private string $phone;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    public function findByCredentials(string $login, string $password) : User
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            SELECT login, password
            FROM user
        SQL
        );
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, User::class);
    }
}
