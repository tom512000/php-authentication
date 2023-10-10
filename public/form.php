<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Html\AppWebPage;
use Html\UserProfile;

// Création de l'authentification
$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

// Production du formulaire de connexion
$p->appendCSS(
    <<<CSS
    form input {
        width : auto ;
    }
CSS
);

$authentication->logoutIfRequested();

try {
    $utilisateur = $authentication->getUser();
    $profil_utilisateur = new UserProfile($utilisateur);
    $p->appendContent($profil_utilisateur->toHtml());
    // Formulaire de déconnexion
    $form = $authentication->logoutForm('form.php', 'Déconnexion');
    $p->appendContent(
        <<<HTML
        {$form}
HTML
    );
} catch (NotLoggedInException $e) {
    // Formulaire de connexion
    $form = $authentication->loginForm('auth.php');
    $p->appendContent(
        <<<HTML
        {$form}
        <p>Pour faire un test : essai/toto</p>
HTML
    );
}

/*
if ($authentication->getUserFromSession()) {
    // Formulaire de déconnexion
    $form = $authentication->logoutForm('form.php', 'Déconnexion');
    $p->appendContent(
        <<<HTML
        {$form}
HTML
    );
} else {
    // Formulaire de connexion
    $form = $authentication->loginForm('auth.php');
    $p->appendContent(
        <<<HTML
        {$form}
        <p>Pour faire un test : essai/toto</p>
HTML
    );
}*/

/*if ($authentication->isUserConnected()) {
    $form = $authentication->logoutForm('form.php', 'Déconnexion');
    $p->appendContent(
        <<<HTML
        {$form}
HTML
    );
} else {
    $form = $authentication->loginForm('auth.php');
    $p->appendContent(
        <<<HTML
        {$form}
        <p>Pour faire un test : essai/toto</p>
HTML
    );
}*/

echo $p->toHTML();
