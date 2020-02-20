<?php

class MysqlModel
{
	protected $db;

	public function __constructor()
	{
		# open database connection
		$this->connect();
	}

	public function connect()
	{
		global $config;
		try
		{

			# open database connection
			$this->db = new PDO('mysql:host=' . $config['database']['mysql']['videorouter']['host'] . ';dbname=' . $config['database']['mysql']['videorouter']['dbname'], 
				$config['database']['mysql']['videorouter']['user'], 
				$config['database']['mysql']['videorouter']['password']);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch(PDOException $pdoException)
		{
			print_r($pdoException);die();
		}
	}

	public function closeConnection()
	{
		# close database connection
		$this->db = null;
	}

}
