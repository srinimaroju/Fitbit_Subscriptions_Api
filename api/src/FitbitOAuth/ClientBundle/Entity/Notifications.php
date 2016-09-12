<?php

namespace FitbitOAuth\ClientBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fitbit_subscriptions_notifications")
 * @ORM\HasLifecycleCallbacks()
 */

class Notifications
{
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $notification_id;

    /** @ORM\Column(type="string", name="fitbit_uid") */
    private $fitbit_uid;

    /** @ORM\Column(type="text", name="notification_data", nullable=true) */
    private $notification_data;
    
    /** @ORM\Column(type="datetime", name="created_at") */
    private $received_at;
    

    /**
     * Get notificationId
     *
     * @return integer
     */
    public function getNotificationId()
    {
        return $this->notification_id;
    }

    /**
     * Set fitbitUid
     *
     * @param string $fitbitUid
     *
     * @return Notifications
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
     * Set notificationData
     *
     * @param string $notificationData
     *
     * @return Notifications
     */
    public function setNotificationData($notificationData)
    {
        $this->notification_data = $notificationData;

        return $this;
    }

    /**
     * Get notificationData
     *
     * @return string
     */
    public function getNotificationData()
    {
        return $this->notification_data;
    }

    /**
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     *
     * @return Notifications
     */
    public function setReceivedAt($receivedAt)
    {
        $this->received_at = $receivedAt;

        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime
     */
    public function getReceivedAt()
    {
        return $this->received_at;
    }
}
