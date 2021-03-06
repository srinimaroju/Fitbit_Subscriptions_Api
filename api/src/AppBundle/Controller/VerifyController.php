<?php



namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * As per
 * https://dev.fitbit.com/docs/subscriptions/
 * 
 * 
 * Verify A Subscriber
 * All new or edited subscriber endpoints must be verified in order to receive subscription updates. Verification is necessary for both your security and that of Fitbit's, to ensure that you are the owner of the subscriber endpoint. Subscriber endpoints created before December 1st, 2015 are encouraged to implement the following verification test, but are not required to do so.
 * When a subscriber is added, or when its URL is changed, Fitbit sends 2 GET requests to the subscriber endpoint, each with a "verify" query string parameter. One request has the subscriber verification code (which you can find on your app details page) as the "verify" query string parameter value, and expects the subscriber to respond with a 204. Another request has an intentionally invalid verification code, and expects the subscriber to respond with a 404. For example:
 * GET https://yourapp.com/fitbit/webhook?verify=correctVerificationCode should result in a 204. GET https://yourapp.com/fitbit/webhook?verify=incorrectVerificationCode should result in a 404.
 * If both requests succeed, the subscriber is marked as verified on the app details page. Otherwise, the subscriber is marked as not verified, and an option is given to retry verification.
*/

class VerifyController extends Controller {
	/**
     * @Route("/verify")
     */
    public function indexAction(Request $request)
    {	
        $key = $request->query->get("verify");
        $fitbit_config = $this->container->getParameter("fitbit");
		$verification_key = $fitbit_config['verification_key'];
		
		if($key == $verification_key) {
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