<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();
        $salt = explode(' ', $user->getSalt());
        $event->setData([
            'userId' => $salt[0],
            'username' => $salt[1],
            'role' => $user->getRoles(),
            'payload' => $event->getData(),
        ]);
    }
}