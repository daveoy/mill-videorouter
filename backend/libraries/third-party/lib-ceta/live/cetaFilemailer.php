<?php

class CetaFilemailer extends Ceta {

    function billUploads($uploadDetails) {

        $numrecip = 0;
	$millrecip = 0;

	$uploadDetails['operator'] = preg_replace('/(@.*)/', '', $uploadDetails['operator']);

	foreach ($uploadDetails["recipients"] as $recipient) {
	    if (preg_match('/(@the-mill.com|@beam.tv|@themill.com)/', $recipient)) {
	        $millrecip = 1;
	    } else {
	        $numrecip++;
	    }
	}

	if ($millrecip == 1) {
	    $numrecip++;
	}

	// NY billing - they only use FMFIRST
	if (preg_match('/NY$/', $uploadDetails['worksheet'])) {
	    $res = $this->billUpload($uploadDetails, 'FMFIRST', count($uploadDetails['files']));
	    return $res;
	}

	error_log("billUploads: " . $uploadDetails["tapenumber"]);
	if ($uploadDetails["tapenumber"] == 'ELECTRONIC DUB (MIDI MILL BBH)') {
	    $res = $this->billUpload($uploadDetails, 'MMBFMFIRST', 1);
	} else {
	    $res = $this->billUpload($uploadDetails, 'FMFIRST', 1);
	}

	if (isset($res['error'])) {
	    return $res;
	}

	$billingid = $res;

	error_log("LIQ: billingid - $billingid");

	if (count($uploadDetails['files']) > 1) {
	    if ($uploadDetails["tapenumber"] == 'ELECTRONIC DUB (MIDI MILL BBH)') {
	        $res = $this->billUpload($uploadDetails, 'MMBFMADD', (count($uploadDetails['files'])-1));
	    } else {
	        $res = $this->billUpload($uploadDetails, 'FMADD', (count($uploadDetails['files'])-1));
	    }
	    if (isset($res['error'])) {
	        return $res;
	    }
	    $billingid = "$billingid:$res";
	}

	if (preg_match('/(UK|BRAND)$/', $uploadDetails['worksheet'])) {
	    $res = $this->billUpload($uploadDetails, 'DDPR', $numrecip);

	    if (isset($res['error'])) {
	        return $res;
	    }

	    $billingid = "$billingid:$res";

	    error_log("LIQ (DDPR - $numrecip): billingid - $billingid");

	}

	return $billingid;

    }

    function billUpload($uploadDetails, $costcode, $quantity) {

        if ($costcode == 'FMFIRST' || $costcode == 'MMBFMFIRST') {
	    $description = "Filemailer first encode: " . str_replace('&', '', $uploadDetails['title']);
	}

	if ($costcode == 'FMADD' || $costcode == 'MMBFMADD') {
	    $description = "Filemailer additional encodes: " . str_replace('&', '',$uploadDetails['title']);
	}

	if ($costcode == 'DDPR') {
	    $description = "Digital delivery per recipient: " . str_replace('&', '', $uploadDetails['title']);
	}

        $data = array('table_name' => 'jobdetail',
	              'id_name' => 'jobdetailID',
		      'jobID' => $uploadDetails['worksheet'],
		      'copytype' => "D",
		      'description' => $description,
		      'costcode' => $costcode,
		      'quantity' => $quantity,
		      'fd_orderby' => 1,
		      'completeddate' => date('Y-m-d H:i:s'),
		      'completeduser' => $uploadDetails['operator'],
		      'comment' => "Uploaded on: ". date('Y-m-d'),
		      'emailto' => join(",", $uploadDetails["recipients"]),
		      'format' => "MMF");

	if ($uploadDetails['billable'] != 1) {
	    $data['nocharge'] = 1;
	    $data['nochargereason'] = $uploadDetails['nobillreason'];
	    $data['nochargedate'] = date('Y-m-d H:i:s');
	    // $data['nochargeuserID'] = 'PC';				// DONT HAVE THE ID AS NOT ALL ACCOUNTS HAVE LOGINS
	}

        if (preg_match("/\d([A-Z]+)$/", $uploadDetails['worksheet'], $matches)) {
            $wssuffix = $matches[1];
        } else {
            $wssuffix = 'UK';
        }

        if (isset($this->depots[$wssuffix])) {
            $this->cetaServer($wssuffix);
        }

	$jdata = rawurlencode(json_encode($data));
	$res = json_decode($this->cetaInsert($jdata), TRUE);

	if ($res['count'] == 1) {
	    return $res['data']['jobdetailID'];
	}

	return array('error' => 1,
	             'message' => 'Error billing filemailer upload');
    }

