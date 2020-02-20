<?php

class LogModel extends Model 
{
    private $tableName = "vr_log";
    protected $uid;
    protected $user_uid;
    protected $input_port_uid;
    protected $output_port_uid;
    protected $status;
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

	public function setUserUid($userUid)
    {
    	$this->user_uid = $userUid;
    }

    public function setInputPortUid($inputPortUid)
    {
        $this->input_port_uid = $inputPortUid;
    }

    public function setOutputPortUid($outputPortUid)
    {
        $this->output_port_uid = $outputPortUid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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
    
    public function getUserUid()
    {
    	return $this->user_uid;
    }
    
    public function getInputPortUid()
    {
        return $this->input_port_uid;
    }
    
    public function getOutputPortUid()
    {
        return $this->output_port_uid;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreated()
    {
    	return $this->created;
    }

    public function log($userUid = 0, $ports = array(), $status = "Failed")
    {
        $object = clone $this;
        unset($object->tableName);
        unset($object->uid);
        unset($object->db);

        $object->setUserUid($userUid);
        $object->setInputPortUid($ports['input_port']);
        $object->setOutputPortUid($ports['output_port']);
        $object->setStatus($status);
        $object->setCreated(time());

        $result = $this->insert(
            array(
                "tableName" => $this->tableName,
                "object" => $object 
            )
        );
        
        return $result;
    }

    public function getLogs($outputList)
    {
        $portsList = array();
        foreach($outputList as $key => $output)
        {
            $portsList[$output->Id] = $output->Source;

            # add to output list empty "created" field by default
            $outputList[$key]->Created = 0;            
        }        
        if(!empty($portsList))
        {   
            # get all the logs
            $query = "SELECT DISTINCT(output_port_uid), input_port_uid, uid, status, created FROM (";
            foreach($portsList as $output => $input)
            {
                $query .= "SELECT * FROM " . $this->tableName . " WHERE status = \"Success\" AND input_port_uid = " . $input . " AND output_port_uid = " . $output . " UNION ALL ";
            }

            $query = rtrim($query, " UNION ALL ");
            $query .= ") AS t ORDER BY created DESC";

            $logs = $this->query($query);

            # add logs for each connection
            foreach($outputList as $key => $output)
            {
                foreach($logs as $log)
                {
                    if($log->input_port_uid == $output->Source && $log->output_port_uid == $output->Id)
                    {
                        $outputList[$key]->Created = $log->created;
                    }
                }
            }
        }

        return $outputList;
    }

}

?>