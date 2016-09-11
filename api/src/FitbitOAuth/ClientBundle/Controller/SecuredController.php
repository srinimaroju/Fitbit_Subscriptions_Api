<?php
/**
 * Created by PhpStorm.
 * User: german
 * Date: 1/20/15
 * Time: 11:12 PM
 */

namespace FitbitOAuth\ClientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecuredController extends Controller
{
    /**
     * @Route("/api/ping", name="pingpage")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        print_r($user->getLastUpdatedAt());
        $user_details = $user->getUserProfileData();
      
        if ($user_details === null) {
            $username = 'ANONYMOUS';
        } else {
            $username = $user_details->displayName;
        }

        return new JsonResponse(array('status' => "Pong! {$username}"));
    }
    

    /**
     * @Route("/api/unsecure/ping", name="unsecurepingpage")
     */
    public function unsecureIndexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        print "Test"; exit;
        if($user == null || !is_object($user)) {
            $username = 'ANONYMOUS';
        } else {
            $username = $user->getUsername();
            if ($username === null) {
                $username = 'ANONYMOUS';
            }
        }
        return new JsonResponse(array('status' => "Pong! {$username}"));
    }

} 