<?php

require_once _LIBRARIES . "core/Socket.php";
require_once _MODELS . "Database.php";

class RouterMock extends Model {

	protected $report;
	private $tableName = "vr_output_test";

	public function __construct() 
	{
		parent::__construct();
	}

	public function route($parameters = null)
	{
		$inputPort = $parameters['input_port'];
		$outputPort = $parameters['output_port'];

		$routerInstruction = "R 1 $outputPort $inputPort";

		$query = "UPDATE vr_output_test SET source = " . $inputPort . " WHERE port_uid = " . $outputPort;		
		$return = $this->query($query, "update");

		return $return == 0 ? "KO: Routing failed" : "OK: Setting route completed";
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
