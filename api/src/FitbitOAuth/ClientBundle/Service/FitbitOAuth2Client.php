<?php

namespace FitbitOAuth\ClientBundle\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use OAuth2;
use ArrayObject;

class FitbitOAuth2Client  
{
    protected $client;
    protected $authEndpoint;
    protected $tokenEndpoint;
    protected $redirectUrl;
    protected $grant;
    protected $params;
    protected $user_api;
    protected $refresh_token;


    public function __construct(OAuth2\Client $client, $fitbit_api, $grant, $params)
    {
       // print_r($fitbit_api_urls); exit;    
        $fitbit_api_urls = new ArrayObject($fitbit_api, ArrayObject::ARRAY_AS_PROPS);
       // $fitbit_api_urls = $fitbit_apis[0];
        
        //print_r($fitbit_api_urls); exit;  
        $this->client = $client;
        $this->authEndpoint  = $fitbit_api_urls->oauth_authorize_endpoint;
        $this->tokenEndpoint = $fitbit_api_urls->oauth_token_endpoint;
        $this->redirectUrl   = $fitbit_api_urls->oauth_redirect_uri;
        $this->user_api      = $fitbit_api_urls->user_api;
        
        $this->grant = $grant;
        $this->params = $params;
    }
    
    public function getAuthenticationUrl() {
        $params = array("scope" => $this->params);
        return $this->client->getAuthenticationUrl($this->authEndpoint, $this->redirectUrl,  $params);
    }

    public function getAccessToken($code = null)
    {
        if ($code !== null) {
           $params = array('code' => $code  , 'redirect_uri' => $this->redirectUrl);
        }
        $response = $this->client->getAccessToken($this->tokenEndpoint, $this->grant, $params);
        if(isset($response['result']) && isset($response['result']['access_token'])) {
            $accessToken = $response['result']['access_token'];
            $this->client->setAccessToken($accessToken);
            return $response;
        }
        throw new OAuth2\Exception(sprintf('Unable to obtain Access Token. Response from the Server: %s ', var_export($response)));
    }
    
    public function getRefreshedAccessToken($refreshToken) {
        $params['refresh_token'] = $refreshToken;
        $response = $this->client->getAccessToken($this->tokenEndpoint, OAuth2\Client::GRANT_TYPE_REFRESH_TOKEN, $params);
        if(isset($response['result']) && isset($response['result']['access_token'])) {
            $accessToken = $response['result']['access_token'];
            $this->client->setAccessToken($accessToken);
            return $response;
        } else {
            return null;
        }
    }

    public function setAccessToken($token){
        $this->client->setAccessToken($token);
    }

    public function setRefreshToken($token){
        $this->refresh_token= $token;
    }

    public function getUserProfileData($uid) {
        $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);

        $user_profile_api = $this->constructUserUrl($this->user_api['profile'], $uid);
        $response = $this->fetch($user_profile_api);
        $user = $response['result']['user'];
        $keys = array("age","displayName","gender","avatar","fullName");
        $result = array();
        foreach($keys as $key) {
            $result[$key] = $user[$key];
        }
        return $result;
    }

    public function subscribeToActivity($uid, $sid, $activity) {
        $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
        $sub_api = $this->constructSubscriptionUrl($this->user_api['subscribe_api'], $sid, $activity);
        $response = $this->fetch($sub_api, array(), OAuth2\Client::HTTP_METHOD_POST);
        return $response;
    }

    public function getSubscriptions($activity=null) {
        $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
        $subscription_url =  $this->user_api['list_subscriptions'];
        if($activity) {
            $subscription_url = str_replace('[activity]',$activity,$this->user_api['list_subscriptions_collection']);
        }
        $response = $this->fetch($subscription_url);
        return $response;
    }

    public function deleteSubscriptions($activity, $sid) {
        $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
        $subscription_url =  $this->constructSubscriptionUrl($this->user_api['subscribe_api'], $sid, $activity);

        $response = $this->fetch($subscription_url, array(), OAuth2\Client::HTTP_METHOD_DELETE);
        return $response;
    }

    public function getActivityData($uid, $activity, $date) {

        $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);

        $sleep_url_api = str_replace(array('[user-id]','[date]'), 
                                     array($uid, $date), 
                                     $this->user_api[$activity]
                         );
        $result = $this->client->fetch($sleep_url_api);
        if($result['code']==200) {
            $sleep_data = $result['result']['summary'];
            return $sleep_data;
        } else {
            return null;
        }
    }

    protected function constructUserUrl($url, $uid) {
        return str_replace("[user-id]", $uid, $url);
    }


    protected function constructSubscriptionUrl($url, $sid, $activity) {
         return str_replace('[activity]',$activity,str_replace("[subscription-id]", $sid, $url));
    }


    public function fetch($url, $params=array(), $http_method = OAuth2\Client::HTTP_METHOD_GET) {
        $response = $this->client->fetch($url, $params, $http_method);
        //Handle expired token
        if($response['code']==401 &&
              ( $response['result']['errors'][0]['errorType']=="expired_token"
               || 
               $response['result']['errors'][0]['errorType']=="invalid_token" )
             ) {
            $access_token = $this->getRefreshedAccessToken($this->refresh_token);
            if($access_token) {
                return $this->client->fetch($url, $params, $http_method);   
            } else {
                throw new Exception("Failed to refresh token");
            }
        } else {
            return $response;
        }
    }
} 