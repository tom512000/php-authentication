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
    // Constante utilisée comme nom de champs du formulaire de connexion pour l'identifiant.
    private const LOGIN_INPUT_NAME = 'login';
    // Constante utilisée comme nom de champs du formulaire de connexion pour le mot de passe.
    private const PASSWORD_INPUT_NAME = 'password';
    // Constante utilisée comme clé de session dans le tableau des données de sessions.
    private const SESSION_KEY = '__UserAuthentication__';
    // Constante utilisée comme clé de la session de l'utilisateur dans le tableau des données de sessions.
    private const SESSION_USER_KEY = 'user';
    // Constante utilisée comme nom de champs du formulaire de déconnexion.
    private const LOGOUT_INPUT_NAME = 'logout';
    // Attribut permettant de stocker soit une instance de User, soit null.
    private ?User $user = null;

    /**
     * Constructeur de la classe UserAuthentication.
     * Récupère et affecte l'utilisateur (User) de la session à $user.
     */
    public function __construct()
    {
        try {
            $utilisateur = $this->getUserFromSession();
            $this->user = $utilisateur;
        } catch (NotLoggedInException) {
        }
    }

    /**
     * Récupère l'utilisateur de la session, vérifie si cet utilisateur est une instance de User et l'affecte à l'attribut $user et à la clé de session.
     * Retourne également l'utilisateur.
     *
     * @return User utilisateur (User) de la session
     *
     * @throws NotLoggedInException
     */
    public function getUserFromSession(): User
    {
        Session::start();
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])) {
            $utilisateur = $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
            if ($utilisateur instanceof User) {
                $this->setUser($utilisateur);

                return $utilisateur;
            }
        }
        throw new NotLoggedInException('Aucun utilisateur dans la session !');
    }

    /**
     * Retourne le formulaire de connexion en HTML.
     *
     * @param string $action     URL qui traite l'envoi du formulaire
     * @param string $submitText valeur du bouton de connexion
     *
     * @return string code HTML du formulaire
     */
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
     * Récupère l'utilisateur dans la BD à partir des données du formulaire.
     *
     * @return user L'instance User de l'utilisateur s'il a été trouvé dans la BD
     *
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
     * Vérifie si la clé de la session de l'utilisateur est déclarée et si elle contient bien une instance de User.
     *
     * @return bool true si la clé de la session de l'utilisateur existe et si elle contient une instance de User, false sinon
     *
     * @throws SessionException
     */
    public function isUserConnected(): bool
    {
        Session::start();
        $res = false;
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])
            && ($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] instanceof User)) {
            $res = true;
        }

        return $res;
    }

    /**
     * Retourne le formulaire de déconnexion en HTML.
     *
     * @param string $action URL qui traite l'envoi du formulaire
     * @param string $text   valeur du label du formulaire
     *
     * @return string code HTML du formulaire
     */
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

    /**
     * Efface la donnée de session associée à l'utilisateur et l'attribut $user si une valeur « 'logout' » de formulaire est reçue.
     */
    public function logoutIfRequested(): void
    {
        try {
            Session::start();
            if (isset($_POST[self::LOGOUT_INPUT_NAME])) {
                unset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]);
                unset($this->user);
            }
        } catch (SessionException) {
        }
    }

    /**
     * Retourne l'instance de User stockée dans l'attribut $user.
     *
     * @return User utilisateur stocké dans l'attribut $user
     *
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
     * Affecte une instance de User à la clé de la session de l'utilisateur et à l'attribut $user.
     *
     * @param User $user instance de User à stocker
     *
     * @throws SessionException
     */
    protected function setUser(User $user): void
    {
        $this->user = $user;
        Session::start();
        $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] = $user;
    }
}
