<?php

namespace FitbitOAuth\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use OAuth2;
use FitbitOAuth\ClientBundle\Entity\User;
use FitbitOAuth\ClientBundle\Store\FitbitJWT;

class SubscribeController extends Controller
{
    /**
     * @Route("/subscribe", name="auth")
     */
    public function subscribeAction(Request $request)
    {
         $logger = $this->get('logger');
        $oauth_client = $this->get("fitbit_oauth_client");

        if (!$request->query->get('code')) {
            return new RedirectResponse($oauth_client->getAuthenticationUrl());
        } else{
            $response = $oauth_client->getAccessToken($request->query->get('code'));
            if($response['code'] == 200) {
            
                $result = $response['result'];
                $client->setAccessToken($result['access_token']);
                    
                $user = new User();
                $user->setFitbitUid($result['user_id']);
                $uid = $result['user_id'];
                $user->setFitbitData(json_encode($result));

                $em = $this->getDoctrine()->getManager();
                  // tells Doctrine you want to (eventually) save the Product (no queries yet)
                $em->persist($user);

                // actually executes the queries (i.e. the INSERT query)
                $em->flush();
                $jwt = FitbitJWT::encode();
                $return_response = new Response('Saved new product with id '.$user->getId()." and jwt $jwt");
                $logger->info("Create jwt $jwt for user id $uid and returned");
                $return_response->headers->set("X-JWT-Auth","$jwt");
                return $return_response;
            } else {
                $logger->error("Error Occured processing subscription request");
                $logger->error($response);
                print "Error Occured processing subscription request";
                print_r($response); exit;   
            }

        }
    }
}
