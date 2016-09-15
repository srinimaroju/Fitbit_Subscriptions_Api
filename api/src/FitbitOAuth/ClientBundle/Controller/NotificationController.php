<?php
/**
 * Handles fitbit notifications
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
use FitbitOAuth\ClientBundle\Entity\Notification;

class NotificationController extends Controller
{
    /**
     * @Route("/webhook/notify", name="Handle Fitbit Notifications")
     */
    public function verifyEndPointAction(Request $request)
    {
        $method = $request->getMethod();

        if($method == Request::METHOD_POST || $request->query->get("post")) {
            $content = $request->getContent();
           // $fitbithandler = new FitbitDataHandler($user, $oauth_client);
            try {
                $notification = new Notification(null,$content);
                $data = json_decode($content);
                $notification->setFitbitUid($data[0]->ownerId);
                $em = $this->getDoctrine()->getManager();
                $em->persist($notification);
                $em->flush();
            } catch(Exception $e) {
                $logger->error(sprintf("Failed processing notification with message %s", $e->getMessage()));
            }
            
            //Return 204 even in case of exception
            return new Response("", Response::HTTP_NO_CONTENT);
        }

        $query_key = $request->query->get("verify");
        $verification_key = $this->container->getParameter("fitbit_verification_key");
        
        if($query_key == $verification_key) {
            //echo "http 204";
            $response_code = Response::HTTP_NO_CONTENT;

        } else {
            //echo "http 404";
            $response_code = Response::HTTP_NOT_FOUND;
        }
        $response = new Response("", $response_code);
        return $response;
    }
} 