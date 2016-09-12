<?php
namespace FitbitOAuth\ClientBundle\Service;

use OAuth2;
use FitbitOAuth\ClientBundle\Entity\User;
use FitbitOAuth\ClientBundle\Entity\Subscription;
use FitbitOAuth\ClientBundle\Service\FitbitOAuth2Client;
use FitbitOAuth\ClientBundle\Store\FitbitJWT;


class FitbitDataHandler {
	protected $em;
	protected $user;
	protected $client;
	protected $access_token;
	protected $refresh_token;

	public function __construct(User $user, FitbitOAuth2Client $oauth_client) {
		$this->user = $user;
		$this->client = $oauth_client;

		$data = $user->getFitbitData();
		$oauth_client->setRefreshToken($data->refresh_token);
        $oauth_client->setAccessToken($data->access_token);
	}

	public function getUserProfileData() {
        return $this->client->getUserProfileData($this->user->getFitbitUid());
	}

	public function subscribeToSleep($em) {
		$fitbit_uid = $this->user->getFitbitUid();
		$repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\Subscription');

        $subscription = $repository->findOneBy(
    		array('fitbit_uid' => $fitbit_uid)
    	);

        //Already subscribed
        if($subscription && $subscription->getStatus()==1) {
        	return 
        			array('message'=>"Already subscribed", 
        				  'info'=>$subscription->getSubscriptionData()
        				 );
        }

        $subscription = new Subscription($fitbit_uid);
        $em->persist($subscription);
        $em->flush();
        
        $response = $this->client->subscribetoSleep($fitbit_uid, $subscription->getSid());
        $subscription->setSubscriptionData($response);
        $subscription->setStatus(1);

        //persist to database;
        $em->flush();

        return $response;
	}
}