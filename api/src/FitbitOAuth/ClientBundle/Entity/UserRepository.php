<?php
// src/AppBundle/Entity/UserRepository.php
namespace FitbitOAuth\ClientBundle\Entity;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /*
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    } */
    public function loadUserByFitBitUid($fitbit_uid) {
    	return $this->createQueryBuilder('u')
            ->where('u.fitbit_uid = :fitbit_uid')
            ->setParameter('fitbit_uid', $fitbit_uid)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByUsername($uid) {
        return $this->createQueryBuilder('u')
            ->where('u.uid = :uid')
            ->setParameter('uid', $uid)
            ->getQuery()
            ->getOneOrNullResult();
    }
}