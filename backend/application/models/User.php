<?php

require_once _LIBRARIES . "core/Proxy.php";

class UserModel extends Model 
{
	private $tableName = "users";
	protected $uid;
	protected $department_uid;
	protected $username;
	protected $fullname;
	protected $email;
	protected $uuid;
	protected $milltv;
	protected $access;
	protected $password;
	protected $calendar_id;
	protected $remote;
    protected $last_job_uid;
    protected $job_title;
    protected $extension_number;
	protected $location;

	public function __construct($fields = array())
    {
        parent::__construct();

        if(!empty($fields))
        {
        	$this->loadUser($fields);
        }
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

    private function loadUser($fields)
    {
		# build object
    	foreach($fields as $key => $value)
    	{
    		# fix name to retrieve method
    		$method = $this->fixMethodName($key);
			
    		# check if method exists
    		if(method_exists($this, $method))
    		{
    			$this->{$method}($value);
    		}
    	}
    }

    ##
    # Setters
    ##
	public function setUid($uid)
    {
    	$this->uid = $uid;
    }

    public function setDepartmentUid($departmentUid)
    {
    	$this->department_uid = $departmentUid;
    }

    public function setUsername($username)
    {
    	$this->username = $username;
    }

    public function setFullname($fullname)
    {
    	$this->fullname = $fullname;
    }

    public function setEmail($email)
    {
    	$this->email = $email;
    }

    public function setUuid($uuid)
    {
    	$this->uuid = $uuid;
    }

    public function setMilltv($milltv)
    {
    	$this->milltv = $milltv;
    }

    public function setAccess($access)
    {
    	$this->access = $access;
    }

    public function setPassword($password)
    {
    	$this->password = $password;
    }

    public function setCalendarId($calendarId)
    {
    	$this->calendar_id = $calendarId;
    }

    public function setRemote($remote)
    {
    	$this->remote = $remote;
    }

    public function setLastJobUid($lastJobUid)
    {
    	$this->last_job_uid = $lastJobUid;
    }

    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    public function setExtensionNumber($extensionNumber)
    {
        $this->extensionNumber = $extensionNumber;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    ##
    # Getters
    ##

    public function getUid()
    {
    	return $this->uid;
    }
    
    public function getDepartmentUid()
    {
    	return $this->department_uid;
    }
    
    public function getUsername()
    {
    	return $this->username;
    }
    
    public function getFullname()
    {
    	return $this->fullname;
    }
    
    public function getEmail()
    {
    	return $this->email;
    }
    
    public function getUuid()
    {
    	return $this->uuid;
    }
    
    public function getMilltv()
    {
    	return $this->milltv;
    }
    
    public function getAccess()
    {
    	return $this->access;
    }
    
    public function getPassword()
    {
    	return $this->password;
    }
    
    public function getCalendarId()
    {
    	return $this->calendar_id;
    }
    
    public function getRemote()
    {
    	return $this->remote;
    }
    
    public function getLastJobUid()
    {
    	return $this->last_job_uid;
    }

    public function getJobTitle()
    {
        return $this->job_title;
    }

    public function getExtensionNumber()
    {
        return $this->extension_number;
    }

    public function getLocation()
    {
        return $this->location;
    }
    
    ##
    # Custom functions
    ##

    public function saveUser()
    {
    	# remove db object and uid
    	$object = clone $this;
    	unset($object->db);
    	unset($object->uid);

    	# insert new user
    	$id = $this->insert(
    		array(
    			"tableName" => $this->tableName,
    			"object" => $object
    		)
    	);

    	return $id;
    }

    public function load($uid)
    {
    	$user = $this->select(
    		array(
    			"tableName" => $this->tableName,
    			"criteria" => array("uid" => $uid)
			)
    	);

    	if(isset($user[0])) 
    	{
    		$this->loadUser($user[0]);
    	}
    }

    public function updateUser($userFields)
    {
        # update user object
        $this->loadUser($userFields);
        
        # remove db object
        $object = clone $this;
        unset($object->db);

        $return = $this->update(
            array(
                "tableName" => $this->tableName,
                "object" => $object
            )
        );

        return $return;
    }

    public function deleteUser()
    {
        # remove db object
        $object = clone $this;
        unset($object->db);

        return $return = $this->delete(
            array(
                "tableName" => $this->tableName,
                "object" => $object
            )
        );
    }

    public function getUser($id)
    {
    	# get user by uid
    	$user = $this->select(
    		array(
    			"tableName" => $this->tableName,
    			"to_get" => array("uid", "department_uid", "username", "fullname", "email", "uuid", "milltv", "access", "calendar_id" ,"remote", "last_job_uid"),
    			"criteria" => array("uid" => $id)
			)
    	);

        $user = isset($user[0]) ? $user[0] : null;

        # get additional details for the user 
        $user = $this->getAdditionalDetails($user);

        return $user;
    }

    public function getUsers()
    {
    	# get all users
    	$users = $this->select(
    		array(
    			"tableName" => $this->tableName,
    			//"to_get" => array("uid", "username"),
			)
    	);

    	return $users;
    }

    private function getAdditionalDetails($user)
    {
        global $config;
        if(!is_null($user))
        {
            # initialize fields
            $user->job_title = null;
            $user->extension_number = null;
            $user->location = null;

            # get additional details from Directory service
            $proxy = new Proxy();
            $proxy->setEndpoint($config['directory']['entdpoint'] . "get-names.php");
            $proxy->setRequestType("POST");
            $proxy->setFields(array("search" => $user->fullname, "groups" => false));
            $details = $proxy->execute();

            # unfortunately we receive a json between brakets, so ltrim/rtrim them and decode json
            $details = rtrim($details, ")");
            $details = ltrim($details, "(");

            $details = json_decode($details);

            if(!empty($details->results))
            {
                $userDetails = $details->results[0];

                $user->job_title = $userDetails->job;
                $user->extension_number = $userDetails->ext;
                $user->location = $config['the_mill']['location'][$userDetails->location_data->location];
            }
        }
        
        return $user;
    }

}

?>
