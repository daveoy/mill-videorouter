<?php
require_once THIRD_PARTY . 'lib-auth/live/MillAuth.php';
require_once _LIBRARIES . 'core/MillAD.php';

class LoginModel extends Model 
{
    
    public function __construct($fields = array())
    {
        parent::__construct();
    }
    
    ##
    # Custom functions
    ##
    public function login($credentials = array())
    {
        global $config;
        if(empty($credentials))
            throw ErrorException("Wrong username or password", 500);

        $userDetails = array(
            // "user" => array(),
            // "permissions" => array(),
        );

        # make login using Auth classes
        $auth = new MillAuth(new MillAuthContainer(),"","",false);

        $auth->start();

        if($auth->checkAuth()) 
        {
            $username = $auth->getUsername();

            $activeDirectory = new MILL_AD();
            $fullname = $activeDirectory->adSearchUserID($username);

            # get user details
            // $username = "anthonyf";
            $userDetails['user'] = array(
                "username" => $username,
                "fullname" => $fullname
            );

            $organizationalUnit = $activeDirectory->getUserOU($username);

            # retrieve permissions by user's Organizational Unit
            if(!is_null($organizationalUnit))
            {
                if(is_array($config['admin_organizational_units'], $organizationalUnit))
                {
                    $userDetails['permissions'] = $config['permissions']['admin'];
                }
                else
                {
                    $userDetails['permissions'] = $config['permissions']['user'];
                }
            }
        }
        
        return $userDetails;
    }
}

?>