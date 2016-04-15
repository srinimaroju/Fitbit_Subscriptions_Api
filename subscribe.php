<?php
require('OAuth2/Client.php');
require('OAuth2/GrantType/IGrantType.php');
require('OAuth2/GrantType/AuthorizationCode.php');

const CLIENT_ID     = '227HZF';
const CLIENT_SECRET = '0a856487889789c6f6ca1c691fdd9cc3';

const REDIRECT_URI           = 'https://www.pavans-world.com/fitbit/subscribe.php';
const AUTHORIZATION_ENDPOINT = 'https://www.fitbit.com/oauth2/authorize';
const TOKEN_ENDPOINT         = 'https://api.fitbit.com/oauth2/token';

$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);

if (!isset($_GET['code']))
{
    //Set the scope
    $scope = array("activity","heartrate","location","nutrition","profile","sleep");
    
    $scope_space_delimited_array = implode(" ", $scope);
    $extra_args = array("scope" => $scope_space_delimited_array);
  
    $auth_decoded = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI , $extra_args);
    $auth_url = str_replace("+","%20", $auth_decoded);
    //print  $auth_url; exit;
    header('Location: ' . $auth_url);
    die('Redirect');
}
else
{
    #echo "Test"; exit;
    $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
    //echo "geting token"; exit;
    $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
    
    parse_str($response['result'], $info);
    $client->setAccessToken($info['access_token']);
    //$response = $client->fetch('https://graph.facebook.com/me');
    if($response['code'] = 200) {
	var_dump($response, $response['result']);
    } else {
	print "some error occured";
	print_r($response); exit;	
    }

}
