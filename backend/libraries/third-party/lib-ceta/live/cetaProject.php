<?php

class CetaProject extends Ceta {

    function fetchProjectIdByJnum($jnumber) {

	if (preg_match("/(\d+)/", $jnumber, $match)) {
	    $jnumber = $match[0];
	}

        $cdata = array('Object' => 'project',
                       'SearchField' => 'projectnumber',
                       'Criteria' => $jnumber,
                       'Options' => '');

        $ares = json_decode($this->cetaRead($cdata), TRUE);

	if (isset($ares['warning'])) {
	    return $ares;
	} else {
	    return $ares[0];
	}
    }   

    function fetchProjectIdByCetaId($id) {
        $cdata = array('Object' => 'project',
	               'SearchField' => 'projectID',
		       'Criteria' => $id);

        $jsonres = $this->cetaRead($cdata);
	return json_decode($jsonres, TRUE);
    }

    function fetchLastBookingDateByCetaid($id) {
        $res = $this->sqlRead("SELECT MAX(startdate) AS date FROM job WHERE projectid = '$id';");
	$row = mysql_fetch_assoc($res);
	return $row['date'];
    }

}

?>
