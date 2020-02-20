<?php

class InputMock extends Model {
	
	private $tableName = "vr_input_test";
	private $labelTableName = "vr_label";
	private $hashMap = array(
		"port_uid" => "Id",
		"label" => "Label",
		"hardware" => "Hardware",
		"source" => "Source"
	);
	protected $inputList;

	public function __construct() 
	{
		parent::__construct();
	}

	public function getInput()
	{
		$retrievedInputList = $this->select(
			array(
				"tableName" => $this->tableName,
			)
		);

		foreach($retrievedInputList as $current)
		{
			$object = new stdClass();
			foreach($this->hashMap as $key => $translation)
			{	
				if(isset($current->{$key}))
					$object->{$translation} = $current->{$key};
			}
			$this->inputList[$object->Id] = $object;
		}

		return $this->inputList;
	}

	public function getInputDetails($portUid = null)
	{
		if(is_null($portUid))
			throw new ErrorException("portUid can't be null", 500);

		$details = $this->select(
			array(
				"tableName" => $this->labelTableName,
				"criteria" => array("type" => "input", "port_uid" => $portUid)
			)
		);

		return isset($details[0]) ? $details[0] : null;
	}
}
