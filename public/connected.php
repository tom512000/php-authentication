<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\AppWebPage;

$authentication = new UserAuthentication();

// Un utilisateur est-il connecté ?
if (!$authentication->isUserConnected()) {
    // Rediriger vers le formulaire de connexion
    exit; // Fin du programme
}

$title = 'Zone membre connecté';
$p = new AppWebPage($title);

$p->appendContent(<<<HTML
        <h1>$title</h1>
        <h2>Page 1</h2>
HTML
);

echo $p->toHTML();
