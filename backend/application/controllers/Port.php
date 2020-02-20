<?php

require_once _MODELS . "Port.php";

class Port extends Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('rest/index');
    }

    public function GET() 
    {
    	throw new ErrorException("Not Implemented" , "501");	
    }

    public function POST() 
    {
        # retrieve port_uid
        $portUid = isset($this->parameters['port_uid']) ? $this->parameters['port_uid'] : null;
        if(is_null($portUid))
     		throw new ErrorException("port_uid can't be null" , "501");

     	# get port informations
     	$portModel = new PortModel();
		$portInfo = $portModel->getInfo($portUid);

		$this->template->set('response' , $portInfo);
        $this->template->render();
    }

    public function PUT() 
    {
        throw new ErrorException("Not Implemented" , "501");
    }

    public function DELETE() 
    {
        throw new ErrorException("Not Implemented" , "501");
    }
}