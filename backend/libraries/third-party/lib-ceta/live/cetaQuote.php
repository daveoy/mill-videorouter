<?php

class CetaQuote extends Ceta {

    function fetchQuotesFromProjectID($projectID) {
        $cdata = array('Object' => 'quote',
                       'SearchField' => 'projectID',
                       'Criteria' => $projectID,
                       'Options' => '');
    
        $jsonres = $this->cetaRead($cdata);
        return json_decode($jsonres, TRUE);
    }  

    function fetchQuotesFromJnumber($jnumber) {

	if (preg_match("/(\d+)/", $jnumber, $match)) {
	    $jnumber = $match[0];
	}

        $cdata = array('Object' => 'quote',
                       'SearchField' => 'projectnumber',
                       'Criteria' => $jnumber,
                       'Options' => '');
    
        $jsonres = $this->cetaRead($cdata);
        return json_decode($jsonres, TRUE);
    }  

    function fetchQuoteDetail($quoteid) {

        $cdata = array('Object' => 'quote',
	               'SearchField' => 'quoteID',
		       'Criteria' => $quoteid,
		       'Options' => '');
        $jsonres = $this->cetaRead($cdata);
	return json_decode($jsonres, TRUE);

    }

    function fetchLiveQuotesFromJnumber($jnumber) {

        $status = array('New', 'Completed', 'Confirmed');
	$quotes = array();

	$allquotes = $this->fetchQuotesFromJnumber($jnumber);

	foreach ($allquotes as $quote) {
	    if (!in_array($quote['fd_status'], $status)) {
	        continue;
	    }
	    $quotes[] = array('Number' => 'Q'.$quote['quoteID'],
	                      'Title' => $quote['title'],
			      'Producer' => $quote['ourcontact'],
			      'Status' => $quote['fd_status'],
			      'ClientCompany' => $quote['companyname'],
			      'ClientCompanyId' => $quote['companyID'],
			      'ClientContact' => $quote['contactname'],
			      'ProductionCompany' => $quote['productioncompanyname'],
			      'Director' => $quote['director']
			      );
	}

	return $quotes;

    }

    function fetchRateCardByCode($code) {

        $cdata = array('Object' => 'ratecard',
	               'SearchField' => 'cetacode',
		       'Criteria' => $code,
		       'Options' => '');

        $ares = json_decode($this->cetaRead($cdata), TRUE);
	return $ares[0];

    }

    function fetchRatecardByCategory($category) {

        $cdata = array('Object' => 'ratecard',
	               'SearchField' => 'category',
		       'Criteria' => $category,
		       'Options' => '');

	$ares = json_decode($this->cetaRead($cdata), TRUE);

	$rcitems = array();

	foreach ($ares as $rcitem) {
	    $rcitems[] = array("Id" => $rcitem['ratecardID'],
	                       "Code" => $rcitem['cetacode'],
	                       "Description" => $rcitem['description'],
	                       "Unit" => $rcitem['unit'],
	                       "Status" => $rcitem['fd_status']
			       );
	}

	return $rcitems;

	/*
        $jsonres = $this->cetaRead($cdata);
        return json_decode($jsonres, TRUE);
	*/
    }

