<?php
/** Test JWT Tokenizer
 *  Encode and decode token
 */

namespace Tests\AppBundle\Store;

use FitbitOAuth\ClientBundle\Store\FitbitJWT;

class FitbitJWTTest extends \PHPUnit_Framework_TestCase {

    public function testToken() {

        $user_id = "myuid";
        $client_id = "clientid";
        $client_secret = "clientsecret";
        $payload["userid"] =   $user_id;

        $encoded_token = FitbitJWT::encode($client_id, $client_secret,  $payload); 

        $decoded_token = FitbitJWT::decode($encoded_token,$client_id, $client_secret);
        $this->assertEquals($decoded_token->userid,$user_id);
    }


}