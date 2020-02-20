<?php

require_once THIRD_PARTY . "lib-ceta/live/Ceta.class.php";

class PortModel extends Model 
{
    public function __construct($fields = array())
    {
        global $config;
        parent::__construct();
    }

    private function fixMethodName($methodName)
    {
    	$explodedMethod = explode("_", $methodName);
    	$pieces = count($explodedMethod);
    	
    	# build methodName replacing underscores and making uppercase the first character of every piece after the first one
    	$methodName = "";
    	for($i = 0; $i < $pieces; $i++)
    	{
    		$methodName .= ucfirst($explodedMethod[$i]);
    	}
	 
    	return "set" . $methodName;
    }

    public function getInfo($portUid)
    {
        $portInfo = array();

        # check if port is currently booked
        $portInfo['booked'] = $this->isCurrentlyBooked($portUid);
        return $portInfo;
    }

    public function isCurrentlyBooked($portUid, $time = null)
    {
        # get ceta_id from given port
        $query = "SELECT ceta_id FROM vr_label WHERE type = 'output' AND port_uid = " . $portUid;
        $cetaId = $this->query($query);
        if(empty($cetaId))
            throw new ErrorException("The port_uid " . $portUid . " doesn't exists", 500);

        if(is_null($time))
            $time = date("Y-m-d H:i", time());

        # set as not booked by default
        $booked = 0;

        if($cetaId[0]->ceta_id != "N/A")
        {
            $cetaQuery = "SELECT COUNT(resourcescheduleid) FROM resourceschedule WHERE resourceid = " . $cetaId[0]->ceta_id . " AND '" . $time . "' between starttime and endtime and fd_status = 'Confirmed';";
        
            # instantiate CETA 
            $ceta = new Ceta();

            # retrieve if room of monitor with given portUid is currently booked
            $cetaResponse = $ceta->sqlRead($cetaQuery);
            $fetch = mysql_fetch_row($cetaResponse);
            $booked = $fetch[0];
        }

        

        return $booked[0] > 0 ? true : false;
        
    }


}

?>