<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class UserAvatar
{
    private int $id;
    private ?string $avatar;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Recherche dans la base de données l'utilisateur dont l'identifiant est passé en paramètre.
     *
     * @param int $userId
     * @return UserAvatar Avatar trouvé dans la base de données.
     * @throws EntityNotFoundException Si l'avatar n'a pas été trouvé.
     */
    public static function findById(int $userId): UserAvatar
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            SELECT id, avatar
            FROM user
            WHERE id = :id
        SQL
        );

        if (!$stmt->execute([':id' => $userId])) {
            throw new EntityNotFoundException("L'utilisateur n'existe pas !");
        }

        if (($utilisateur = $stmt->fetchObject(UserAvatar::class)) === false) {
            throw new EntityNotFoundException("Impossible de charger l'utilisateur en mémoire !");
        }

        return $utilisateur;
    }
}
