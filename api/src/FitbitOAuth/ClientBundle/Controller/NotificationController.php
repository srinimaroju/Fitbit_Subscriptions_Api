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

class NotificationController extends Controller
{
    /**
     * @Route("/subapi/notify", name="Handle Fitbit Notifications")
     */
    public function verifyEndPointAction(Request $request)
    {
        $query_key = $request->query->get('verification_key');
        $fitbit_verification_key = $this->container->getParameter('fitbit_verification_key');
        if($query_key == $fitbit_verification_key) {
             $response = new Response("", 204);

        } else {
            $response = new Response("Not Found",404);
        }
        return $response;
    }
} 