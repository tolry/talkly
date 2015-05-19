<?php

namespace TobiasOlry\TalklyBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use TobiasOlry\TalklyBundle\Security\Authentication\NtlmToken;

class NtlmListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;
    protected $debug;

    public function __construct(
        $debug,
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager
    ) {
        $this->debug                 = (bool) $debug;
        $this->tokenStorage          = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $token = new NtlmToken();

        if ($this->debug) {
            $token->setUser('mmustermann');
        } else {
            $token->setUser($this->getRemoteUser($request));
        }

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);

    }

    /**
     * @param $request
     *
     * @return mixed|void
     */
    protected function getRemoteUser($request)
    {
        $username = $request->server->get('REMOTE_USER');

        if (empty($username)) {
            return;
        }

        if (strpos($username, $this->domain . '\\') !== 0) {
            return;
        }

        return str_replace($this->domain . '\\', '', $username);
    }
}