<?php

require_once _MODELS . "Mysql.php";
require_once _INTERFACES . "iDatabase.php";

class DatabaseModel extends MysqlModel implements iDatabase
{
	public function __constructor()
	{
		// open database connection
		parent::__constructor();
	}


	/**
	*	tableName: name of the database table where to retrieve data
	*	toGet: fields to return (* by default)	
	*	criteria: fields used as criteria to filter results
	*/
	public function select($fields = array()) 
	{
		$tableName = null;
		$toGet = array();
		$criteria = array();
		$limit = null;

		if(!array_key_exists("query", $fields)) 
		{
			# tableName is mandatory
			if(!array_key_exists("tableName", $fields)) {
				throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing tableName", 500);
			} 

			$tableName = $fields['tableName'];

			# build query
			# fields to get
			if(array_key_exists("to_get", $fields))
			{
				foreach($fields['to_get'] as $field)
				{
					$key = ":" . $field; 
					$toGet[] = $field;
				}
			} 
			else 
			{
				$toGet = array("*");
			}

			# criteria
			if(array_key_exists("criteria", $fields))
			{
				foreach($fields['criteria'] as $fieldKey => $field)
				{
					$key = ":" . $fieldKey;
					$criteria[$key] = $field;
				}
			}

			if(array_key_exists("limit", $fields))
			{
				$limit = $fields['limit'];
			}

			$query = "SELECT " . implode(",", $toGet) . " FROM " . $tableName;
			if(!empty($criteria)) 
			{
				$query .= " WHERE ";
				foreach($criteria as $key => $value)
				{
					$query .= str_replace(":", "", $key) . " = $key AND ";
				}
				$query = rtrim($query, " AND ");
			}

			# limit
			if(!is_null($limit))
			{
				$query .= " LIMIT " . $limit;
			}

		} else {
			$query = $fields["query"];
		}

		# execute query
		$statement = $this->db->prepare($query);

		$statement->execute($criteria);
		
    	$result = $statement->fetchAll(PDO::FETCH_OBJ);
    	return $result;

	}

	/**
	*	tableName: name of the database table where to retrieve data
	*	object: object with fields
	*/
	public function insert($fields = array())
	{
		$keys = array();
		$values = array();

		# tableName is mandatory
		if(!array_key_exists("tableName", $fields)) 
		{
			throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing tableName", 500);
		}

		# check if object is passed as argument
		if(!array_key_exists("object", $fields)) 
		{
			throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing object", 500);
		}

		# get fields to store
		foreach($fields['object'] as $key => $value)
		{
			$keys[] = $key;
			$values[":" . $key] = $value;
		}

		# build query
		$query = "INSERT INTO " . $fields['tableName'] . " (";

		# implode keys
		$query .= implode(",", $keys);

		# remove last comma
		$query = rtrim($query, ", ");
		
		$query .= ") VALUES (";

		# loop values
		foreach($keys as $value)
		{
			$query .= ":" . $value . ", ";
		}

		# remove last comma
		$query = rtrim($query, ", ");
		$query .= ")";

		try 
		{
			# build statement
			$statement = $this->db->prepare($query);
			$statement->execute($values);

			return $this->db->lastInsertId();
		}
		catch(PDOException $e) 
		{
			throw new ErrorException($e->getMessage(), 500);
		}

	}

	/**
	*	tableName: name of the database table where to retrieve data
	*	object: object with fields
	*/
	public function update($fields = array()) 
	{
		$uid = null;
		$values = array();

		# tableName is mandatory
		if(!array_key_exists("tableName", $fields)) {
			throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing tableName", 500);
		}

		# check if object is passed as argument
		if(!array_key_exists("object", $fields)) {
			throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing object", 500);
		}

		# get uid of current object
		$uid = $fields['object']->uid;
		
		# build query
		$query = "UPDATE " . $fields['tableName'] . " SET ";

		foreach($fields['object'] as $key => $value)
		{
			if($key != "uid")
			{
				$query .= $key . " = :" . $key . ", ";
				$values[":" . $key] = $value;
			}
		}
		
		$query = rtrim($query, ", ");
		$query .= " WHERE uid = " . $uid;

		try 
		{
			# build statement
			$statement = $this->db->prepare($query);
			$statement->execute($values);

			return $statement->rowCount();
		}
		catch(PDOException $e) 
		{
			throw new ErrorException($e->getMessage(), 500);
		}

	}

	public function delete($fields = array()) 
	{
		$uid = null;
		$values = array();

		# tableName is mandatory
		if(!array_key_exists("tableName", $fields)) {
			throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing tableName", 500);
		}

		# check if object is passed as argument
		if(!array_key_exists("object", $fields)) {
			throw new ErrorException(get_class($this) . "::" . __FUNCTION__ . " => Missing object", 500);
		}

		# get uid
		$uid = $fields['object']->uid;
		
		# build query
		$query = "DELETE FROM " . $fields['tableName'] . " WHERE uid = :uid";
		$values[':uid'] = $uid;
		
		try 
		{
			# build statement
			$statement = $this->db->prepare($query);
			$statement->execute($values);

			return $statement->rowCount();
		}
		catch(PDOException $e) 
		{
			throw new ErrorException($e->getMessage(), 500);
		}
	}

	public function query($query, $verb = "select")
	{
		switch($verb)
		{
			case "select":
				# execute query
				$statement = $this->db->prepare($query);
				$statement->execute();
		    	$result = $statement->fetchAll(PDO::FETCH_OBJ);
		    	break;

		    case "update":
		    	$result = $this->db->exec($query); // returns affected rows
		    	break;

		    case "delete":
		    	$statement = $this->db->prepare($query);
				$statement->execute();
				$result = $statement->rowCount();
		    	break;

		}
		
    	return $result;
	}
}