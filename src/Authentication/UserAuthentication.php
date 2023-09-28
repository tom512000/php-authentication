<?php

declare(strict_types=1);

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Html\Helper\Dumper;
use Service\Exception\SessionException;
use Service\Session;

class UserAuthentication
{
    private const LOGIN_INPUT_NAME = 'login';
    private const PASSWORD_INPUT_NAME = 'password';
    private const SESSION_KEY = '__UserAuthentication__';
    private const SESSION_USER_KEY = 'user';
    private ?User $user = null;

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
     * @throws AuthenticationException
     * @throws EntityNotFoundException|SessionException
     */
    public function getUserFromAuth(): User
    {
        if (!User::findByCredentials($_POST['login'], $_POST['pass'])) {
            throw new AuthenticationException("L'authentification est impossible !");
        }
        $user = User::findByCredentials($_POST['login'], $_POST['pass']);
        $this->setUser($user);

        return $user;
    }

    /**
     * @throws SessionException
     */
    protected function setUser(User $user): void
    {
        $this->user = $user;
        Session::start();
        $_SESSION[UserAuthentication::SESSION_USER_KEY] = $user;
    }

    /**
     * @throws SessionException
     */
    public function isUserConnected(): bool
    {
        Session::start();
        $res = false;
        if (Dumper::dump($_SESSION[UserAuthentication::SESSION_USER_KEY] instanceof User)) {
            $res = true;
        } else {
            header('./form.php');
        }

        return $res;
    }
}
