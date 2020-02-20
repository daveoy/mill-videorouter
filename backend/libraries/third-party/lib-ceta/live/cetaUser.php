<?php

class CetaUser extends Ceta {

    function fetchDetailsByUsername($username) {

        $cdata = array('Object' => 'user',
                       'SearchField' => 'username',
                       'Criteria' => $username);

        $jsonres = $this->cetaRead($cdata);
	$ares = json_decode($jsonres, True);

	$user = array('Cuid' => $ares[0]['cetauserID'],
	              'Username' => $ares[0]['username'],
	              'Fullname' => $ares[0]['fullname'],
	              'Initials' => $ares[0]['initials'],
	              'Department' => $ares[0]['department'],
	              'Location' => $ares[0]['location']);

        return $user;
    }   

    function fetchOpsByDept($department, $location=false) {

        $cdata = array('Object' => 'user',
	               'SearchField' => 'department',
		       'Criteria' => $department);

        $jsonres = $this->cetaRead($cdata);
	$ares = json_decode($jsonres, True);

	if ($location == false) {
	    return $ares;
	}

	$return = array();

	foreach ($ares as $user) {
	    if ($user['location'] == $location) {
	        $return[] = $user;
	    }
	}

	return $return;

    }

    function fetchResourceByCategory($name, $category) {

        $res = $this->sqlRead("SELECT * FROM resource WHERE categorycode = '$category' AND name = '" . str_replace("'", "''", $name) . "' AND fd_status = 'Available'");

	if (mysql_num_rows($res) == 0) {
	    return array("error" => 1,
	                 "message" => 'Ceta: No current worksheets found');
	}

	$row = mysql_fetch_assoc($res);

	$resource = array('resourceID' => $row['resourceID'],
	                  'Category' => $row['category'],
	                  'Name' => $row['name'],
	                  'Location' => $row['location'],
	                  'Email' => $row['email']
			  );

        return $resource;
    }

}

?>
