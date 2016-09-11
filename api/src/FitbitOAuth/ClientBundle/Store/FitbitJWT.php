<?php
/** Custom JWT for Fitbit Listener Project
 */

namespace FitbitOAuth\ClientBundle\Store;

use Firebase\JWT\JWT;
use Exception;

class FitbitJWT {

    public static function decode($jwt, $client_id, $client_secret) {

        $secret = base64_decode(strtr($client_secret, '-_', '+/'));

        try {
            // Decode the user
            $decodedToken = JWT::decode($jwt, $secret, array('HS256'));
            // validate that this JWT was made for us
            if ($decodedToken->aud != $client_id) {
                throw new CoreException("This token is not intended for us.");
            }
        } catch(\Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $decodedToken;
    }

    public static function encode($client_id, $client_secret,  $custom_payload = null, $lifetime = 36000) {

            $time = time();

            $payload = array(
                "iat" => $time,
            );

            if ($custom_payload) {
                $payload = array_merge($custom_payload, $payload);
            }

            $jti = md5(json_encode($payload));

            $payload['jti'] = $jti;
            $payload["exp"] = $time + $lifetime;
            $payload["aud"] = $client_id;

            $secret = base64_decode(strtr($client_secret, '-_', '+/'));

            $jwt = JWT::encode($payload, $secret);

            return $jwt;


    }

}
