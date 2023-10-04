<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\AppWebPage;

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
if (isset($authentication->getUser())) {
    $form = $authentication->logoutForm("form.php", "Déconnexion");
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
}


echo $p->toHTML();
