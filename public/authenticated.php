<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\AppWebPage;
use Html\UserProfile;
use Authentication\Exception\NotLoggedInException;

$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

if (!$authentication->isUserConnected()) {
    header("Location: ./form.php");
    exit;
}

$title = 'Zone membre utilisateur';
$p = new AppWebPage($title);

$p->appendContent(
    <<<HTML
        <h1>$title</h1>
        <h2>{$authentication->getUser()->getFirstName()}</h2>
HTML
);

echo $p->toHTML();