    function ppmRequired($jnumber, $returnall=FALSE) {

        $rccategories = array('2D', '3D', 'Datalab', 'Studio', 'Design', 'Shoot', 'Telecine', 'Brand');
        $ppmitems = array('Flame'     => array('rcitems' => explode(' ', 'COMB DMINF HDMSU FLA INF ROTO HDMSU COMBA DIMSU DMFLA SAT'),
                                               'days' => 0,
                                               'trigger' => 3), 
                          'Smoke'     => array('rcitems' => explode(' ', 'SMOKE DMS HDMS'),
                                               'days' => 0,
                                               'trigger' => 10),
                          'Datalab'   => array('rcitems' => explode(' ', 'DKFPFRAME DKFS DKTR'),
                                               'days' => 0,
                                               'trigger' => 2), 
                          'AFX'       => array('rcitems' => explode(' ', 'AFTERFX AFTERFXD ARTWORKD ARTWORKHR DEAFXD DESAC'),
                                               'days' => 0,
                                               'trigger' => 3), 
                          'C4D'       => array('rcitems' => explode(' ', 'CINE4DD CINE4DH'),
                                               'days' => 0,
                                               'trigger' => 3), 
                          'Final Cut' => array('rcitems' => explode(' ', 'FCP FCRCD'),
                                               'days' => 0,
                                               'trigger' => 3), 
                          'Smac'      => array('rcitems' => explode(' ', 'SMACD SMACH'),
                                               'days' => 0,
                                               'trigger' => 3), 
                          'Design'    => array('rcitems' => explode(' ', 'DEAFXD DESAC DESCD DESD DESH DESHA DESI DESMISC DESP DESPC DESPT DEAFXH DESAD DESARTD DESARTH DESCDD DESCDH'),
                                               'days' => 0,
                                               'trigger' => 5), 
                          '3D'        => array('rcitems' => explode(' ', 'CGA CGDIRFEE CGDIRFEEDAY CGETD CGG CGLR CGM CGMISC CGMP CGPV CGR CGS CGSCAM CGT CGTEST CGTEXT ARTDIR3 CGCD CGFX CGIDA CGIH CGIW CGMCAD CGSHAKE DMCGI SAT3D'),
                                               'days' => 0,
                                               'trigger' => 5), 
                          'Nuke'      => array('rcitems' => explode(' ', 'ROTOD ROTOH NUKED NUKEH'),
                                               'days' => 0,
                                               'trigger' => 1), 
                          'Shoot'     => array('rcitems' => explode(' ', 'CGLIVE SA3 SA2 DKSHOOT SHOOTMISC'),
                                               'days' => 0,
                                               'trigger' => 0), 
                          'Telecine'  => array('rcitems' => explode(' ', 'DK2K DKHD DKGR'),
                                               'days' => 0,
                                               'trigger' => 1)
                     );

        foreach($rccategories as $category) {
            $rcs = $this->fetchRatecardByCategory($category);

            foreach($rcs as $rc) {
                $rcitems[$rc['Code']] = array('Code' => $rc['Code'],
                                          'Unit' => $rc['Unit']);
            }
        }

        $quotes = $this->fetchQuotesFromJnumber($jnumber);
        $results = array();

        foreach($quotes as $quote) {

            if ($quote['fd_status'] == 'Cancelled') {
                continue;
            }

            $quotetimes = $ppmitems;

            foreach($quote['details'] as $details) {

                foreach($quotetimes as $category => $categoryvalues) {
                    if (in_array($details['cetacode'], (array)$categoryvalues['rcitems'])) {
                        if ($rcitems[$details['cetacode']]['Unit'] == 'day') {
                            $quotetimes[$category]['days'] = $quotetimes[$category]['days'] + $details['quotedunits'] * $details['quotedtime'];
                        } else {
                            $quotetimes[$category]['days'] = $quotetimes[$category]['days'] + ($details['quotedunits'] * $details['quotedtime'])/10;
                        }
                    }
                }
            }

            $results[] = array('QuoteId' => $quote['quoteID'],
                               'Title' => $quote['title'],
                               'Subtitle' => $quote['subtitle'],
                               'Status' => $quote['fd_status'],
                               'PPM' => 0,
                               'Items' => $quotetimes
                              );
        }    

        foreach($results as $k => $quote) {

            foreach($quote['Items'] as $category=> $item) {
                if ($item['days'] > 0) {
                    if ($item['days'] > $ppmitems[$category]['trigger']) {
			if ($returnall == FALSE) {
			    return 1;
			}
                        $results[$k]['PPM'] = 1;
                    }
                }
            }
        }
	if ($returnall == FALSE) {
	    return 0;
	}
	return $results;

    }

}

?>
