<?php
// ini_set("display_errors", true);
// error_reporting(E_ALL);
// data too heavy for the $_POST, manage it manually
$pairs = explode("&", file_get_contents("php://input"));
$fields = array();
foreach ($pairs as $pair) {
    $nv = explode("=", $pair);
    $name = urldecode($nv[0]);
    $value = urldecode($nv[1]);
    $fields[$name] = $value;
}

require_once "Config.php";

# DB Connection
# open database connection
$db = new PDO('mysql:host=' . $config['database']['mysql']['videorouter']['host'] . ';dbname=' . $config['database']['mysql']['videorouter']['dbname'], 
							  $config['database']['mysql']['videorouter']['user'], 
							  $config['database']['mysql']['videorouter']['password']);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$arrayForQueries = array();
foreach($fields as $key => $field)
{
	# build array for queries, will be always "a_b_c" (input_label_2)
	$explodedKey = explode("_", $key);
	if(preg_match("/_label_level_[1-2]_/i", $key, $match))
	{
		# clean left and right spaces
		$field = ltrim($field);
		$field = rtrim($field);

		# get level_1 and level_2
		# input => port_uid => key
		if(strlen($arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]]) > 0 && $field != "")
		{
			$arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]] = $arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]] . "|" . $field;
		}
		else if($field != "")
		{
			$arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]] = $field;
		}
		// $arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]] = strlen($arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]]) > 0 ? $arrayForQueries[$explodedKey[0]][$explodedKey[4]][$explodedKey[1]] . "|" . $field : $field;
	}
	else if(preg_match("/short_label/i", $key, $match))
	{
		$arrayForQueries[$explodedKey[0]][$explodedKey[3]][$match[0]] = $field;
	}
	else
	{
		$arrayForQueries[$explodedKey[0]][$explodedKey[2]][$explodedKey[1]] = $field;
	}
}

if(isset($arrayForQueries['input']))
{	

	# execute input queries
	foreach($arrayForQueries['input'] as $port => $details)
	{
		# check if port has been disabled from CMS
		if(!isset($details['active']))
			$details['active'] = 0;
		else
			$details['active'] = 1;

		$query = "UPDATE vr_label SET name = \"" . $details['label'] . "\", group_uid = " . $details['group'] . ", active = " . $details['active'] . ", short_label = \"" . $details['short_label'] . "\" WHERE uid = " . $details['uid'];
		// $values = array(":label" => $details['label'], ":group" => $details['group'], ":uid" => $details['uid'], ":active" => $details['active']);
		$statement = $db->prepare($query);
		$statement->execute();

		# update port lock status
		$query = "SELECT * FROM vr_input_lock WHERE port_uid = " . $port;
		$statement = $db->prepare($query);
		$statement->execute();

		$isLocked = $statement->fetch(PDO::FETCH_OBJ);

		if(!empty($isLocked))
		{
			if(!isset($details['locked']))
			{
				# unlock port
				$query = "DELETE FROM vr_input_lock WHERE port_uid = " . $port;
				$statement = $db->prepare($query);
				$statement->execute();
			}
		}
		else
		{
			if(isset($details['locked']))
			{
				# lock port
				$query = "INSERT INTO vr_input_lock (port_uid, username, created) VALUES (" . $port. ", 'cms', " . time() . ")";
				$statement = $db->prepare($query);
				$statement->execute();
			}	
		}
	}
} 
else if(isset($arrayForQueries['output']))
{
	foreach($arrayForQueries['output'] as $port => $details)
	{
		# check if port has been disabled from CMS
		if(!isset($details['active']))
			$details['active'] = 0;
		else
			$details['active'] = 1;
		$query = "UPDATE vr_label SET name = \"" . $details['label'] . "\", active = " . $details['active'] . ", short_label = \"" . $details['short_label'] . "\" WHERE uid = " . $details['uid'];
		$statement = $db->prepare($query);
		$statement->execute();

		# get port uid
		$query = "SELECT port_uid FROM vr_label WHERE uid = " . $details['uid'];
		$statement = $db->prepare($query);
		$statement->execute();
		$portUid = $statement->fetch(PDO::FETCH_OBJ);
		$portUid = $portUid->port_uid;

		$query = "UPDATE vr_output SET floor_uid = \"" . $details['floor'] . "\" WHERE port_uid = " . $portUid;
		$statement = $db->prepare($query);
		$statement->execute();

	}
}
header("Location: " . $config['http_base_url']);