<?php

declare(strict_types=1);

namespace Html;

class UserProfileWithAvatar extends UserProfile
{
    public function toHtml(): string
    {
        $html = parent::toHtml();
        $html .=
            <<<HTML
                <p>Avatar</p><br>
                <img src="avatar.php?userId={$this->getUser()->getId()}" alt="Photo de profil"/>
            HTML;
        return $html;
    }
}