    function getJobsByProject($projectnumber) {

        $cetaproject = new CetaProject();
        $cetajob = new CetaJob();

	$cetaproject->cetaLocation($this->cetaLocation());
	$cetajob->cetaLocation($this->cetaLocation());

        $project = $cetaproject->fetchProjectIdByJnum($projectnumber);

        if (array_key_exists('warning', $project)) {
	    return array("error" => 1,
	                 "message" => 'Ceta message: ' . $project['warning']);
        }

        $latestjobs = array("jobnumber" => $project['projectnumber'],
                            "jobtitle" => $project['title'],
                            "jobproduct" => $project['productname'],
                            "worksheets" => array());

        $alljobs = $cetajob->fetchJobsByProjectId($project['projectID']);

        $now = date('Y-m-d');
        $yesterday = date('Y-m-d', mktime()-86400);

        foreach ($alljobs as $job) {
            if (((substr($job['StartTime'], 0, 10) == $yesterday) || (substr($job['StartTime'], 0, 10) == $now)) && (($job['FdStatus'] == 'Confirmed' || $job['FdStatus'] == 'Completed'))){
                $latestjobs['worksheets'][] = array('wsnumber' => $job['JobID'] . $job['Depot'],
                                                    'wsdate' => substr($job['StartTime'], 0, 10),
                                                    'quoteno' => 'unknown');
            }
        }

	if (count($latestjobs['worksheets']) == 0) {
	    return array("error" => 1,
	                 "message" => 'Ceta: No current worksheets found');
	} else {
            return $latestjobs;
	}

    }

    function getFilmailerJobs($projectnumber) {

        $cetaproject = new CetaProject();
	$cetaproject->cetaLocation($this->cetaLocation());

        $res = $cetaproject->fetchProjectIdByJnum($projectnumber);

        if (array_key_exists('warning', $project)) {
	    return array("error" => 1,
	                 "message" => 'Ceta message: ' . $project['warning']);
        }

        $return = array('jobnumber' => $res['projectnumber'],
                        'jobtitle' => $res['title'],
                        'jobproduct' => $res['productname'],
                        'worksheets' => array());

        $sql = "SELECT jobid, jobtype, fd_status, startdate, enddate, depot FROM job "
             . "WHERE startdate BETWEEN DATE_ADD(current_date, INTERVAL -1 DAY ) AND DATE_ADD(current_date, INTERVAL 1 DAY ) "
             . "AND fd_status in ('Confirmed','Completed') AND jobtype = 'Production' AND projectid = '" . $res['projectID'] . "'" ;

        $res = $cetaproject->sqlRead($sql);

	if (mysql_num_rows($res) == 0) {
	    return array("error" => 1,
	                 "message" => 'Ceta: No current worksheets found');
	}

        while($row = mysql_fetch_assoc($res)) {
            $return['worksheets'][] = array('wsnumber' => $row['jobid'].$row['depot'],
                                            'wsdate' => substr($row['startdate'], 0, 10),
                                            'quoteno' => 'unknown');
        }   

	return $return;

    }


    function getDetailsByJob($job) {
        
	$validtapetype = explode(' ', 'UML UMH D1S D1L BDS BDL BAS BAL DAT VHS CDR DVC D5L D5S HDS HDL H5S SRH DVD LAS LT0 DIR');

	$cetajob = new CetaJob();
	$cetajob->cetaLocation($this->cetaLocation());

	$job = $cetajob->fetchJobDetailsByJobId($job);

	if (isset($job['warning'])) {
	    return array('error' => 1,
	                 'message' => 'Ceta message: ' . $job['warning']);
	}
 
	if ($job['fd_status'] == 'Costed') {
	    return array('error' => 1,
	                 'message' => 'Ceta message: cannot bill to this job, already costed');

	}

	$cetauser = new cetauser();
	$cetauser->cetaLocation($this->cetaLocation());
	$res = $cetauser->fetchResourceByCategory($job['ourcontact'], '/producer');

	if (isset($res['error'])) {
	    $producer = array('fullname' => 'Unknown',
	                      'email' => 'Unknown');
	} else {
	    $producer = array('fullname' => $res['Name'],
	                      'email' => $res['Email']);
	}

	$cetamedia = new CetaMedia();
	$cetajob->cetaLocation($this->cetaLocation());

	$tapes = $cetamedia->fetchMediaByJnum($job['projectnumber']);

	$res = array('worksheetnumber' => $job['jobID'] . $job['depot'],
		     'jobnumber' => 'J'.$job['projectnumber'],
		     'jobtitle' => $job['title1'],
		     'jobproduct' => $job['productname'],
		     'producer' => $producer,
		     'tapes' => array(
		                    array('tapenumber' => 'Electronic Dub',
				          'tapedesc' => 'Electronic Dub'),
				    array('tapenumber' => 'Electronic Dub (Midi Mill BBH)',
				          'tapedesc' => 'Electronic Dub (Midi Mill BBH)'
				)));

        foreach ($tapes as $tape) {
            if (in_array(substr($tape['TapeNumber'], 0, 3), $validtapetype)) {
	        $res['tapes'][] = array('tapenumber' => $tape['TapeNumber'],
	                                'tapedesc' => $tape['TapeName']);
    	    }
        }

        return $res;

    }

}

?>
