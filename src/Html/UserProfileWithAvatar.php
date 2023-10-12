<?php

declare(strict_types=1);

namespace Html;

use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Entity\UserAvatar;
use Html\Helper\Dumper;

class UserProfileWithAvatar extends UserProfile
{
    public const AVATAR_INPUT_NAME = 'avatar';
    private string $formAction;

    public function __construct(User $user, string $formAction)
    {
        parent::__construct($user);
        $this->formAction = $formAction;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateAvatar(): bool
    {
        // echo Dumper::dump($_FILES);
        if ((isset($_FILES[self::AVATAR_INPUT_NAME]))
            && (UPLOAD_ERR_OK === $_FILES[self::AVATAR_INPUT_NAME]['error'])
            && ($_FILES[self::AVATAR_INPUT_NAME]['size'] > 0)
            && is_uploaded_file($_FILES[self::AVATAR_INPUT_NAME]['tmp_name'])) {
            $userAvatar = UserAvatar::findById($this->getUser()->getId());
            $userAvatar->setAvatar($_FILES[self::AVATAR_INPUT_NAME]);
            $userAvatar->save();
            unlink($_FILES[self::AVATAR_INPUT_NAME]);

            return true;
        } else {
            return false;
        }
    }

    public function toHtml(): string
    {
        $html = parent::toHtml();
        $inputName = self::AVATAR_INPUT_NAME;
        $html .=
            <<<HTML
                <p>Avatar</p><br>
                <img src="avatar.php?userId={$this->getUser()->getId()}" alt="Photo de profil"/>

                <form action="$this->formAction" method="post">
                    <label for="$inputName">Changer :</label>
                    <input type="file" name="$inputName" accept="image/png"/><br>
                    <input type="submit" name="$inputName" value="Mettre à jour" style="width: 242px"/>
                </form>
            HTML;

        return $html;
    }
}
