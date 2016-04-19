<?php

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

error_reporting(E_ALL);
ini_set('display_errors', 1);

$request_uri=$_SERVER['REQUEST_URI'];
$query_string = null;
$query_strings = null;
if(stristr($request_uri,'?')) {
	$query_array = explode("?", $request_uri);
	$query_string = $query_array[1];
	$query_string_array = explode("=", $query_string);
	if(sizeof($query_string_array) && $query_string_array[0]) {
		$query_strings[$query_string_array[0]] = $query_string_array[1];
	}
}
if($query_strings['verify'] == "9b55fda64956820da9becd83851563a6c14673501288b28e6ee2d7e1c3931724") {
	//echo "http 204";
	 header("HTTP/1.0 204 No Response");

} else {
	//echo "http 404";
	header("HTTP/1.0 404 Not Found");
}
