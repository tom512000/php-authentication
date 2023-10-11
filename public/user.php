<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Html\AppWebPage;
use Html\UserProfile;

$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

try {
    $user = $authentication->getUser();
    $profile = new UserProfile($user);
    $p->appendContent($profile->toHtml());
} catch (NotLoggedInException) {
    header("Location: ./form.php");
    exit;
}

echo $p->toHTML();
