<?php
/**
 * Secure functions here
 */

namespace FitbitOAuth\ClientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;


use FitbitOAuth\ClientBundle\Service\FitbitDataHandler;

class SecuredController extends Controller
{
    /**
     * @Route("/user/get/profile", name="getProfilePage")
     */
    public function getProfileAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //print_r($user->getLastUpdatedAt());
        $user_details['profile'] = $user->getUserProfileData();
        $user_details['email'] = $user->getEmail();
      
        if ($user_details === null) {
            $username = 'ANONYMOUS';
        } else {
            $username = $user_details['profile']->displayName;
        }

        return new JsonResponse(array('result' => $user_details));
    }
    
    /**
     * @Route("/user/subscribe/sleep", name="subscribetosleep")
     */
    public function subscribeToSleepAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $oauth_client = $this->get("fitbit_oauth_client");
        $fitbithandler = new FitbitDataHandler($user, $oauth_client);
        $em = $this->getDoctrine()->getManager();
        $response = $fitbithandler->subscribeToActivity($em,'sleep');

        return new JsonResponse(array('result' => $response));
    }
    

    /**
     * @Route("/user/set/email/{email}", name="Setemail")
     */
    public function setUserEmailAction(Request $request, $email) {
        $method = $request->getMethod(); 

        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $emailConstraint = new EmailConstraint();
        $emailConstraint->message = 'Invalid Email';
        
        $errors = $this->get('validator')->validate(
            $email,
            $emailConstraint 
        );
        if($errors->count()) {
            throw new AccessDeniedHttpException($errors);   
        } else {
            $user->setEmail($email);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(array('result' => "success"));
        }
    }
    /**
     * @Route("/unsecure/ping", name="unsecurepingpage")
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