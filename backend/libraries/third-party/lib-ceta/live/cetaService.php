<?php

class CetaService extends Ceta {

    function fetchServiceDetails($cetacode) {

        $cdata = array('Object' => 'service',
                       'SearchField' => 'cetacode',
                       'Criteria' => $cetacode,
                       'Options' => '');
    
	$ares = json_decode($this->cetaRead($cdata), TRUE);

	foreach ($ares['data'] as $service) {
	    if ($service['cetacode'] == $cetacode) {
	        return array('CetaCode' => $service['cetacode'],
		             'Description' => $service['description']);
	    }
	}
    }

    function fetchUserHoliday($username, $startdate, $enddate) {
        
	$userresource = $this->fetchResourceByUsername($username);

	$urlparts = array();
	$urlparts[] = 'http://' . $this->cetaServer();
	$urlparts[] = $this->webservicepath;
	$urlparts[] = 'jread';
	$urlparts[] = 'dateresourceschedule';
	$urlparts[] = $userresource['resourceID'];
	$urlparts[] = $startdate;
	$urlparts[] = $enddate;

	$this->url = implode("/", $urlparts);
	$jobs = json_decode(file_get_contents($this->url), TRUE);

	foreach ($jobs['data'] as $job) {
	    if ($job['fd_status'] == 'Holiday') {
	        printf("%s %s %s\n", $job['projectID'], $job['fd_status'], $job['theStartTime']);
	    }
	}

	return;

    }

    function fetchUserSchedule($username, $startdate, $enddate) {

	$userresource = $this->fetchResourceByUsername($username);

	$urlparts = array();
	$urlparts[] = 'http://' . $this->cetaServer();
	$urlparts[] = $this->webservicepath;
	$urlparts[] = 'jread';
	$urlparts[] = 'dateresourceschedule';
	$urlparts[] = $userresource['resourceID'];
	$urlparts[] = $startdate;
	$urlparts[] = $enddate;

	$this->url = implode("/", $urlparts);
	$jobs = json_decode(file_get_contents($this->url), TRUE);

	$return = array();

	$cetajob = new cetaJob();
	foreach ($jobs['data'] as $job) {
	    if ($job['projectID'] == 0) {		// This skips holidays. Maybe add in later?
	        continue;
	    }
	    $room = '';
	    $jobdetails = $cetajob->fetchJobDetailsByJobId($job['jobID']);
	    foreach ($jobdetails['schedule'] as $schedule) {
	        if ($schedule['resourcecategory'] == 'Room') {
		    $room = $schedule['resourcename'];
		}
	    }

	    $return[] = array('job' => $jobdetails['productname'].': '.$jobdetails['title1'],
	                      'jobnumber' => 'J'.$jobdetails['projectnumber'],
			      'product' => $jobdetails['productname'],
			      'name' => $jobdetails['title1'],
			      'service' => $job['services'],
			      'status' => $jobdetails['fd_status'],
			      'ws' => $jobdetails['jobID'],
			      'sdate' => $job['theStartTime'],
			      'start' => $job['starttime'],
			      'end' => $job['endtime'],
			      'notes' => $jobdetails['notes1'],
			      'client' => $jobdetails['companyname'],
			      'contact' => $jobdetails['contactname'],
			      'room' => $room,
			      'producer' => $jobdetails['ourcontact']);

	}

	return $return;

    }

    function fetchResourceByUsername($username) {
	$cdata = array('Object' => 'resource',
	               'SearchField' => 'username',
		       'Criteria' => $username);
	$ares = json_decode($this->cetaRead($cdata), TRUE);
	return $ares[0];
    }

    function fetchServicesToComplete($username) {

	$this->options = array('direction' => 'asc',
	                       'order_by' => 'startdate');

        $cdata = array('Object' => 'servicestocomplete',
                       'SearchField' => 'username',
                       'Criteria' => $username);
    
	$ares = json_decode($this->cetaRead($cdata), TRUE);

	return $ares;

    }

    function filterDMINF($services) {		// Associate DMINF with the master booking where available

        $dms = array();
	$filteredservices = array();

	foreach ($services['data'] as $service) {
	    if ($service['service'] == 'DMINF' || $service['service'] == 'DMS' || $service['service'] == 'HDMSU' || $service['service'] == 'HDMS') {
	        $dms[$service['resourcescheduleID']] = array('RSSID' => $service['resourcescheduleserviceID'],
		                                             'RSID' => $service['resourcescheduleID'],
							     'Display' => 1);
	    }
	}

	foreach ($services['data'] as $key => $service) {
	    if (isset($dms[$service['resourcescheduleID']])) {
	        $services['data'][$key]['dminfid'] = $dms[$service['resourcescheduleID']]['RSSID'];
		$dms[$service['resourcescheduleID']]['Display'] = 0;
	    } else {
	        $services['data'][$key]['dminfid'] = 0;
	    }
	}

	foreach ($services['data'] as $key => $service){
	    if ($service['service'] == 'DMINF' || $service['service'] == 'DMS' || $service['service'] == 'HDMSU' || $service['service'] == 'HDMS') {
	        if ($dms[$service['resourcescheduleID']]['Display'] == 0) {
		    continue;
		}
	    }
	    $filteredservices['data'][] = $service;
	}
	return $filteredservices;

    }
}

?>
