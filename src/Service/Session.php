<?php

declare(strict_types=1);

namespace Service;

use Service\Exception\SessionException;

class Session
{
    /**
     * @throws SessionException
     */
    public static function start()
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            return PHP_SESSION_ACTIVE;
        } elseif (PHP_SESSION_NONE === session_status()) {
            if (headers_sent()) {
                throw new SessionException('Impossible de modifier les entêtes HTTP.');
            } else {
                // session_start est executée, elle renvoie un booléen pour savoir si la session est lancée ou pas.
                // Si il y a un problème en démarrant la session :
                if (!session_start()) {
                    // On lève une exception.
                    throw new SessionException('Démarrage de la session impossible.');
                }
            }
        } elseif (PHP_SESSION_DISABLED === session_status()) {
            throw new SessionException('La session est désactivée.');
        } else {
            throw new SessionException('Démarrage impossible.');
        }
    }
}
