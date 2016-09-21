<?php
namespace FitbitOAuth\ClientBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class CorsListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {   
    	
    	//Modify json response
    	if($event->getResponse() instanceof JsonResponse) {
	        $responseHeaders = $event->getResponse()->headers;

	        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
	        $responseHeaders->set('Access-Control-Allow-Origin', '*');
	        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
	        
	        $callback = $event->getRequest()->query->get('callback');
	        if($callback) {
	        	$event->getResponse()->setCallback($callback);
	        }
    	}
    }   
}