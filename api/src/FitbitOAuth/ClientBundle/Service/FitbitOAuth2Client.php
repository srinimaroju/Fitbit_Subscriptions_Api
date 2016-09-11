<?php

namespace FitbitOAuth\ClientBundle\Service;
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
        }
    }

    public function setAccessToken($token){
        $this->client->setAccessToken($token);
    }

    public function setRefreshToken($token){
        $this->refresh_token= $token;
    }

    public function getUserProfileData($uid) {
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

    protected function constructUserUrl($url, $uid) {
        return str_replace("[user-id]", $uid, $url);
    }

    public function fetch($url) {
        $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
        $response = $this->client->fetch($url);

        //Handle expired token
        if($response['code']==401 && $response['result']['errors'][0]['errorType']=="expired_token") {
            $access_token = $this->getRefreshedAccessToken($this->refresh_token);
            return $this->client->fetch($url);
        } else {
            return $response;
        }
    }
} 