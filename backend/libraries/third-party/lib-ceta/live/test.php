<?php
require_once 'Ceta.class.php';
require_once 'cetaLdap.php';

$cmd = 'fetchMembersofGroup';
//$cmd = 'fetchJobDetailsByJobId';

//echo "Running: $cmd\n";

// LOCATIONS //
if ($cmd == 'locations') {

    $worksheet = '393781BRAND';
    if (preg_match("/\d([A-Z]+)$/", $worksheet, $matches)) {
        $wssuffix = $matches[1];
    } else {
        $wssuffix = 'UK';
    }

    $ceta = new CetaFilemailer();

    if (isset($ceta->depots[$wssuffix])) {
        $ceta->cetaServer($wssuffix);
    }

    echo "Server: " . $ceta->cetaServer() . "\n";
    exit;
}

// LDAP //
if ($cmd == 'fetchMembersofGroup') {
    echo "fetchMembersofGroup\n";
    $cetaldap = new cetaLdap();
    $users = $cetaldap->fetchMembersofGroup('Mill TV 2d', 'Clipstone Street', 1);
    print_r($users);

    exit;

    $now = time();

    foreach ($users as $k => $v) {
        if (!isset($v['memberof'])) {
	    continue;
	}

	echo $v['samaccountname'][0] . "\n";

	/*
	if (in_array($v['useraccountcontrol'][0], array(512, 66048))) {
	    echo "\tuseraccountcontrol: OK\n";
	} else {
	    echo "\tuseraccountcontrol: DISABLED\n";
	}

        $expdate = convertToUnix($v['accountexpires'][0]);

	if ($expdate == 0) {
	    echo "\tPermanent\n";
	} else {
	    echo date("\td/m/Y\n", $expdate);
	    if ($now > $expdate) {
	        echo "\tDISABLED\n";
	    } else {
	        echo "\tACTIVE\n";
	    }
	}
	*/

	echo "\t" . accountStatus($v) . "\n";

    }

//    print_r($users);
    exit;
}

function accountStatus($user) {

    // 0 - Disabled || 1 - Active, Permanent || 2 - Active, Temporary 

    $now = time();
    if (!in_array($user['useraccountcontrol'][0], array(512, 66048))) {
        return 0;
    }

    $expdate = convertToUnix($user['accountexpires'][0]);
    if ($expdate == 0) {
        return 1;
    }

    if ($expdate > time()) {
        return 2;
    }

    return 0;
}

function convertToUnix($win_time) {   
    if ($win_time == 9223372036854775807 || $win_time == 0) {   
        return 0;
    } else {   
        return (int)(($win_time - 116444736000000000)/10000000);
    }   
} 

// SQL //
if ($cmd == 'cetaSQL') {
    $ceta = new Ceta();
    $sql = "SELECT quoteID, productID, projectnumber, title, subtitle FROM quote WHERE cuser = 'chrisb' AND fd_status = 'Confirmed' ORDER BY quoteID DESC";
    $res = $ceta->sqlRead($sql);
    $quotes = array();

    $cetaquote = new cetaQuote();

    while ($row = mysql_fetch_assoc($res)) {
        
	$details = $cetaquote->fetchQuoteDetail($row['quoteID']);
//	print_r($details[0]);

	$total = 0;
	foreach($details[0]['details'] as $line) {
	    $total += $line['quotedtotal'];
	}

	$quotes[] = array('Id' => $row['quoteID'],
	                  'Jnum' => 'J'.$row['projectnumber'],
			  'Product' => $details[0]['productname'],
			  'Title' => "",
			  'QuoteNo' => $row['quoteID'],
			  'QuoteTitle' => $details[0]['title'],
			  'QuoteTotal' => $total
			  );
    }
    print_r($quotes);
    exit;
}

