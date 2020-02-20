<?php

class ProfileimageModel extends Model 
{
    private $endpoint = "";
	// private $tableName = "users";
	// protected $uid;
	// protected $department_uid;
	// protected $username;
	// protected $fullname;
	// protected $email;
	// protected $uuid;
	// protected $milltv;
	// protected $access;
	// protected $password;
	// protected $calendar_id;
	// protected $remote;
	// protected $last_job_uid;
    protected $user_uid;
    protected $path;


	public function __construct($fields = array())
    {
        global $config;
        parent::__construct();

        $this->endpoint = $config['profile_image']['endpoint'];

        // if(!empty($fields))
        // {
        // 	$this->loadUser($fields);
        // }
    }

    private function fixMethodName($methodName)
    {
    	$explodedMethod = explode("_", $methodName);
    	$pieces = count($explodedMethod);
    	
    	# return methodName in case there are no underscores
    	// if($pieces > 1) 
    	// {

    	# build methodName replacing underscores and making uppercase the first character of every piece after the first one
    	$methodName = "";
    	for($i = 0; $i < $pieces; $i++)
    	{
    		// $methodName .= $i > 0 ? ucfirst($explodedMethod[$i]) : $explodedMethod[$i];
    		$methodName .= ucfirst($explodedMethod[$i]);
    	}
	    // }

    	return "set" . $methodName;
    }

  //   private function loadUser($fields)
  //   {
		// # build object
  //   	foreach($fields as $key => $value)
  //   	{
  //   		# fix name to retrieve method
  //   		$method = $this->fixMethodName($key);
			
  //   		# check if method exists
  //   		if(method_exists($this, $method))
  //   		{
  //   			$this->{$method}($value);
  //   		}
  //   	}
  //   }

    ##
    # Setters
    ##
	public function setUserUid($userUid)
    {
    	$this->user_uid = $userUid;
    }

    public function setPath($path)
    {
    	$this->path = $path;
    }

    ##
    # Getters
    ##

    public function getUserUid()
    {
    	return $this->user_uid;
    }
    
    public function getPath()
    {
    	return $this->path;
    }
    
    public function getProfileImage($username = null)
    {
        $image = null;
        if(!is_null($username))
        {
            $this->endpoint .= "get.php?username=" . $username;
            # get image from global profile image endpoint 
            $ch = curl_init($this->endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $image = curl_exec($ch);
        }

        return $image;
    }

    public function updateProfileImage($username = null, $filePath = null)
    {

        // $file = file_get_contents($filePath);
        // print_r($file);die();
        
        if(!is_null($username)) {
            $this->endpoint .= "update.php";
            $postFields = array("username" => $username, "image" => "@".$filePath, "action" => "upload");
            # get image from global profile image endpoint 
            $ch = curl_init($this->endpoint);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: image/jpeg"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                //, "" => $file));
            $image = curl_exec($ch);
        }

        return $image;
    }

}

?>