<?php

namespace TobiasOlry\TalklyBundle\Security\Firewall;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use TobiasOlry\TalklyBundle\Security\Authentication\NtlmToken;

class NtlmListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        $debug,
        $domain,
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        LoggerInterface $logger
    ) {
        $this->debug                 = (bool) $debug;
        $this->domain                = $domain;
        $this->tokenStorage          = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->logger                = $logger;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $token = new NtlmToken();

        try {
            if ($this->debug) {
                $token->setUser('mmustermann');
            } else {
                $token->setUser($this->getRemoteUser($request));
            }

            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            $this->logger->error((string) $failed);
        }
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
            throw new AuthenticationException('No remote user found.');
        }

        if (strpos($username, $this->domain . '\\') !== 0) {
            throw new AuthenticationException(
                sprintf(
                    'Domain "%s" is not matching with remote user "%s".',
                    $this->domain,
                    $username
                )
            );
        }

        return str_replace($this->domain . '\\', '', $username);
    }
}