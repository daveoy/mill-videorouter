<?php

require_once _MODELS . "Database.php";

class Model extends DatabaseModel {

	public function __construct()
	{
		parent::__constructor();
	}

	public function escapeString($string)
	{
		return mysql_real_escape_string($string);
	}

	public function escapeArray($array)
	{
	    array_walk_recursive($array, create_function('&$v', '$v = mysql_real_escape_string($v);'));
		return $array;
	}

}
?>
