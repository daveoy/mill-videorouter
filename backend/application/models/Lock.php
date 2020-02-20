<?php

require_once _LIBRARIES . 'core/MillAD.php';

class LockModel extends Model 
{
    private $tableName = "vr_input_lock";
    protected $uid;
    protected $port_uid;
    protected $username;
    protected $created;


	public function __construct($fields = array())
    {
        global $config;
        parent::__construct();
    }

    private function fixMethodName($methodName)
    {
    	$explodedMethod = explode("_", $methodName);
    	$pieces = count($explodedMethod);
    	
    	# build methodName replacing underscores and making uppercase the first character of every piece after the first one
    	$methodName = "";
    	for($i = 0; $i < $pieces; $i++)
    	{
    		$methodName .= ucfirst($explodedMethod[$i]);
    	}
	 
    	return "set" . $methodName;
    }

    ##
    # Setters
    ##

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

	public function setPortUid($portUid)
    {
    	$this->port_uid = $portUid;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setCreated($created)
    {
    	$this->created = $created;
    }

    ##
    # Getters
    ##

    public function getUid()
    {
        return $this->uid;
    }
    
    public function getPortUid()
    {
    	return $this->port_uid;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getCreated()
    {
    	return $this->created;
    }

    public function getLockedPorts()
    {
        return $this->select(
            array(
                "tableName" => $this->tableName
            )
        );
    }

    # port can be locked by anybody from Engineering and MCR
    public function lock($username = null, $portUid = null)
    {
        global $config;
        $return = array();
        # check if user has admin rights [not so secure, add two factor authentication]
        $activeDirectory = new Mill_AD();
        $userOrganizationalUnit = $activeDirectory->getUserOU($username);

        if(!is_null($userOrganizationalUnit))
        {
            if(!in_array($userOrganizationalUnit, $config['admin_organizational_units']))
                throw new ErrorException("User " . $username . " doesn't have rights to lock a machine", 500);
            
            # check if port already locked
            $locked = $this->isAlreadyLocked($portUid);
            
            if($locked)
                throw new ErrorException("Port " . $portUid . " already locked", 500);

            # build object
            $object = new stdClass();
            $object->port_uid = $portUid;
            $object->username = $username;
            $object->created = time();

            # lock port 
            $return = $this->insert(
                array(
                    "tableName" => $this->tableName,
                    "object" => $object
                )
            );
        }

        return $return;

    }

    # port can be unlocked by anybody from Engineering and MCR
    public function unlock($username = null, $portUid = null)
    {
        global $config;
        $return = array();
        # check if user has admin rights [not so secure, add two factor authentication]
        $activeDirectory = new Mill_AD();
        $userOrganizationalUnit = $activeDirectory->getUserOU($username);

        if(!is_null($userOrganizationalUnit))
        {
            if(!in_array($userOrganizationalUnit, $config['admin_organizational_units']))
                throw new ErrorException("User " . $username . " doesn't have rights to unlock this machine", 500);
            
            # check if port already locked
            $unlocked = $this->isAlreadyUnlocked($portUid);
            
            if($unlocked)
                throw new ErrorException("Port " . $portUid . " already unlocked", 500);

            # get record uid
            $port = $this->select(
                array(
                    "tableName" => $this->tableName,
                    "criteria" => array("port_uid" => $portUid),
                )
            );

            # if port locked, unlock it
            if(isset($port[0]))
            {
                $return = $this->delete(
                    array(
                        "tableName" => $this->tableName,
                        "object" => $port[0]
                    )
                );
            }

        }

        return $return;
    }

    private function isAlreadyLocked($portUid = null)
    {
        # check if portUid is null
        if(is_null($portUid))
            throw new ErrorException("", 500);

        $port = $this->select(
            array(
                "tableName" => $this->tableName,
                "criteria" => array("port_uid" => $portUid)
            )
        );

        if(isset($port[0]))
            return true;
        else
            return false;
    }

    private function isAlreadyUnlocked($portUid = null)
    {
        # check if portUid is null
        if(is_null($portUid))
            throw new ErrorException("", 500);

        $port = $this->select(
            array(
                "tableName" => $this->tableName,
                "criteria" => array("port_uid" => $portUid)
            )
        );

        if(!isset($port[0]))
            return true;
        else
            return false;
    }
}

?>