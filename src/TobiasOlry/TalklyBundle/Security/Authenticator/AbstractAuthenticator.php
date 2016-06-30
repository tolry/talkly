<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTManager
     */
    private $manager;

    /**
     * @param JWTManager $manager
     */
    public function __construct(JWTManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return null|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() !== '/login_check') {
            return null;
        }

        return json_decode($request->getContent(), true);
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(null, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();
        $jwt  = $this->manager->create($user);

        return new JsonResponse([
            'token' => $jwt
        ]);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}