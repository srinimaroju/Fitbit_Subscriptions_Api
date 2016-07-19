<?php
/** Test JWT Tokenizer
 */

namespace FitbitOAuth\AppBundle\Tests;

use FitbitOAuth\ClientBundle\Store\FitbitJWT

class JWTTest extends \PHPUnit_Framework_TestCase {

    public function testGetAll() {

        $user_id = "myuid";

        $encoded_token = FitbitJWT::encode("")

        $this->assertTrue(is_array($response));
        $this->assertTrue(count($response)>0);

    }


}