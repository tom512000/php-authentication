<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class UserAvatar
{
    // Attribut permettant de stocker l'id de l'avatar.
    private int $id;
    // Attribut permettant de stocker soit un avatar (chaine de caractères), soit null.
    private ?string $avatar;

    /**
     * Retourne l'attribut id de l'instance de UserAvatar.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Retourne l'attribut avatar de l'instance de UserAvatar.
     *
     * @return string|null soit avatar (chaine de caractères), soit null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Retoune l'attribut avatar de l'instance de UserAvatar.
     *
     * @param string|null $avatar Nouvel avatar
     *
     * @return UserAvatar Instance après changement de l'attribut avatar
     */
    public function setAvatar(?string $avatar): UserAvatar
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function save(): UserAvatar
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            UPDATE user
            SET avatar = :avatar
            WHERE id = :id
        SQL
        );

        if (!$stmt->execute([':avatar' => $this->avatar, ':id' => $this->id])) {
            throw new EntityNotFoundException("L'utilisateur n'existe pas !");
        }

        return $this;
    }

    /**
     * Recherche dans la base de données l'utilisateur dont l'identifiant est passé en paramètre.
     *
     * @return UserAvatar avatar trouvé dans la base de données
     *
     * @throws EntityNotFoundException si l'avatar n'a pas été trouvé
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
