<?php

require_once _LIBRARIES . "core/Socket.php";

class OutputDB extends Model
{
	public $labelTableName = "vr_label";
	public $detailsTableName = "vr_output";
	public $floorTableName = "vr_floor";
	public $hashMap = array(
		"port_uid" => "Id",
		"label" => "Label",
		"hardware" => "Hardware",
		"source" => "Source",
		"floor_uid" => "Floor_Uid",
		"floor" => "Floor"
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function getOutputDetails($portUid = null)
	{
		if(is_null($portUid))
			throw new ErrorException("portUid can't be null", 500);

		$details = $this->select(
			array(
				"tableName" => $this->labelTableName,
				"criteria" => array("port_uid" => $portUid, "type" => "output")
			)
		);

		return isset($details[0]) ? $details[0] : null;
	}

}


class Output
{

	protected $outputList;

	public function __construct()
	{
		global $config;

		// connect to Memcache
		$this->memcache = new Memcache();
		$this->memcache->addServer($config['cache']['ip'], $config['cache']['port']);
	}

	public function getOutput()
	{

		$outputs = $this->memcache->get('O');
		$outputs = is_null($outputs) ? array() : json_decode($outputs);

		# retrieve floors for all the ports
		$query = "";
		$outputDB = new OutputDB();
		$query = "SELECT vro.port_uid AS port_uid, vrf.* FROM " . $outputDB->detailsTableName . " AS vro INNER JOIN " . $outputDB->floorTableName . " AS vrf ON vro.floor_uid = vrf.uid";
		$tmpPortsDetails = $outputDB->query($query);

		# putting port_uid as key
		$portsDetails = array();
		foreach($tmpPortsDetails as $id => $retrievedPortObject)
		{
			$portsDetails[$retrievedPortObject->port_uid] = $retrievedPortObject;
		}

		foreach($outputs as $port => $output)
		{
			# set Id
                        $output->Id = $port + 1;

			$output->Floor = isset($portsDetails[$output->Id]) ? $portsDetails[$output->Id]->name : null;
			$output->Floor_uid = isset($portsDetails[$output->Id]) ? $portsDetails[$output->Id]->uid : null;
                        $output->Floor_position = isset($portsDetails[$output->Id]) ? $portsDetails[$output->Id]->position : null;

			# add to the outputList
                        $this->outputList[] = $output;
		}


		return $this->outputList;
	}

	public function getOutputDetails($portUid = null)
	{
		if(is_null($portUid))
			throw new ErrorException("portUid can't be null", 500);

		$outputDB = new OutputDB();
		$details = $outputDB->getOutputDetails($portUid);

		return !empty($details) ? $details : null;
	}
}
