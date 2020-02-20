<?php
require '../xajax/xajax_core/xajax.inc.php';
require 'Ceta.class.php';

class CetaXajax {

    public $xajax;

    public function __construct() {
       $this->xajax = new xajax();

       $methods = get_class_methods(__CLASS__);
       foreach ($methods as $method) {
           if ($method != '__construct') {
               $this->xajax->registerFunction(array($method, &$this, $method));
           }
       }

       $this->xajax->processRequest();

    }   

    function xajaxfetchProjectIdByJnum($jnumber) {

	$cetaproject = new CetaProject();
	$res = $cetaproject->fetchProjectIdByJnum($jnumber);
	$results = print_r($res, TRUE);

        $objResponse = new xajaxResponse();
	$objResponse->setReturnValue($results);

	return $objResponse;

    }   

    function xajaxfetchTapeDetails($tapenumber) {

	$cetamedia = new CetaMedia();
	$res = $cetamedia->fetchTapeDetails($tapenumber);
	$results = print_r($res, TRUE);

        $objResponse = new xajaxResponse();
	$objResponse->setReturnValue($results);

	return $objResponse;

    }
    
    function xajaxfetchQuotesFromProjectID($projectID) {

	$cetaquote = new CetaQuote();
	$res = $cetaquote->fetchQuotesFromProjectID($projectID);
	$results = print_r($res, TRUE);

        $objResponse = new xajaxResponse();
	$objResponse->setReturnValue($results);

	return $objResponse;

    }   

    function xajaxfetchDetailsByUsername($username) {

	$cetauser = new CetaUser();
	$res = $cetauser->fetchDetailsByUsername($username);
	$results = print_r($res, TRUE);

        $objResponse = new xajaxResponse();
	$objResponse->setReturnValue($results);

	return $objResponse;

    }   

}

?>
