<?php

namespace App\Listeners;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        /**
         * @var $user User
         */
        $user = $event->getUser();
        $company = $user->company;

        $event->setData(
            array_merge(
                $event->getData(),
                ['user_id' => $user->getId()],
                ['company_name' => $company->name],
            )
        );
    }
}
