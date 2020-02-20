<?php

require_once _LIBRARIES . "core/Socket.php";

class InputDB extends Model
{
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

	public function getInputDetails($portUid = null)
	{
		if(is_null($portUid))
			throw new ErrorException("portUid can't be null", 500);

		$details = $this->select(
			array(
				"tableName" => $this->labelTableName,
				"criteria" => array("port_uid" => $portUid, "type" => "input")
			)
		);

		return isset($details[0]) ? $details[0] : null;
	}

}

class Input
{

	protected $inputList;
	private $memcache;

	public function __construct()
	{
		global $config;

		// connect to Memcache
		$this->memcache = new Memcache();
		$this->memcache->addServer($config['cache']['ip'], $config['cache']['port']);
	}

	public function getInput()
	{
		$inputs = $this->memcache->get('I');
		$inputs = is_null($inputs) ? array() : json_decode($inputs);

		foreach($inputs as $port => $input)
		{
			$input->Id = $port + 1;
			$this->inputList[] = $input;
		}
		return $this->inputList;
	}

	public function getInputDetails($portUid = null)
	{
		if(is_null($portUid))
			throw new ErrorException("portUid can't be null", 500);

		$inputDB = new InputDB();
		$details = $inputDB->getInputDetails($portUid);

		return !empty($details) ? $details : null;
	}
}
