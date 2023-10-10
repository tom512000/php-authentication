<?php

declare(strict_types=1);

namespace Authentication;

use Authentication\Exception\NotLoggedInException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Service\Exception\SessionException;
use Service\Session;

class UserAuthentication
{
    private const LOGIN_INPUT_NAME = 'login';
    private const PASSWORD_INPUT_NAME = 'password';
    private const SESSION_KEY = '__UserAuthentication__';
    private const SESSION_USER_KEY = 'user';
    private const LOGOUT_INPUT_NAME = 'logout';

    private ?User $user = null;

    public function __construct()
    {
        try {
            $utilisateur = $this->getUserFromSession();
            $this->user = $utilisateur;
        } catch (NotLoggedInException) {
        }
    }

    /**
     * @throws NotLoggedInException
     */
    public function getUserFromSession(): User
    {
        if (isset($_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY])
            && ($_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY] instanceof User)) {
            return $_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY];
        } else {
            throw new NotLoggedInException('Aucun utilisateur dans la session !');
        }
    }

    public function loginForm(string $action, string $submitText = 'OK'): string
    {
        $login = $this::LOGIN_INPUT_NAME;
        $pass = $this::PASSWORD_INPUT_NAME;

        return <<<HTML
            <form action="$action" method="post">
                <input type="text" name="login" placeholder="$login" required>
                <input type="password" name="pass" placeholder="$pass" required>
                <input type="submit" value="$submitText">
            </form>
        HTML;
    }

    /**
     * Récupére l'utilisateur dans la BD à partir des données du formulaire.
     *
     * @return User L'instance User de l'utilisateur s'il a été trouvé dans la BD
     * @throws SessionException
     */
    public function getUserFromAuth(): User
    {
        try {
            $utilisateur = User::findByCredentials($_POST['login'], $_POST['pass']);
            $this->setUser($utilisateur);

            return $utilisateur;
        } catch (EntityNotFoundException) {
        }
    }

    /**
     * @throws SessionException
     */
    public function isUserConnected(): bool
    {
        Session::start();
        $res = false;
        if (isset($_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY])
            && ($_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY] instanceof User)) {
            $res = true;
        }

        return $res;
    }

    public function logoutForm(string $action, string $text): string
    {
        $logout = $this::LOGOUT_INPUT_NAME;

        return <<<HTML
            <form action="$action" method="post">
                <label for="submit">$text</label><br>
                <input type="submit" name="$logout" value="OK">
            </form>
        HTML;
    }

    public function logoutIfRequested(): void
    {
        try {
            Session::start();
            if (isset($_POST[UserAuthentication::LOGOUT_INPUT_NAME])) {
                unset($_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY]);
                unset($this->user);
            }
        } catch (SessionException) {
        }
    }

    /**
     * @throws NotLoggedInException
     */
    public function getUser(): User
    {
        if (!isset($this->user)) {
            throw new NotLoggedInException('Aucun utilisateur dans la session !');
        }

        return $this->user;
    }

    /**
     * @throws SessionException
     */
    protected function setUser(User $user): void
    {
        $this->user = $user;
        Session::start();
        $_SESSION[UserAuthentication::SESSION_KEY][UserAuthentication::SESSION_USER_KEY] = $user;
    }
}