if ($cmd == 'findFinished') {

    $ceta = new Ceta();
    $sql = "SELECT pj.projectID, pj.projectnumber, pj.title, pd.name from project pj, product pd WHERE pj.productID = pd.productID AND pj.projectID IN (SELECT projectID FROM quote WHERE billed = 1) AND pj.allocation = 'LD Project'";
    $res = $ceta->sqlRead($sql);

    $alljobs = array();

    while ($row = mysql_fetch_assoc($res)) {

       if ($row['projectnumber']>9000000){
           $row['projectnumber'] = $row['projectnumber']-9000000;
       }
       if ($row['projectnumber']>900000){
           $row['projectnumber'] = $row['projectnumber']-900000;
       }

       $alljobs[$row['projectID']] = array('archivable' => 'yes',
                                           'projectnumber' => $row['projectnumber'],
					   'title' => $row['title'],
					   'product' => $row['name']);
    }

    print_r($alljobs);

    exit;
}

// SCHEDULED //
if ($cmd == 'fetchopschedule') {
    $cetaservice = new CetaService();
    $res = $cetaservice->fetchUserSchedule('alarsen', "2012-07-23", "2012-08-29");
    echo "<pre>" . print_r($res, TRUE);
    exit;
}

if ($cmd == 'fetchUserHoliday') {
    $cetaservice = new CetaService();
    $res = $cetaservice->fetchUserHoliday('bens', "2011-06-01", "2012-06-01");
    exit;
}

// COMPANY //
if ($cmd == 'fetchCompanyById') {

    $cetacompany = new CetaCompany();
    $details = $cetacompany->fetchCompanyById(1910002058);

    print_r($details);
    exit;
}

// QUOTE //
if ($cmd == 'requiresPPM') {

    $cetaquotes = new cetaQuote();
    $result = $cetaquotes->ppmRequired($_GET['jnum'], TRUE);
    echo "<pre>"; print_r($result);
    exit;

    foreach($result as $k => $quote) {
	if ($quote['PPM'] == 1) {
            printf("%s, %s, %s - !!! PPM REQUIRED !!!\n", $quote['QuoteId'], $quote['Title'], $quote['Subtitle']);
	} else {
            printf("%s, %s, %s\n", $quote['QuoteId'], $quote['Title'], $quote['Subtitle']);
	}
	foreach($quote['Items'] as $category=> $item) {
	    if ($item['days'] > 0) {
		echo "\t$category => " . $item['days'];
	        if ($item['days'] > $item['trigger']) {
		    echo " - *";
		}
		echo "\n";
	    }
	}
    }

    exit;

}

if ($cmd == 'fetchQuotesFromJnumber') {
     $cetaquotes = new cetaQuote();

     $quotes = $cetaquotes->fetchLiveQuotesFromJnumber('J100291');
     print_r($quotes);

     exit;
}

if ($cmd == 'fetchQuoteDetail') {

    $username = 'mattw';

    $cetauser = new cetaUser();
    $userdetails = $cetauser->fetchDetailsByUsername($username);

    $cetaquotes = new cetaQuote();
    $quotedetail = $cetaquotes->fetchQuoteDetail('48458');
    if ($quotedetail[0]['ourcontact'] == $userdetails['Fullname']) {
        echo "FOUND ONE!\n";
        
    }
    exit;

}

if ($cmd == 'fetchRatecardByCategory') {

   $cetaquotes = new cetaQuote();
   $rcitems = $cetaquotes->fetchRatecardByCategory('Digital Interactive');
   print_r($rcitems);
   exit;
}

// PROJECT //
if ($cmd == 'fetchProjectIdByJnum') {
    $cetaproject = new CetaProject();
    $res = $cetaproject->fetchProjectIdByJnum('100291');
    print_r($res);
    exit;
}

if ($cmd == 'fetchProjectIdByCetaId') {
    $cetaproject = new CetaProject();
    $res = $cetaproject->fetchProjectIdByCetaId(1860000659);
    echo $cetaproject->url;
    print_r($res);
    exit;
}

// CALENDAR //

