<?php

class GroupModel extends Model
{
	
	private $tableName = "vr_group";
	protected $uid;
	protected $name;
	protected $position;
	protected $active;

	public function __construct()
	{
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
    		// $methodName .= $i > 0 ? ucfirst($explodedMethod[$i]) : $explodedMethod[$i];
    		$methodName .= ucfirst($explodedMethod[$i]);
    	}

    	return "set" . $methodName;
    }

	##
	# Setter
	##
	public function setUid($uid)
	{
		$this->uid = $uid;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setPosition($position)
	{
		$this->position = $position;
	}

	public function setActive($active)
	{
		$this->active = $active;
	}

	##
	# Getter
	##
	public function getUid()
	{
		return $this->uid;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function getActive()
	{
		return $this->active;
	}

	##
	# Functions
	##
	public function getGroup($criteria = array())
	{
		$group = array();
		if(!empty($criteria))
		{
			$group = $this->select(
				array(
					"table_name" => $this->tableName,
					"criteria" => $criteria
				)
			);
		}
		return $group;
	}

	public function getGroups()
	{
		return $this->select(
			array(
				"table_name" => $this->tableName
			)
		);
	}

	public function getPortsByType($type = "input")
	{
		switch($type)
		{
			case "input": default:
				$query = "SELECT vrl.*, vrg.group_uid as group_uid, vrg.name as group_name, vrg.position as group_position, vril.uid AS locked, vril.username AS locker, vril.created AS date_lock FROM vr_label AS vrl INNER JOIN vr_group AS vrg ON vrl.group_uid = vrg.group_uid LEFT JOIN vr_input_lock AS vril ON vrl.port_uid = vril.port_uid WHERE vrl.type = '" . $type . "' AND vrg.active = 1 AND vrl.active = 1 ORDER BY vrg.position";
				break;
			
			case "output":
				$query = "SELECT vrl.*, vrf.name AS floor_name FROM vr_label AS vrl INNER JOIN vr_output AS vro ON vrl.port_uid = vro.port_uid INNER JOIN vr_floor AS vrf ON vrf.uid = vro.floor_uid WHERE vrl.type = '" . $type . "'";
				break;

		}
		$groups = $this->query($query);

		return $groups;
	}

	public function getGroupsByPortType($type = "input")
	{
		$query = "SELECT DISTINCT vrg.group_uid as group_uid, vrg.name as group_name FROM vr_label AS vrl INNER JOIN vr_group AS vrg ON vrl.group_uid = vrg.group_uid WHERE vrl.type = '" . $type . "' AND vrg.active = 1 AND vrl.active = 1 ORDER BY vrg.position";
		$groups = $this->query($query);

		return $groups;
	}

	public function getOutputFloors()
	{
		$query = "SELECT DISTINCT vrf.name AS floor, vrl.port_uid AS port_uid FROM vr_label AS vrl INNER JOIN vr_output AS vro ON vrl.port_uid = vro.port_uid INNER JOIN vr_floor AS vrf ON vrf.uid = vro.floor_uid WHERE vrl.type = 'output' GROUP BY vro.floor_uid";
		$floors = $this->query($query);

		return $floors;
	}

	public function getPortsByGroupUid($groupUid = null, $type = null)
	{
		$ports = array();
		if(!is_null($groupUid))
		{
			$query = "SELECT vrl.*, vrg.group_uid as group_uid, vrg.name as group_name FROM vr_label AS vrl INNER JOIN vr_group AS vrg ON vrl.group_uid = vrg.group_uid WHERE vrl.group_uid = " . $groupUid;
			if(!is_null($type) && ($type == "input" || $type == "output"))
				$query .= " AND vrl.type = '" . $type . "'";
			$query .= " AND vrg.active = 1 ORDER BY vrg.position";
			$ports = $this->query($query);
		}
		return $ports;
	}

	public function getPortsByFloor($floor = null)
	{
		$ports = array();
		if(!is_null($floor))
		{
			$query = "SELECT DISTINCT vrl.port_uid AS port_uid, vrl.name AS name FROM vr_label AS vrl INNER JOIN vr_output AS vro ON vrl.port_uid = vro.port_uid WHERE vro.floor_uid = " . $floor;
			$ports = $this->query($query);
		}


		return $ports;
	}

}
