<?php

class OutputMock extends Model {
	
	private $tableName = "vr_output_test";
	private $labelTableName = "vr_label";
	private $detailsTableName = "vr_output";
	private $floorTableName = "vr_floor";
	private $hashMap = array(
		"port_uid" => "Id",
		"label" => "Label",
		"hardware" => "Hardware",
		"source" => "Source",
		"floor_uid" => "Floor_Uid",
		"floor" => "Floor",
		"floor_position" => "Floor_position"
	);

	protected $outputList;

	public function __construct() 
	{
		parent::__construct();
	}

	public function getOutput()
	{
		
		$retrievedOutputList = $this->select(
			array(
				"tableName" => $this->tableName,
			)
		);

		foreach($retrievedOutputList as $current)
		{
			$object = new stdClass();
			
			$query = "SELECT vrf.* FROM " . $this->detailsTableName . " AS vro INNER JOIN " . $this->floorTableName . " AS vrf ON vro.floor_uid = vrf.uid WHERE vro.port_uid = " . $current->port_uid . " ORDER BY position DESC";
			$details = $this->query($query);
			$current->floor = isset($details[0]) ? $details[0]->name : null;
			$current->floor_uid = isset($details[0]) ? $details[0]->uid : null;
			$current->floor_position = isset($details[0]) ? $details[0]->position : null;
			foreach($this->hashMap as $key => $translation)
			{	
				if(isset($current->{$key}))
					$object->{$translation} = $current->{$key};
			}
			$this->outputList[$object->Id] = $object;
		}

		return $this->outputList;
	}

	public function getOutputDetails($portUid = null)
	{
		if(is_null($portUid))
			throw new ErrorException("portUid can't be null", 500);

		$details = $this->select(
			array(
				"tableName" => $this->labelTableName,
				"criteria" => array("type" => "output", "port_uid" => $portUid)
			)
		);

		return isset($details[0]) ? $details[0] : null;
	}
}
