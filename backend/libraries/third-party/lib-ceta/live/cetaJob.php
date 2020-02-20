<?php

class CetaJob extends Ceta {

    function fetchJobsByProjectId($id) {

        $cdata = array('Object' => 'job',
                       'SearchField' => 'projectID',
                       'Criteria' => $id,
                       'Options' => '');

        $ares = json_decode($this->cetaRead($cdata), TRUE);

	$jobs = array();
	$schedules = array();

	foreach ($ares as $job) {

	    $schedules = array();
	    $rsschedules = array();

	    foreach ($job['schedule'] as $s) {
	        $schedules[] = array('RssId' => $s['resourcescheduleID'],
		                     'RId' => $s['resourceID'],
		                     'Category' => $s['resourcecategory'],
		                     'Resourcename' => $s['resourcename'],
		                     'StartTime' => $s['starttime'],
		                     'EndTime' => $s['endtime'],
				     'FdStatus' => $s['fd_status']);
	    }

	    foreach ($job['resource_schedule_service'] as $rss) {
	        $rsschedules[] = array('RssId' => $rss['resourcescheduleID'],
		                       'Service' => $rss['service'],
				       'FdStatus' => $rss['fd_status']);
	    }

	    $jobs[] = array('JobID' => $job['jobID'],
	                    'Depot' => $job['depot'],
	                    'StartTime' => $job['startdate'],
	                    'EndTime' => $job['enddate'],
	                    'FdStatus' => $job['fd_status'],
			    'Schedule' => $schedules,
			    'ResourceSchedule' => $rsschedules);
	    
	}

	return $jobs;
    }

    function fetchJobDetailsByJobId($id) {

        $cdata = array('Object' => 'job',
	               'SearchField' => 'jobID',
	               'Criteria' => $id,
	               'Options' => '');

	$ares = json_decode($this->cetaRead($cdata), TRUE);

	if (isset($ares['warning'])) {
	    return $ares;
	} else {
	    return $ares[0];
	}
    }

    function fetchJobsByRateCardId($jnumber, $rcIds) {

        $cetaproject = new CetaProject();
        $projectdetails = $cetaproject->fetchProjectIdByJnum($jnumber);

        $rcitems = array();
        $cetaquote = new cetaQuote();

        foreach ($rcIds as $rcid) {
            $res = $cetaquote->fetchRateCardByCode($rcid);
            $rcitems[$rcid] = $res['description'];
        }

        $options = array('resource_schedule_service' => TRUE);
        $res = $this->fetchJobsByProjectId($projectdetails['projectID'], $options);

        $jobs = array();

        foreach ($res as $job) {
            $booking = array('JobId' => $job['JobID'],
                             'StartTime' => '2099-01-01 00:00:00',
                             'EndTime' => 0,
                             'People' => array(),
                             'Room' => '', 
                             'Equipment' => array(),
                             'RcItems' => array(),
                             'RcCount' => 0); 

            foreach ($job['ResourceSchedule'] as $rsched) {
	        if (array_key_exists($rsched['Service'], $rcitems)) {
                    $booking['RcItems'][] = $rcitems[$rsched['Service']];
                    $booking['RcCount']++;
                }
            }

            if ($booking['RcCount'] > 0) {
                foreach($job['Schedule'] as $sched) {

                    if ($booking['StartTime'] > $sched['StartTime']) {
                        $booking['StartTime'] = $sched['StartTime'];
                    }

                    if ($booking['EndTime'] < $sched['EndTime']) {
                        $booking['EndTime'] = $sched['EndTime'];
                    }

                    if (array_key_exists($sched['Category'], $booking)) {
                        $booking[$sched['Category']][] = $sched['Resourcename'];
                    }
                }

                $jobs[] = $booking;
            }
        }
	return $jobs;
    }   


}
?>
