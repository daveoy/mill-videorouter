<?php

class TokenModel extends Model 
{
    protected $endpoint = "";
    protected $access_token;
    
    public function __construct($fields = array())
    {
        global $config;
        $endpoint = $config['http_base_url'] . "token.php";
        parent::__construct();
    }

    ##
    # Setters
    ##
	
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setAccessToken($accessToken)
    {
        $this->access_token = $accessToken;
    }

    ##
    # Getters
    ##

    public function getUser()
    {
        return $this->user;
    }
    
    public function getAccessToken()
    {
        return $this->access_token;
    }
    
    ##
    # Custom functions
    ##

    public function requestToken($userId = null)
    {
        if(is_null($userId))
            throw ErrorException("Missing userId to generate Access Token", 500);

        $oauth2 = new OAuth2();
        $accessToken = $oauth2->requestToken($userId);
        if($accessToken !== false)
            $response = array('response' => 200, 'error' => 0, 'data' => array('user_id' => $userId, 'access_token' => $accessToken));
        else
            $response = array('response' => 501, 'error' => 0, 'data' => array('message' => 'Access Denied for the current user'));

        return $response;
    }

    public function refreshToken($userId = null, $accessToken = null)
    {
        if(is_null($userId) || is_null($accessToken))
            throw ErrorException("Please provide user_id and access_token to refresh token", 500);

        $oauth2 = new OAuth2();
        $accessToken = $oauth2->refreshToken($userId, $accessToken);
        if($accessToken !== false)
            $response = array('response' => 200, 'error' => 0, 'data' => array('user_id' => $userId, 'access_token' => $accessToken));
        else
            $response = array('response' => 501, 'error' => 0, 'data' => array('message' => 'Access Denied for the current user'));

        return $response;
    }
}

?>