<?php

namespace FitbitOAuth\ClientBundle\Service;


use FitbitOAuth\ClientBundle\Store\FitbitJWT;

/**
 *
 * Service that provides access JWT validation
 */
class TokenService {

    private $client_id;
    private $client_secret;
    private $oauth_client;

    public function __construct($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function generateJWT($custom_payload=null, $lifetime = 36000) 
    {
        return FitbitJWT::encode($this->client_id, $this->client_secret,  $custom_payload, $lifetime);
    }
    /**
     * Decodes the JWT and validate it
     *
     * @return stdClass
     */
    public function decodeJWT($encToken)
    {
        return FitbitJWT::decode($encToken, $this->client_id, $this->client_secret, $this->secret_base64_encoded);
    }
}
