<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    
    /**
     * Date d'expiration JWT Token (1jour)
     * @param JWTCreatedEvent $event
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $expiration = new \DateTime('+1 day');
        $expiration->setTime(2, 0, 0);

        $payload = $event->getData();
        $payload['exp'] = $expiration->getTimestamp();

        $event->setData($payload);
    }

}