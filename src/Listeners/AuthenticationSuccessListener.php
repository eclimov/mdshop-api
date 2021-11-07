<?php

namespace App\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $event->setData(
            array_merge(
                $event->getData(),
                ['user_id' => $event->getUser()->getId()]
            )
        );
    }
}
