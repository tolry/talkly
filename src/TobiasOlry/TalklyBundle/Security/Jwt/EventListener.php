<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Security\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class EventListener
{
    private $user;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()
            ? $tokenStorage->getToken()->getUser()
            : null;
    }

    public function onJwtCreated(JWTCreatedEvent $event)
    {
        if (! $this->user) {
            return;
        }

        $payload = $event->getData();
        $payload['user'] = [
            'id' => $this->user->getId(),
            'username' => $this->user->getUsername(),
            'name' => $this->user->getName(),
        ];

        $event->setData($payload);
    }
}
