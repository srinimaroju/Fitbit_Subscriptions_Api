<?php

namespace FitbitOAuth\ClientBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class User implements UserInterface, \Serializable
{
	 /**
     * @ORM\Column(type="string")
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

    /** @ORM\Column(type="text", name="facebook_data") */
    private $facebook_data;

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
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }
    
    public function getUsername()
    {
        return $this->jwt["email"];
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
        $this->fitbit_data = $fitbitData;

        return $this;
    }

    /**
     * Get fitbitData
     *
     * @return string
     */
    public function getFitbitData()
    {
        return $this->fitbit_data;
    }

    /**
     * Set facebookData
     *
     * @param string $facebookData
     *
     * @return User
     */
    public function setFacebookData($facebookData)
    {
        $this->facebook_data = $facebookData;

        return $this;
    }

    /**
     * Get facebookData
     *
     * @return string
     */
    public function getFacebookData()
    {
        return $this->facebook_data;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
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

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->fitbit_uid,
        ) = unserialize($serialized);
    }
}
