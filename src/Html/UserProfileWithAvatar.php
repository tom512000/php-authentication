<?php

declare(strict_types=1);

namespace Html;

use Entity\User;

class UserProfileWithAvatar extends UserProfile
{
    public const AVATAR_INPUT_NAME = 'avatar';
    private string $formAction;

    public function __construct(User $user, string $formAction)
    {
        parent::__construct($user);
        $this->formAction = $formAction;
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
                    <input style="width: 242px" type="submit" name="$inputName" value="Mettre à jour"/>
                </form>
            HTML;
        return $html;
    }
}
