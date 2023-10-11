<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\UserAvatar;

try {
    $userId = (int)$_GET['userId'];
    $userAvatar = UserAvatar::findById($userId);
    $avatar = $userAvatar->getAvatar();
} catch (EntityNotFoundException) {
    $avatar = file_get_contents('img/default_avatar.png', true);
}

header('Content-Type: image/png');
echo $avatar;
exit;
