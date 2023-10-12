<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Html\AppWebPage;
use Html\UserProfileWithAvatar;

$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

try {
    $user = $authentication->getUser();
    $profile = new UserProfileWithAvatar($user, $_SERVER['PHP_SELF']);
    $p->appendContent($profile->toHtml());
} catch (NotLoggedInException) {
    header('Location: ./form.php');
    exit;
}

echo $p->toHTML();
