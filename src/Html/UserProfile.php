<?php

declare(strict_types=1);

namespace Html;

use Entity\User;

class UserProfile
{
    /** @var User Utilisateur */
    private User $user;

    use StringEscaper;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function toHtml() : string
    {
        return <<<HTML
        <p>Nom<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getFirstName())}</p>
        <p>Prénom<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getLastName())}</p>
        <p>Login<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getLogin())}[{$this->user->getId()}]</p>
        <p>Téléphone<br>&nbsp;&nbsp;&nbsp;&nbsp;{$this->escapeString($this->user->getPhone())}</p>
    HTML;
    }
}
