<?php

class Database {
	
	protected $pdo;

	public function __construct() {
		global $config;
		
		// establish database connection
		$this->pdo = new PDO(
			$config['database']['dsn'],
			$config['database']['username'],
       		$config['database']['password']
		);
	}

	// get database connection
	public function getConnection() {
		return $this->pdo;
	}

	// do select query
	public function querySelect() {
		
	}

	// do insert query
	public function queryInsert($params=array()) {}

	// do update query
	public function queryUpdate($params=array()) {}

	// do delete query
	public function queryDelete($params=array()) {}
	

	// execute string query
	public function executeStringQuery($query=null) {
		$result = array();
		// check if query is not null
		if(!is_null($query)) {
			try{
				// execute query			
				$stmt = $this->pdo->prepare($query);
				$stmt->execute();
				// return as object
				$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			} catch(PDOException $e) {
				throw new ErrorException($e->getMessage, '500');
			}
		}
		return $result;
	}


}
