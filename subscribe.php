<?php
require('OAuth2/Client.php');
require('OAuth2/GrantType/IGrantType.php');
require('OAuth2/GrantType/AuthorizationCode.php');

const CLIENT_ID     = '227HZF';
const CLIENT_SECRET = '0a856487889789c6f6ca1c691fdd9cc3';

const REDIRECT_URI           = 'https://www.pavans-world.com/fitbit/subscribe.php';
const AUTHORIZATION_ENDPOINT = 'https://www.fitbit.com/oauth2/authorize';
const TOKEN_ENDPOINT         = 'https://api.fitbit.com/oauth2/access_token';

$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
if (!isset($_GET['code']))
{
    //Set the scope
    $scope = ["activity","heartrate","location","nutrition","profile","sleep"];
    $scope_space_delimited_array = implode("", $scope);
    
    $auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI , array("scope" => $scope_space_delimited_array));
    print  $auth_url; exit;
    header('Location: ' . $auth_url);
    die('Redirect');
}
else
{
    $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
    $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
    parse_str($response['result'], $info);
    $client->setAccessToken($info['access_token']);
    $response = $client->fetch('https://graph.facebook.com/me');
    var_dump($response, $response['result']);
}
