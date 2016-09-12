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
        if($user!=null) {
    		$data = $user->getFitbitData();
    		$oauth_client->setRefreshToken($data->refresh_token);
            $oauth_client->setAccessToken($data->access_token);
        }
	}

	public function getUserProfileData() {
        return $this->client->getUserProfileData($this->user->getFitbitUid());
	}

    public function getSubscriptions($activity=null) {
        return $this->client->getSubscriptions($activity);
    } 

    public function deleteSubscriptions($em, $activity=null) {
        $repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\Subscription');

        $fitbit_uid = $this->user->getFitbitUid();
        $subscriptions = $repository->findBy(array('fitbit_uid'=>$fitbit_uid));
        $result = array();

        foreach($subscriptions as $subscription) {
            $sid = $subscription->getSid();
         
            $response = $this->client->deleteSubscriptions($activity, $sid);
         
            if($response['code']!=204) {
                $result[] = array('error'=>true,"message"=>"error deleting subscription with sid %sid",'response'=>$response);
            } else {
                $result[] = $response;
            }
            $em->remove($subscription);
        } 
        $em->flush();
        return $result;
    } 

	public function subscribeToActivity($em,$activity) {
		$fitbit_uid = $this->user->getFitbitUid();
		$repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\Subscription');

        $subscription = $repository->findOneBy(
    		array('fitbit_uid' => $fitbit_uid,
    			  'activity' => $activity)
    	);

        //Already subscribed
        if($subscription && $subscription->getStatus()==1) {
        	return 
        			array('message'=>"Already subscribed", 
        				  'info'=>$subscription->getSubscriptionData()
        				 );
        }

        //Create a db record first - to get sid
        $subscription = new Subscription($fitbit_uid, $activity);
        $em->persist($subscription);
        $em->flush();
        

        //Subscribe and then persist the response
        $response = $this->client->subscribeToActivity($fitbit_uid, $subscription->getSid(), $activity);
        if($response['code']==409) {
            return $response;
        }
        if($response['code']!=200 && $response['code']!=201)  {
        	throw new OAuth2\Exception(sprintf("Unable to subscribe to the activity. Message from server: %s ", var_export($response)));
        }
        $subscription->setSubscriptionData($response);
        $subscription->setStatus(1);

        //persist to database;
        $em->flush();

        return $response;
	}
}