<?php
namespace FitbitOAuth\ClientBundle\Service;

use OAuth2;
use FitbitOAuth\ClientBundle\Entity\User;
use FitbitOAuth\ClientBundle\Service\FitbitOAuth2Client;
use FitbitOAuth\ClientBundle\Store\FitbitJWT;


class FitbitDataHandler {
	protected $em;
	protected $user;
	protected $client;

	public function __construct(User $user, FitbitOAuth2Client $client) {
		$this->user = $user;
		$this->client = $client;
	}

	public function getUserProfileData() {
		$user = $this->user;
        $oauth_client = $this->client;

        $data = $user->getFitbitData();

        $access_token = $data->access_token;
        $refresh_token = $data->refresh_token;

        $oauth_client->setRefreshToken($refresh_token);
        $oauth_client->setAccessToken($access_token);

        return $oauth_client->getUserProfileData($user->getFitbitUid());
	}
}