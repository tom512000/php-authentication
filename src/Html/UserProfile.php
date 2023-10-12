<?php

declare(strict_types=1);

namespace Html;

use Entity\User;

class UserProfile
{
    use StringEscaper;
    // Attribut permettant de stocker une instance de User.
    private User $user;

    /**
     * Constructeur de la classe UserProfile.
     * Récupère et affecte l'utilisateur (User) de la session à $user.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Retourne l'instance de User stockée dans l'attribut $user.
     *
     * @return User utilisateur stocké dans l'attribut $user
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Retourne les informations de l'utilisateur en HTML.
     */
    public function toHtml(): string
    {
        return <<<HTML
        <p>Nom<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getFirstName())}</p>
        <p>Prénom<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getLastName())}</p>
        <p>Login<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getLogin())}[{$this->user->getId()}]</p>
        <p>Téléphone<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getPhone())}</p>
    HTML;
    }
}
