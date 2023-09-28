<?php

declare(strict_types=1);

use Authentication\Exception\AuthenticationException;
use Authentication\UserAuthentication;
use Html\AppWebPage;

$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

try {
    // Tentative de connexion
    $user = $authentication->getUserFromAuth();
    // Si connexion réussie, affichage du profil
    $p->appendContent(<<<HTML
<div>Bonjour {$user->getFirstName()}</div>
HTML
    );
} catch (AuthenticationException $e) {
    // Récupération de l'exception si connexion échouée
    $p->appendContent("Échec d'authentification&nbsp;: {$e->getMessage()}");
} catch (Exception $e) {
    $p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}");
}

// Envoi du code HTML au navigateur du client
echo $p->toHTML();
