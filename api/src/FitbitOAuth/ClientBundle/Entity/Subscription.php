<?php

namespace FitbitOAuth\ClientBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fitbit_subscriptions")
 * @ORM\HasLifecycleCallbacks()
 */

class Subscription 
{
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $sid;

    /** @ORM\Column(type="string", name="fitbit_uid") */
    private $fitbit_uid;

    /** @ORM\Column(type="text", name="subscription_data", nullable=true) */
    private $subscription_data;
    
    /** @ORM\Column(type="datetime", name="created_at") */
    private $created_at;

    /** @ORM\Column(type="datetime", name="last_updated_at") */
    private $last_updated_at;

    /** @ORM\Column(type="integer", name="status", nullable=true) */
    private $status;
  

    public function __construct($fitbit_uid) {
        $this->fitbit_uid = $fitbit_uid;
        $this->status = 0;
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



   
    public function serialize()
    {
        return serialize(array(
            $this->sid,
            $this->fitbit_uid,
            $this->subscription_data,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->fitbit_uid,
            $this->subscription_data,
        ) = unserialize($serialized);
    }

    /**
     * Get sid
     *
     * @return integer
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set fitbitUid
     *
     * @param string $fitbitUid
     *
     * @return Subscription
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
     * Set subscriptionData
     *
     * @param string $subscriptionData
     *
     * @return Subscription
     */
    public function setSubscriptionData($subscriptionData)
    {
        $this->subscription_data = json_encode($subscriptionData);
        return $this;
    }

    /**
     * Get subscriptionData
     *
     * @return string
     */
    public function getSubscriptionData()
    {
        return json_decode($this->subscription_data);
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Subscription
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
}

