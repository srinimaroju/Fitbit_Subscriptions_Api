<?php

namespace FitbitOAuth\ClientBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="FitbitOAuth\ClientBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class User implements UserInterface, EquatableInterface, \Serializable
{
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $uid;

    /** @ORM\Column(type="string", name="fitbit_uid") */
    private $fitbit_uid;

    /** @ORM\Column(type="text", name="fitbit_data") */
    private $fitbit_data;
    
    /** @ORM\Column(type="datetime", name="created_at") */
    private $created_at;

    /** @ORM\Column(type="datetime", name="last_updated_at") */
    private $last_updated_at;

    /** @ORM\Column(type="text", name="user_profile_data", nullable=true) */
    private $user_profile_data;

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->last_updated_at = new \DateTime();
    }

     /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->created_at = new \DateTime();
        $this->last_updated_at = new \DateTime();
    }
    
    public function getUsername()
    {
        return $this->uid;
    }

    public function getPassword()
    {
        return null;
    }
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set lastUpdatedAt
     *
     * @param \DateTime $lastUpdatedAt
     *
     * @return User
     */
    public function setLastUpdatedAt($lastUpdatedAt)
    {
        $this->last_updated_at = $lastUpdatedAt;

        return $this;
    }

    /**
     * Get lastUpdatedAt
     *
     * @return \DateTime
     */
    public function getLastUpdatedAt()
    {
        return $this->last_updated_at;
    }

    /**
     * Set fitbitUid
     *
     * @param string $fitbitUid
     *
     * @return User
     */
    public function setFitbitUid($fitbitUid)
    {
        $this->fitbit_uid = $fitbitUid;
        return $this;
    }

    /**
     * Get fitbitUid
     *
     * @return string
     */
    public function getFitbitUid()
    {
        return $this->fitbit_uid;
    }

    /**
     * Set fitbitData
     *
     * @param string $fitbitData
     *
     * @return User
     */
    public function setFitbitData($fitbitData)
    {
        $this->fitbit_data = json_encode($fitbitData);
        return $this;
    }

    /**
     * Get fitbitData
     *
     * @return string
     */
    public function getFitbitData()
    {
        return json_decode($this->fitbit_data);
    }

    /**
     * Set userProfileData
     *
     * @param string $userProfileData
     *
     * @return User
     */
    public function setUserProfileData($user_profile_data)
    {
        $this->user_profile_data = json_encode($user_profile_data);
        return $this;
    }

    /**
     * Get userProfileData
     *
     * @return string
     */
    public function getUserProfileData()
    {
        return json_decode($this->user_profile_data);
    }

    public function getRoles()
    {
        return array('ROLE_USER','ROLE_API');
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->uid,
            $this->fitbit_uid,
        ));
    }

    public function eraseCredentials() {
    
    }

    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    public function isEqualTo(UserInterface $user)
    {
        if ($this->getFitbitUid() !== $user->getFitbitUid()) {
            return false;
        }

        return true;
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->fitbit_uid,
        ) = unserialize($serialized);
    }
}
