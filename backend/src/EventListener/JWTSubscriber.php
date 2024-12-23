<?php
// src/EventListener/JWTSubscriber.php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTSubscriber
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['username'] = $user->getUsername();
        $payload['email'] = $user->getEmail();

        $event->setData($payload);
    }
}
