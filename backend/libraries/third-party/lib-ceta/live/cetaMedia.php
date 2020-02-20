<?php

class CetaMedia extends Ceta {

    function __construct() {
	parent::__construct();
    }

    function setMediaDetails($type) {

	if ($type == 'SanDirUK') {
            return array('format' => 'SAN Directory',
	                 'copytype' => 'Master',
	                 'location' => 'London SAN',
	                 'projecttype' => 'LD Project',
		         'aspectratio' => "I DON'T KNOW",
		         'videostandard' => "I DON'T KNOW",
		         'jobdetailID' => 0,
		         'donotcharge' => 1,
		         'stocktype' => 'DIR',
		         'inlibrary' => 'YES',
		         'heldonsite' =>1,
		         'depot' => 'UK');
        }

	return false;
    }

    function fetchMediaByBarcode($barcode) {

        $cdata = array('Object' => 'media',
                       'SearchField' => 'barcode',
                       'Criteria' => $barcode);
    
        $details = json_decode($this->cetaRead($cdata), TRUE);

	if (isset($details['warning'])) {
	    return array('error' => 1,
	                 'message' => $details['warning']);
	}

        return $details[0];
    }  

    function fetchMediaByLibraryId($libraryid) {

        $cdata = array('Object' => 'media',
                       'SearchField' => 'libraryID',
                       'Criteria' => $libraryid);
    
        $details = json_decode($this->cetaRead($cdata), TRUE);

	if (isset($details['warning'])) {
	    return array('error' => 1,
	                 'message' => $details['warning']);
	}

        return $details[0];
    }  

    function fetchMediaByJnum($jnum) {

	$jnum = $this->toCetaProjectNumber($jnum);

        if ($this->location == 3){
            $jnum = $this->returnLaNumber($jnum);
        }

        $cdata = array('Object' => 'media',
	               'SearchField' => 'projectnumber',
		       'Criteria' => $jnum);

        $jres = $this->cetaRead($cdata);

	$tapes = array();

	$ares = json_decode($jres, TRUE);

        foreach ($ares as $tape) {

	    if ($tape['shelf'] == '' || $tape['shelf'] == $tape['location']) {
	        $location = $tape['location'];
	    } else {
	        $location = $tape['shelf'] . ', ' . $tape['location'];
	    }

	    $tapes[] = array('TapeName' => htmlspecialchars($tape['title']),
			     'Subtitle' => htmlspecialchars($tape['subtitle']),
	                     'TapeNumber' => $tape['barcode'],
			     'LibraryId' => $tape['libraryID'],
			     'TapeType' => $tape['copytype'],
			     'TapeLocation' => $location,
			     'TapeContents' => htmlspecialchars($tape['labelnotes']),
			     'CurrentlyRequired' => $tape['currentlyrequired'],
			     'TapeCreationDate' => strtotime($tape['cdate']),
			     'JobId' => $tape['jobID']);
	}

        return $tapes;
    }


    // JPS Compatibility
    function getTapesDetails($barcode, $jnum=false, $location=false) {

        $details = $this->fetchMediaByBarcode($barcode);

	$return = array('JobNumber' => 'J' . $details['projectnumber'],
	                'JobName' => '',
			'Product' => htmlspecialchars($details['productname']),
			'VideoStandard' => htmlspecialchars($details['videostandard']),
			'MediaTitle' => htmlspecialchars($details['subtitle']),
			'Notes' => htmlspecialchars($details['notes1']),
			'Contents' => htmlspecialchars($details['labelnotes']));

	foreach ($details['events'] as $event) {
	    $return[] = array('Id' => $event['eventID'],
	                      'Start' => $event['timecodestart'],
	                      'Clock' => htmlspecialchars($event['clocknumber']),
			      'ClockTC' => $event['clocktimecode'],
			      'ProgramTC' => $event['timecodestart'],
			      'Duration' => $event['fd_length'],
	                      'Detail' => htmlspecialchars($event['eventtitle']),
			      'Date' => $event['eventdate'],
			      'Deleted' => '');
	}

	return $return;
    }


