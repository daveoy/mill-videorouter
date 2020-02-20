<?php
require(_APPLICATION . 'config' . DS . 'Config.php');
require(_LIBRARIES . '/core/OAuth2/Request.php');

class OAuth2 {
	
	protected $clientId;
	protected $clientSecret;
	protected $endpoint;

	public function __construct() 
	{
		global $config;
		$this->setClientId($config['oauth2']['client_id']);
		$this->setClientSecret($config['oauth2']['client_secret']);
		$this->setEndpoint($config['oauth2']['endpoint']);
	}

	// Setters
	protected function setClientId($clientId) 
	{
		$this->clientId = $clientId;
	}

	protected function setClientSecret($clientSecret) 
	{
		$this->clientSecret = $clientSecret;
	}

	protected function setEndpoint($endpoint) 
	{
		$this->endpoint = $endpoint;
	}


	// Getters
	protected function getClientId() 
	{
		return $this->clientId;
	}

	protected function getClientSecret() 
	{
		return $this->clientSecret;
	}

	protected function getEndpoint() 
	{
		return $this->endpoint;
	}

	// Check if the user has permission with a given Access Token
	public function hasPermission($userId=null, $accessToken=null) 
	{
		// call oauth2 server and check token
        if(!is_null($userId) && !is_null($accessToken)) 
        {
            if(strlen($userId) > 0 && strlen($accessToken) > 0) 
            {
               	$request = new OAuth2_Request();
				$request->setEndpoint($this->endpoint . 'resource.php');
				$request->setAuthType("oauth2");
				$request->setAccessToken($accessToken);
				$request->setRequestType("POST");
				//$request->setFields(array('access_token' => $accessToken));
				$request->setFields(array('userId' => $userId));
				//print_r($request);die();
				$return = $request->execute();
				//print_r($return);die();
				if(!is_null($return)) 
				{
					if(!empty($return)) 
					{
						$return = json_decode($return);
						if(isset($return->success)) 
						{
							return true;
						} 
						else if(isset($return->error)) 
						{
							if($return->error == 'invalid_token') 
							{
								// generate another token
								return $return;
							}
						}
					}
				}
        	}
        }
        return false;
	}

	// Request a token for the current user, if he is authorized from the OAuth2 Server
	public function requestToken($userId) 
	{
		$request = new OAuth2_Request();
		$request->setEndpoint($this->endpoint . 'token.php');
		$request->setAuthType("basic");
		$request->setAuth($this->clientId.":".$this->clientSecret);
		$request->setRequestType("POST");
		$request->setFields(array('grant_type' => 'client_credentials', 'user_id' => $userId));
		//print_r($request);die();
		$response = $request->execute();
		if(!is_null($response)) 
		{
			$response = json_decode($response);
			if(isset($response->access_token))
				return $response->access_token;
		}
		return false;
	}

	// Refresh token for the current user, if he is authorized from the OAuth2 Server
	public function refreshToken($userId, $accessToken) 
	{
		$request = new OAuth2_Request();
		$request->setEndpoint($this->endpoint . 'token.php');
		$request->setAuthType("basic");
		$request->setAuth($this->clientId.":".$this->clientSecret);
		$request->setRequestType("POST");
		$request->setFields(array('grant_type' => 'client_credentials', 'user_id' => $userId, 'access_token' => $accessToken, 'refresh_token' => 1));
		$response = $request->execute();
		if(!is_null($response)) 
		{
			$response = json_decode($response);
			if(isset($response->access_token))
				return $response->access_token;
		}
		return false;
	}
}

