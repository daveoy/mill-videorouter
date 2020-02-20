<?php

class cetaCalendar extends Ceta {

    function __construct() {

	$this->clientServiceScheduleUrl = '/display/project/clientServiceSchedule.php?projectId=';

    }

    function fetchProjectCalendar($jnum) {

	$ceta = new Ceta();
        $categories = array('ganttGreen', 'ganttOrange', 'ganttRed');
        $ci = 0;

        $cetaproject = new CetaProject();
        $details = $cetaproject->fetchProjectIdByJnum($jnum);
        $startdate = strtotime($details['startdate']);
        $projectid = $details['projectID'];

	$url = 'http://' . $ceta->baseurl . $this->clientServiceScheduleUrl . $projectid;
	$jsonres = file_get_contents($url);
	$res = json_decode($jsonres, TRUE);

        $schedule = array();
	$yearstime = mktime() + 31536000;

        foreach ($res['schedule'] as $event) {

	    if (strtotime($event['date']) > $yearstime) {
	        continue;
	    }

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
            foreach ($category['Dates'] as $date => $booking) {

                if ($startdate > strtotime($date)) {
                    continue;
                }

                $event = array();
                foreach ($booking['Services'] as $service) {

                    if (array_key_exists('from', $event)) {
                        $event['desc'] = $event['desc'] . '<br>' . $service['Description'] . '(' . $service['Service'] . ')';
                    } else {
                        $event = array('from' => '/Date(' . (strtotime($service['Date']) + 3600) . '000)/',
                                       'to'   => '/Date(' . (strtotime($service['Date']) + 3600) . '000)/',
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

	return $ganttarray;

    }  

}

?>