if ($cmd == 'fetchProjectCalendar') {

    $categories = array('ganttGreen', 'ganttOrange', 'ganttRed');
    $ci = 0;

    $cetacalendar = new CetaCalendar();
    $res = $cetacalendar->fetchProjectCalendar(95600);

//    print_r($res); exit;

    $schedule = array();

    foreach ($res['schedule'] as $event) {

	if (!array_key_exists($event['category'], $schedule)) {
	    if ($ci >= count($categories)) {
	        $ci = 0;
	    }

	    $schedule[$event['category']] = array('Category' => $event['category'],
	                                          'CategoryColour' => $categories[$ci],
						  'Dates' => array());
	    $ci++;
	}

	$schedule[$event['category']]['Dates'][$event['date']]['Services'][] = array('Service' => $event['service'],
										     'Date' => $event['date'],
	                                                                             'Description' => $event['description'],
										     'Depot' => $event['depot'],
										     'Count' => $event['count'],
										     'Duration' => $event['total_duration']);
    }

    $ganttarray = array();

    foreach ($schedule as $category) {

	$events = array();
	foreach ($category['Dates'] as $date) {

	    $event = array();
	    foreach ($date['Services'] as $service) {

	        if (array_key_exists('from', $event)) {
	            $event['desc'] = $event['desc'] . '<br>' . $service['Description'] . '(' . $service['Service'] . ')';
	        } else {
	            $event = array('from' => '/Date(' . strtotime($service['Date']) . '000)/',
		                   'to'   => '/Date(' . strtotime($service['Date']) . '000)/',
			           'customClass'   => $category['CategoryColour'],
			           'desc' => $service['Description'] . '(' . $service['Service'] . ')');
	        }
	    }

	    $events[] = $event;
	}

	$ganttarray[] = array('name' => $category['Category'],
	                      'desc' => '',
			      'values' => $events);

    }

//    print_r($ganttarray); exit;

    header("Content-type: application/json");
    echo json_encode($ganttarray);
    exit;

}

// LOOP //
if ($cmd == 'fetchLoopCalendar') {

    $cetaproject = new CetaProject();
    $cetajob = new CetaJob();
    $options = array('resource_schedule_service' => TRUE);
    $res = $cetajob->fetchJobsByProjectId(1860000660, $options);
    print_r($res);
    exit;

}

// JOB //
if ($cmd == 'fetchJobsByProjectId') {
    $cetajob = new CetaJob();
    $options = array('resource_schedule_service' => TRUE);
    $res = $cetajob->fetchJobsByProjectId(99211, $options);
    print_r($res);
}

