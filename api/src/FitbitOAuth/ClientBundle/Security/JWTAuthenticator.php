<?php


namespace FitbitOAuth\ClientBundle\Security;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

use FitbitOAuth\ClientBundle\Service\TokenService;

class JWTAuthenticator implements SimplePreAuthenticatorInterface,AuthenticationFailureHandlerInterface
{
      protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    public function createToken(Request $request, $providerKey)
    {
        // look for an authorization header
        //$authorizationHeader = $request->headers->get('Authorization');
        $jwt = $request->query->get('jwt');
        
        if ($jwt == null) {
            return new PreAuthenticatedToken(
                'anon.',
                null,
                $providerKey
            );
        }

        // decode and validate the JWT
        try {
            $token = $this->tokenService->decodeJWT($jwt);
        } catch(\UnexpectedValueException $ex) {
            throw new BadCredentialsException('Invalid token');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $token,
            $providerKey
        );
    }

    /**
     * @param TokenInterface           $token
     * @param JWTUserProviderInterface $userProvider
     * @param                          $providerKey
     *
     * @return PreAuthenticatedToken
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
            //print_r(get_class($userProvider)) ; exit;
        // The user provider should implement JWTUserProviderInterface
        if (!$userProvider instanceof UserProviderInterface) {
            throw new InvalidArgumentException('Argument must implement interface UserProviderInterface');
        }

        if ($token->getCredentials() === null) {
            throw new AuthenticationException('Invalid JWT.');
        } else {
            $jwt = $token->getCredentials();
            $uid = $jwt->user_id;
            //print_r($jwt); exit;
            // Get the user for the injected UserProvider
            $user = $userProvider->loadUserbyUserName($uid);

            if (!$user) {
                throw new AuthenticationException(sprintf('Invalid JWT.'));
            }
        }

        return new PreAuthenticatedToken(
            $user,
            $token,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("Authentication Failed: {$exception->getMessage()}", 403);
    }

}
