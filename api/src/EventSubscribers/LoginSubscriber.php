<?php

namespace App\EventSubscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();

        if(!$user->isStatus() ) {
            $event->setData([
                'status' => 0,
                'message' => 'Votre compte est n\'est pas actif, veuillez vérifier votre boite mail'
            ]);
            $event->getResponse()->setStatusCode(401);
        }

    }
}
