<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\UserAvatar;

try {
    if (isset($_GET['userId'])) {
        $userId = $_GET['userId'];
    }
    $userAvatar = UserAvatar::findById($userId);
    $avatar = $userAvatar->getAvatar();
} catch (EntityNotFoundException) {
    $avatar = file_get_contents('./img/default_avatar.png');
}

header('Content-Type: image/png');
exit;
