<?php

namespace FitbitOAuth\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use OAuth2;


class SubscribeController extends Controller
{
    /**
     * @Route("/subscribe", name="auth")
     */
    public function subscribeAction(Request $request)
    {
        print_r("test");
        $oauth_client = $this->get("fitbit_oauth_client");
        print_r($oauth_client); exit;
        if (!$request->query->get('code')) {
            
        }

        //$authorizeClient->getAccessToken($request->query->get('code'));

        exit;
    }
}