    function createTape($projectnumber, $operator, $location = 1) {
        
	$cetaproject = new cetaProject();
	$project = $cetaproject->fetchProjectIdByJnum($projectnumber);

	if (isset($project['warning'])) {
	    return array('error' => 1,
	                 'message' => $project['warning']);
	}

	$this->cetaLocation(1);
	$res = $this->generateBarcode('DIR');

	if ($res['count'] != 1) {
	    return array('error' => 1,
	                 'message' => 'Error generating barcode.');
	}

	$barcode = $res['data']['sequencevalue'];
	$typedata = $this->setMediaDetails('SanDirUK');

	if ($typedata == false) {
	    return array('error' => 1,
	                 'message' => 'Could not find default setting for tape type');
	}

        $data = array('table_name' => 'library',
	              'id_name' => 'libraryID',
		      'projectID' => $project['projectID'],
		      'projectnumber' => $project['projectnumber'],
	              'productID' => $project['productID'],
	              'productname' => $project['productname'],
	              'title' => $project['title'],
	              'barcode' => $barcode,
	              'companyname' => $project['companyname'],
	              'companyID' => $project['companyID'],
	              'cuser' => $operator,
	              'cdate' => date('Y-m-d H:i:s'),
		      'modate' => date('Y-m-d H:i:s'));

	$data = array_merge($data, $typedata);

	$jdata = rawurlencode(json_encode($data));

	$res = json_decode($this->cetaInsert($jdata), TRUE);

	if ($res['data']['libraryID']) {
	    return $res['data']['libraryID'];
	} else {
	    return array('error' => 1,
	                 'message' => 'Error adding tape record: ' . $res['message']);
	}
    }

    function generateBarcode($stockcode) {

	$location = $this->locations[$this->cetaLocation()];

        $cdata = array('Object' => 'issuebarcode',
                       'SearchField' => $stockcode,
                       'Criteria' => $location);
    
        $jres = $this->cetaRead($cdata);
        return json_decode($jres, TRUE);

    }

    function createTapeRecord($tapeline) {

	$data = array();

	$tcstopparts = explode(':', $tapeline['Duration']);

	$data['table_name'] = "events";
	$data['id_name'] = "eventID";

	$data['timecodestart'] = '00:00:0000';
	$data['timecodestop'] = $tapeline['Duration'];
	$data['fd_length'] = $tapeline['Duration'];

	$data['libraryID'] = $tapeline['libraryID'];

	$data['eventtitle'] = $tapeline['Version'] . " - " . $tapeline['AspectRatio'];

	$data['cdate'] = date('Y-m-d H:i:s');
	$data['cuser'] = $tapeline['Operator'];

	$data['clocknumber'] = $tapeline['Clock'];
	$data['eventdate'] = date('Y-m-d H:i:s');

	$jdata = rawurlencode(json_encode($data));
	$res = json_decode($this->cetaInsert($jdata), TRUE);

	if ($res['count'] == 1) {
	    return $res['data']['eventID'];
	} else {
	    return array('error' => 1,
	                 'message' => 'Error creating tape record.');
	}

    }

    function returnLaNumber($jpsNumber){

        $array = array(1,43,66,82,288,406,462,463,466,467,468,470,473,474,476,478,479,480,483,485,486,489,493,494,495,497,499,501,502,503,505,507,508,509,511,512,514,515,517,518,519,520,522,524,525,526,527,529,530,532,50833,51906,52099,52383,52610,52845,53097,53226,53395,53422,53441,53639,53735,53858,53866,53979,54350,55070,55115,55549,55712,55732,55928,55951,56171,56180,56220,56253,56492,56562,56601,56875,56896,56993,57035,57156,57341,57345,57692,58189,58232,58237,58370,58587,58646,58663,58700,59063,59127,59173,59401,59472,59565,59664,59748,59840,60359,60485,60547,60549,60637,60716,60789,61862,62045,62236,62573,62786,62788,62852,63288,63595,63772,63976,64281,64354,64428,64588,64636,64742,64743,64766,64938,64998,65430,65522,66103,66192,66220,66253,66255,66420,66534,66822,66852,66853,66895,66997,67003,67454,67576,67987,68628,68816,68935,69452,69707,71497,72182,72328,72372,72465,72614,72660,73065,73100,73274,73517,74128,74996,75339,75398,75447,75452,75657,75739,75939,76167,76276,76391,76393,76551,76612,77400,77876,78153,78284,78522,78525,78646,78770,79149,79208,79444,79502,79755,79989,80091,80373,81424,81697,81952,82078,82130,82269,82325,82548,82589,83361,83413,83427,83561);

        if (in_array($jpsNumber, $array)){
            return ($jpsNumber+800000);
        } else {
            return($jpsNumber);
        }
   }

}
?>
