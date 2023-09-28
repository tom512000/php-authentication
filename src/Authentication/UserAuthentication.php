<?php

declare(strict_types=1);

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;

class UserAuthentication
{
    public const LOGIN_INPUT_NAME = 'login';
    public const PASSWORD_INPUT_NAME = 'password';

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
     * @throws EntityNotFoundException
     */
    public function getUserFromAuth(): User
    {
        if (!User::findByCredentials($_POST['login'], $_POST['pass'])) {
            throw new AuthenticationException("L'authentification est impossible !");
        }
        return User::findByCredentials($_POST['login'], $_POST['pass']);
    }
}
