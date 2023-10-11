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
        <h2><a href="user.php">{$authentication->getUser()->getFirstName()}</a></h2>
HTML
);

echo $p->toHTML();
