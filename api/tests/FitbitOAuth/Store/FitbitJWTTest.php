<?php
/** Test JWT Tokenizer
 *  Encode and decode token
 */

namespace Tests\AppBundle\Store;

use FitbitOAuth\ClientBundle\Store\FitbitJWT;

class FitbitJWTTest extends \PHPUnit_Framework_TestCase {

    public function testToken() {

        $user_id = "myuid";
        $client_id = "mycustomclient";
        $client_secret = "mycustomsecret";
        $payload["userid"] =   $user_id;

        $encoded_token = FitbitJWT::encode($client_id, $client_secret,  $payload); 
        //$encoded_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJpYXQiOjE0NzIzNTc4NjYsImp0aSI6ImUzZTYxYTNlMjBkZDBkYzhmMzJlODE0YmE4ODUyZDc2IiwiZXhwIjoxNDcyMzkzODY2LCJhdWQiOiJteWN1c3RvbWNsaWVudCJ9.CmWfHlcrvT-8w5KtTfPn8SCB77pBWSyLnHrrdT8zUas";
        $decoded_token = FitbitJWT::decode($encoded_token,$client_id, $client_secret);
        //print_r($decoded_token); exit;
        $this->assertEquals($decoded_token->userid,$user_id);
    }
}