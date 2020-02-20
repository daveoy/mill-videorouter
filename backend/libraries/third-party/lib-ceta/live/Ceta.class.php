<?php

include 'cetaMedia.php';
include 'cetaProject.php';
include 'cetaJob.php';
include 'cetaQuote.php';
include 'cetaUser.php';
include 'cetaService.php';
include 'cetaCompany.php';
include 'cetaCalendar.php';
include 'cetaFilemailer.php';

class Ceta {

    function __construct() {

        $this->baseurl = 'icfm-ldn.mill.co.uk';
		$this->webservicepath = 'psyapi/index.php';
		$this->calltype = 'jread';
		$this->url = '';

		$this->db = 'cetasoft2_mill';
		$this->dbrouser = 'icfm-ro';
		$this->dbropasswd = 'yano62lubi';

		$this->depots = array('UK' => array('cetaServer' => 'icfm-ldn.mill.co.uk'),
		                      'NY' => array('cetaServer' => 'icfm-ny.mill-ny.com'),
		                      'LA' => array('cetaServer' => 'icfm-la.mill-la.com'),
		                      'SG' => array('cetaServer' => 'icfm-ldn.mill.co.uk'));
		$this->depot = 'UK';

		$this->locations = array(1 => 'UK',
		                         2 => 'NY',
								 3 => 'LA',
								 4 => 'SG');
		$this->location = 1;
	                        
		$this->options = array();

    }

    function callCeta($cdata) {

		$urlparts = array();
		$urlparts[] = 'http://' . $this->cetaServer();
		$urlparts[] = $this->webservicepath;
		$urlparts[] = $this->calltype;
		$urlparts[] = $cdata['Object'];
		$urlparts[] = $cdata['SearchField'];
		$urlparts[] = rawurlencode($cdata['Criteria']);
		$urlparts[] = urlencode(json_encode($this->options));

		$this->url = implode("/", $urlparts);
		$jsonres = file_get_contents($this->url);

		return $jsonres;

    }

    function cetaRead($cdata) {
        $this->calltype = 'jread';
		return $this->callCeta($cdata);
    }

    function cetaInsert($cdata) {			// Write directly into a table

		$urlparts = array();
		$urlparts[] = 'http://' . $this->cetaServer();
		$urlparts[] = $this->webservicepath;
		$urlparts[] = 'data/write';

		$this->url = implode("/", $urlparts);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "jsonData=$cdata");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close ($ch);

		return $result;
    }

    function cetaServer($depot=false) {
		if ($depot) {
		    $this->depot = $depot;
		}
		return $this->depots[$this->depot]['cetaServer'];
    }

    function cetaLocation($location=false) {
		if ($location) {
		    $this->location = $location;
		}
		$this->cetaServer($this->locations[$this->location]);
		return $this->location;
    }

    function toCetaProjectNumber($num) {
		if (preg_match('/^J(\d+)/i', $num, $matches)) {
			return $matches[1];
		} else {
			return $num;
		}
    }

    function setLimit($o) {
        $this->options['limit'] = $o;
    }

    function setOrderBy($o) {
        $this->options['order_by'] = $o;
    }

    function setDirection($o) {
        $this->options['direction'] = $o;
    }

    function setOffset($o) {
        $this->options['offset'] = $o;
    }

    function sqlRead($sql) {
        $db = mysql_connect($this->cetaServer(), $this->dbrouser, $this->dbropasswd)
	    	or die("Could not connect : " . mysql_error());
        mysql_select_db($this->db);
		return mysql_query($sql);
    }

}

?>
