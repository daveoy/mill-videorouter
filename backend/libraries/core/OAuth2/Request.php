<?php

class OAuth2_Request 
{

	protected $endpoint;
	protected $authType = "basic";
	protected $auth;
	protected $accessToken;
	protected $requestType = "GET";
	protected $fields;

	// Setters
	public function setEndpoint($endpoint) 
	{
		$this->endpoint = $endpoint;
	}

	public function setAuth($auth) 
	{
		$this->auth = $auth;
	}

	public function setRequestType($requestType) 
	{
		$this->requestType = $requestType;
	}

	public function setFields($fields) 
	{
		$this->fields = $fields;
	}

	public function setAuthType($authType="basic") 
	{
		$this->authType = $authType;
	}

	public function setAccessToken($accessToken) 
	{
		$this->accessToken = $accessToken;
	}

	// execute REST request to OAuth2 Server
	public function execute() 
	{
		$ch = curl_init($this->endpoint);
		// customize authentication method
		switch($this->authType) 
		{
			// used when an user logs in and the client has to ask for a new access_token
			case "basic": default:
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $this->auth);
				break;
			
			// used when the accessToken is available
			case "oauth2":
				//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded", "Authorization: Bearer " . $this->accessToken));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->accessToken));
				break;
		}
		
		// customize REST call
		switch($this->requestType) 
		{
			case "GET": default:
				break;

			case "POST": 
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
				break;
			case "PUT": case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->requestType);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
				break;
		} 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}

}
