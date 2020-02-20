<?php

class CetaLoop {

    function formatForGannt($results) {

        $jobs = array();
        $services = array();
        $schedules = array();
        $cetaservice = new cetaService();

        foreach ($ares as $job) {
            // look at $job['schedule'] for individual bookings.

            if ($job['schedule']) {
                foreach($job['schedule'] as $schedule) {
                    $schedules[$schedule['resourcescheduleID']] = $schedule;
                }   
            }

            if ($options['resource_schedule_service']) {
                foreach ($job['resource_schedule_service'] as $service) {

                    if (! array_key_exists($service['service'], $services)) {           // fetch service details if missing
                        $services[$service['service']] = $cetaservice->fetchServiceDetails($service['service']);
                    }

                    $date = substr($schedules[$service['resourcescheduleID']]['starttime'], 0, 10);
                    $services[$service['service']]['Event'][$date] = array('Start' => $schedules[$service['resourcescheduleID']]['starttime'],
                                                                           'End' => $schedules[$service['resourcescheduleID']]['endtime']);
                }   
            }   

            $jobs[] = array('Id' => $job['jobID'],
                            'Status' => $job['fd_status'],
                            'Start' => $job['startdate'],
                            'End' => $job['enddate']
                           );
        }

        // format for gantt
        $categories = array('ganttRed', 'ganttGreen', 'ganttOrange');
        $x = 0;
        foreach ($services as $service) {

            if ($x >= count($categories)) {
                $x = 0;
            }   

            $category = $categories[$x];
            $x++;

            $events = array();
            foreach ($service['Event'] as $event) {
                $events[] = array('from' => '/Date(' . strtotime($event['Start']) . '000)/',
                                  'to'   => '/Date(' . strtotime($event['End']) . '000)/',
                                  'desc'   => $service['Description'],
                                  'customClass'   => $category,
                                  'label'   => '');
            }

            $tasks[] = array('name' => $service['Description'],
                             'desc' => '',
                             'values' => $events);
        }

//      echo json_encode($tasks);


    }   

}

?>
