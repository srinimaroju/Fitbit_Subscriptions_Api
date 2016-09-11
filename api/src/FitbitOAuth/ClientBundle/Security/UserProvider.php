<?php
/**
 * Created by PhpStorm.
 * User: german
 * Date: 1/22/15
 * Time: 9:30 PM
 */

namespace FitbitOAuth\ClientBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 


class UserProvider implements  UserProviderInterface, UserLoaderInterface
{
   
    public function loadUserByJWT($jwt) {
        $user_id = $jwt->user_id;
        $em = $this->getDoctrine()->getManager();
        $user = $em
                  ->getRepository('FitbitOAuth\\ClientBundle\\Entity\\User')
                  ->loadUserByFitBitUid($fitbit_uid);

        return new User(
            $user, array('ROLE_API'));
    }
    
    public function loadUserbyFitBitUid($fitbit_uid) {
        return null;
    }

    public function loadUserByUsername($username)
    {
        throw new NotImplementedException('method not implemented');
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\Security\A0User';
    }
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        /*
        if ($this->username !== $user->getUsername()) {
            return false;
        }
        */
        return true;
    }
}
