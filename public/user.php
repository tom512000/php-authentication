<?php

declare(strict_types=1);

use Authentication\Exception\AuthenticationException;
use Authentication\UserAuthentication;
use Html\AppWebPage;
use Html\UserProfile;

$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

$user = $authentication->getUserFromAuth();
$profile = new UserProfile($user);
$p->appendContent($profile->toHtml());

echo $p->toHTML();
