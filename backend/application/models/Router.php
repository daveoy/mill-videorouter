<?php

require_once _LIBRARIES . "core/Socket.php";
require_once _MODELS . "Database.php";

class RouterDB extends Model
{
        public $lockTableName = "vr_input_lock";

        public function __construct() 
        {   
                parent::__construct();
        }   

        public function isPortLocked($portUid = null)
        {   
                if(is_null($portUid))
                        throw new ErrorException("portUid can't be null", 500);

                $details = $this->select(
                        array(
                                "tableName" => $this->lockTableName,
                                "criteria" => array("port_uid" => $portUid)
                        )   
                );

                return isset($details[0]) ? $details[0] : null;
        }   

}


class RouterModel extends Socket {

	protected $report;

	public function __construct() 
	{
		parent::__construct();
	}

	public function route($parameters = null)
	{
		$inputPort = $parameters['input_port'];
		$outputPort = $parameters['output_port'];

		# check if ports are locked (database check)
		$routerDB = new RouterDB();
		$isLocked = $routerDB->isPortLocked($inputPort);
		
		if($isLocked)
		{
			return "KO: Input port is locked";
		}

		# Fix Router ports
		$inputPort = $inputPort - 1;
		$outputPort = $outputPort - 1;

		# $routerInstruction = "R 1 $outputPort $inputPort";
		$routerInstruction = "VIDEO OUTPUT ROUTING:\n\r$outputPort $inputPort\n\r\n\r";
		$socket = new Socket();
		$return = $socket->send($routerInstruction);
		return $return;
	}


	# get I/O list and parse to put in session
	public function parseList($list = array())
	{
		$newList = array();
		if(!is_null($list))
		{
			foreach($list as $outputKey => $el)
			{	
				$newList[$el->Source] = $outputKey;
			}
		}
		return $newList;
	}
}
