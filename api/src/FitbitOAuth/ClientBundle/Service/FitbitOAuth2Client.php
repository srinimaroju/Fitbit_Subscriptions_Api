<?php

namespace FitbitOAuth\ClientBundle\Service;
use OAuth2;
class FitbitOAuth2Client  
{
    protected $client;
    protected $authEndpoint;
    protected $tokenEndpoint;
    protected $redirectUrl;
    protected $grant;
    protected $params;
    public function __construct(OAuth2\Client $client, $authEndpoint, $tokenEndpoint, $redirectUrl, $grant, $params)
    {
        $this->client = $client;
        $this->authEndpoint = $authEndpoint;
        $this->tokenEndpoint = $tokenEndpoint;
        $this->redirectUrl = $redirectUrl;
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

    public function fetch($url) {
        return $this->client->fetch($url);
    }
} 