if ($cmd == 'fetchJobsByRateCardId') {

    $projectId = 99211;
    $jnumber = 'J81603';
    $rccolourids = explode(' ', 'DKGR DKFS DKPREP DKDATAPREP DKTR DKES');

    $cetajob = new cetaJob();
    $res = $cetajob->fetchJobsByRateCardId($jnumber, $rccolourids);
    print_r($res);
    exit;

    $cetaproject = new CetaProject();
    $projectdetails = $cetaproject->fetchProjectIdByJnum('J81603');

    $rcitems = array();
    $cetaquote = new cetaQuote();

    foreach ($rccolourids as $rcid) {
	$res = $cetaquote->fetchRateCardByCode($rcid);
	$rcitems[$rcid] = $res['description'];
    }

    $cetajob = new CetaJob();
    $options = array('resource_schedule_service' => TRUE);
    $res = $cetajob->fetchJobsByProjectId($projectdetails['projectID'], $options);

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
	    if (in_array($rsched['Service'], $rccolourids)) {
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

    print_r($jobs);
}

if ($cmd == 'emailIncompleteWS') {

    return;
    $location = 1;
    $jobsubmiturl = array(1 => 'http://icfm-ldn.mill.co.uk/mill/ceta/jobsubmission/displayjobs.php?u=',
                          2 => 'http://icfm-ny.mill-ny.com/mill/ceta/jobsubmission/displayjobs.php?u=',
			  3 => 'http://icfm-la.mill-la.com/mill/ceta/jobsubmission/displayjobs.php?u=',
			  4 => 'http://icfm-ldn.mill.co.uk/mill/ceta/jobsubmission/displayjobs.php?u=');

    $from = "Scheduling - London<bookings@themill.com>";
    $subject = "Ceta: You have incomplete jobs";
    $message = "You have the following incomplete jobs:\n\n";
    $headers = "From: $from\r\nReply-To: bookings\@themill.com\r\n";
    $footer = "\nClick on the link below to complete:\n";

    $replyto = "Reply-to: donotreply\@themill.com\n";
              
    $servicedetails = array();

    $cetaldap = new cetaLdap();
    $users = $cetaldap->fetchMembersofGroup('Telecine', 'London');

    $cetaservice = new cetaService();

    echo "<pre>";

    foreach ($users as $username) {

	$body = $message;
	$to = 'pec@themill.com';
        $tocomplete = $cetaservice->fetchServicesToComplete($username);
        $tocomplete = $cetaservice->filterDMINF($tocomplete);

	foreach ($tocomplete['data'] as $service) {

	    if (! isset($servicedetails[$service['service']])) {
	        $servicedetails[$service['service']] = $cetaservice->fetchServiceDetails($service['service']);
	    }

	    $body .= sprintf("%s - %s, J%s (%s), %s-%s\n\r\n", $service['productname'], $service['title'], $service['projectnumber'], $servicedetails[$service['service']]['Description'], date('G:i', strtotime($service['startdate'])), date('G:i D j/m/Y', strtotime($service['enddate'])));
	}

	$body .= $footer . $jobsubmiturl[$location] . $username;
	if ($username == 'seamus') {
	    echo "Username: $username\n" . $body;
//	    $mail_sent = @mail($to, $subject, $body, $headers );
	}
    }

    exit;
}

if ($cmd == 'fetchLatestJobByProject') {		// JS4D equivalent: getLatestWsByJobNo

    $pnumber = 200116;

    $cetaproject = new CetaProject();
    $cetajob = new CetaJob();

    $project = $cetaproject->fetchProjectIdByJnum($pnumber);

    if (array_key_exists('warning', $project)) {
        echo $project['warning']; exit;
	// return $project['warning'];
    }

    $latestjobs = array("jobnumber" => $project[0]['projectnumber'],
                        "jobtitle" => $project[0]['title'],
		        "jobproduct" => $project[0]['productname'],
		        "worksheets" => array());

    $alljobs = $cetajob->fetchJobsByProjectId($project[0]['projectID'], array());

    $now = date('Y-m-d');
    $yesterday = date('Y-m-d', mktime()-86400);
    // Select last 2 days, Confirmed & Completed only.

    foreach ($alljobs as $job) {
	if ((substr($job['StartTime'], 0, 10) >= $yesterday) && (substr($job['StartTime'], 0, 10) <= $now) && ($job['FdStatus'] == 'Confirmed' || $job['FdStatus'] == 'Completed')){
	    $latestjobs['worksheets'][] = array('wsnumber' => $job['JobID'] . $job['Depot'],
	                                        'wsdate' => substr($job['StartTime'], 0, 10),
					        'quoteno' => 'unknown');
	}
    }

    print_r($latestjobs);
    exit;
}

if ($cmd == 'fetchTapesByJob') {			// JS4D equivalent: getWsInfo

    $jobid = 'J80344';

    $validtapetype = explode(' ', 'UML UMH D1S D1L BDS BDL BAS BAL DAT VHS CDR DVC D5L D5S HDS HDL H5S SRH DVD LAS LT0');

    $cetajob = new CetaJob();
    $job = $cetajob->fetchJobDetailsByJobId($jobid);

    if ($job['warning']) {
        return array('error' => 1,
	             'message' => $job['warning']);
        exit;
    }

    $cetamedia = new CetaMedia();
    $tapes = $cetamedia->fetchMediaByJnum($job['projectnumber']);

    $res = array('error' => 0,
                 'worksheetnumber' => $jobid,
		 'jobnumber' => 'J'.$job['projectnumber'],
		 'jobtitle' => $job['title1'],
		 'jobproduct' => $job['productname'],
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

    print_r($res);
    exit;

}

if ($cmd == 'getJobsByProject') {
    $cetafilemailer = new cetaFilemailer();
    $res =  $cetafilemailer->getJobsByProject('J66288');
    print_r($res);
    exit;
}

if ($cmd == 'addFilemailer') {
    $cetajob = new CetaJob();
    $res = $cetajob->addFilemailer('pec');
    print_r($res);
    exit;
}


if ($cmd == 'fetchJobDetailsByJobId') {
    $cetajob = new CetaJob();
    $res = $cetajob->fetchJobDetailsByJobId(429001);
    print_r($res);
    exit;

    $str = $res['notes1'];
    $str = preg_replace('/[^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?]/', '', $str);
    echo "\n$str\n";

  
    /*
    echo "Before:\n" . $res['notes1'];
    echo "\n\n";
//    echo "After:\n" . win1252toIso($res['notes1']);
    echo "After:\n" . preg_replace('/[^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?]/', '', $res['notes1']);
    */

    exit;
}

// USER //
if ($cmd == 'fetchDetailsByUsername') {
    $cetauser = new cetaUser();
    $res = $cetauser->fetchDetailsByUsername('neilb');
    print_r($res);
    exit;
}

// SERVICES //
if ($cmd == 'fetchServiceDetails') {
    $cetaservice = new cetaService();
    $res = $cetaservice->fetchServiceDetails('INF');
    print_r($res);
    exit;
}

if ($cmd == 'telecineNyToComplete') {

    $username = 'jamesb';
    $cetauser = new cetaUser();

    $users = array();
    $users[] = $username;

    $details = $cetauser->fetchDetailsByUsername($username);

    if ($details['Department'] == 'Telecine' && $details['Location'] == 'NY') {
        $telecine = $cetauser->fetchOpsByDept('Telecine', 'NY');
	foreach($telecine as $user) {
	    if($user['username'] != $username) {
	        $users[] = $user['username'];
	    }
	}
    }

    print_r($users);
    exit;

}

if ($cmd == 'fetchServicesToComplete') {

    $cetaservice = new cetaService();
    $res = $cetaservice->fetchServicesToComplete('benn');
    $res = $cetaservice->filterDMINF($res);

    $incompletews = array();
    $yesterday = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

    foreach ($res['data'] as $k => $service) {
	if ($yesterday > strtotime(substr($service['startdate'], 0, 10))) {
	    $incompletews[] = array('product' => $service['productname'],
	                            'jobName' => $service['title'],
				    'jobNo' => 'J'.$service['projectnumber'],
				    'worksheetDate' => $service['startdate']);
	}
    }


    echo "<pre>" . print_r($incompletews, TRUE);

    exit;

}

// MEDIA //
if ($cmd == 'fetchMediaByBarcode') {
    $cetamedia = new CetaMedia();
//    $res = $cetamedia->getTapesDetails('SRHDS0326670LD');
    $res = $cetamedia->getTapesDetails('SRHDS1000171');
    echo $cetamedia->url . '\n';
    print_r($res);
    exit;
}

if ($cmd == 'createTape') {

    $projectnumber = 'J80109';

    $cetamedia = new CetaMedia();
    $cetamedia->cetaLocation(1);

    $tapes = $cetamedia->fetchMediaByJnum($projectnumber);
    foreach ($tapes as $tape) {
        if (preg_match("/^DIR\d+/", $tape['TapeNumber'])) {
	    $barcode = $tape['TapeNumber'];
	    break;
	}
    }

    $res = $cetamedia->createTape($projectnumber, 'pec');

    if ($res['error']) {
        print "ERROR: " . $res['message'] . "\n";
	exit;
    }

    echo "LibraryId: $res\n";

    $media = $cetamedia->fetchMediaByLibraryId($res);

    print_r($media);

    exit;

}

if ($cmd == 'billBeamUpload') {

$uploadDetails =  array('jobnumber' => 'J100001',
                        'worksheet' => '553221UK',
			'recipients' => array('pec@themill.com', 'pcrisp@gmail.com'),
			'files' => array(
			               array('filename' => 'icons.png',
			                       'url' => 'http://staging.beam.tv/workspace/viewFolderFile/sKJNGsFxkW',
					       'embed' => '<iframe src="http://staging.beam.tv/workspace/viewFile/sKJNGsFxkW?cliponly=true" scrolling="no" width="256" height="240" />',
					       'clipframeurl' => 'http://staging.beam.tv/display_clipframe.php/106127613.jpg?file_id=106127613&format=image/png'
					       ),
			               array('filename' => 'icons.png',
			                     'url' => 'http://staging.beam.tv/workspace/viewFolderFile/sKJNGsFxkW',
					     'embed' => '<iframe src="http://staging.beam.tv/workspace/viewFile/sKJNGsFxkW?cliponly=true" scrolling="no" width="256" height="240" />',
					     'clipframeurl' => 'http://staging.beam.tv/display_clipframe.php/106127613.jpg?file_id=106127613&format=image/png'
					     )
					 ),
		        'operator' => 'miles@themill.com',
			'title' => 'TEST - Ignore this',
			'details' => 'TEST - Ignore this',
			'tapenumber' => 'DIR0001258',
			'access' => 'email',
			'workspaceurl' => 'http://staging.beam.tv/workspace/viewFolder/zPpdzNyKWh',
			'billable' => '',
			'nobillreason' => 'Ceta test');

/*
$uploadDetails = array('jobnumber' => '200118',
                       'worksheet' => '400672',
                       'recipients' => array('tgill@themill.com', 'coletteb@themill.com'),
                       'files' => array(array('filename' => 'Waitrose_Wine_Offer_LONG_20SEC_640x360.mp4',
                                              'url' => 'http://www.beam.tv/workspace/viewFolderFile/SSdxqjccwq',
                                              'embed' => '<iframe src="http://www.beam.tv/workspace/viewFile/SSdxqjccwq?cliponly=true" scrolling="no" width="640" height="360" />',
                                              'clipframeurl' => 'http://www.beam.tv/display_clipframe.php/106045987.jpg?file_id=106045987&format=video/mp4'),
                                        array('filename' => 'Waitrose_Wine_Offer_Jubilee_20SEC_640x360.mp4',
                                              'url' => 'http://www.beam.tv/workspace/viewFolderFile/hRCWPhzzVn',
                                              'embed' => '<iframe src="http://www.beam.tv/workspace/viewFile/hRCWPhzzVn?cliponly=true" scrolling="no" width="640" height="360" />',
                                              'clipframeurl' => 'http://www.beam.tv/display_clipframe.php/106045992.jpg?file_id=106045992&format=video/mp4'),
                                        array('filename' => 'Waitrose_Wine_Offer_Jubilee_20SEC_640x360.mp4',
                                              'url' => 'http://www.beam.tv/workspace/viewFolderFile/hRCWPhzzVn',
                                              'embed' => '<iframe src="http://www.beam.tv/workspace/viewFile/hRCWPhzzVn?cliponly=true" scrolling="no" width="640" height="360" />',
                                              'clipframeurl' => 'http://www.beam.tv/display_clipframe.php/106045992.jpg?file_id=106045992&format=video/mp4'),
                                        array('filename' => 'Waitrose_Wine_Offer_Jubilee_20SEC_640x360.mp4',
                                              'url' => 'http://www.beam.tv/workspace/viewFolderFile/hRCWPhzzVn',
                                              'embed' => '<iframe src="http://www.beam.tv/workspace/viewFile/hRCWPhzzVn?cliponly=true" scrolling="no" width="640" height="360" />',
                                              'clipframeurl' => 'http://www.beam.tv/display_clipframe.php/106045992.jpg?file_id=106045992&format=video/mp4')),
                       'operator' => 'pec@beam.tv',
                       'title' => '2x Waitrose Wine Offer mp4',
                       'details' => '',
                       'tapenumber' => 'ELECTRONIC DUB',
                       'access' => 'email',
                       'workspaceurl' => 'http://www.beam.tv/workspace/viewFolder/bpTMffgSkd',
                       'billable' => 0,
                       'nobillreason' => 'Test no cost filmailer');
*/


    /* NO CHARGE NOTES
        see jobdetail table - nocharge, nochargereason, nochargeuserID, nochargedate
    */

    $billto4d = array('LA', 'SG');

    if (in_array(substr($uploadDetails['worksheet'], -2), $billto4d)) {
        echo "Bill 4D\n";
	echo "Exiting\n";
	exit;
    } else {
        echo "Bill Ceta\n";
    }

    $cetafilemailer = new cetaFilemailer();
    $res = $cetafilemailer->billUploads($uploadDetails);
    print_r($res);
    exit;

}

if ($cmd == 'createTapeRecord') {

    $tapeline = array('AssetPath' => '/Volumes/BRAND_XSAN_RT20/work/POKERSTARS/PokerStars_We_Are_Poker_Adapts_J78140/TX_Masters_DIR0386740LD/WAEN13.mov',
                      'Jnum' => 'J78140',
                      'Title' => 'We Are',
                      'AssetId' => '7232',
                      'Operator' => 'rp',
                      'Version' => '60sec B Canada English - 4x3 title safe',
                      'AspectRatio' => 'HD',
                      'Audio' => 'Stereo',
                      'AssetDir' => 'TX_Masters_DIR0386740LD',
                      'AssetProxy' => '/Volumes/BRAND_XSAN_RT20/FCSvr/Proxies.bundle/75/00/00000000000075a4/We%20Are.mov',
                      'Clock' => 'WAEN13',
                      'FrameRate' => '30000/1001',
                      'Duration' => '00:00:60:00',
                      'CreationDate' => '2012-04-17T14:01:42Z');

    $projectId = $tapeline['Jnum'];
    $libraryid = false;

    $cetamedia = new CetaMedia();

    $tapes = $cetamedia->fetchMediaByJnum($projectId);

    foreach ($tapes as $tape) {
	if (preg_match("/^DIR\d+/", $tape['TapeNumber'])) {
	    $libraryid = $tape['LibraryId'];
	    break;
	}
    }

    if ($libraryid === false) {
        echo "No tape found, creating new tape\n";
	$libraryid = $cetamedia->createTape($projectId, $tapeline['Operator']);
    }

    $tapeline['libraryID'] = $libraryid;

    if (isset($res['error'])) {	// No tape found
        echo "Error: Could not find tape record\n";
	exit;
    }

    $res = $cetamedia->createTapeRecord($tapeline);

    print_r($res);

    exit;

}

if ($cmd == 'fetchMediaByJnum') {
    $cetamedia = new CetaMedia();
    //$cetamedia->setLimit(5);
    $cetamedia->setOrderBy('copytype');
    //$cetamedia->setDirection('asc');
    //$cetamedia->setOffset('5');

    $cetamedia->cetaLocation(3);
    $res = $cetamedia->fetchMediaByJnum('J83561');
    print_r($res);
    exit;
}

function win1252toIso($string) {   
    #These chars seem to be not contained in php's CP1252 translation table
    static $extensions = array(142 => "&Zcaron;", 158 => "&zcaron;");
   
    # Go through string and decide char by char: "leave as is or build entity?"
    $newStr = ""; 
    for ($i=0; $i < strlen($string); $i++) {   
        $ord = ord($string[$i]);
        if (in_array($ord,array_keys($extensions))) {
            # build entity using extra translation table
            $newStr .= $extensions[$ord];
        } else {
            # build entity using php's translation table or leave as is
            #$newStr .= ( $ord > 127 && $ord < 160 ) ? 
            #    htmlentities( $string[$i], ENT_NOQUOTES, "CP1252" ) 
            #    : $string[$i];
            $newStr .= htmlentities($string[$i],ENT_NOQUOTES,"CP1252");
        }
    }   
    return $newStr;
}   


?>
