<?php

require_once _MODELS . "Lock.php";

class Lock extends Controller
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
    	# username and input_port_uid can't be null
    	if(!isset($this->parameters['username']) && !isset($this->parameters['input_port_uid']))
    		throw new ErrorException("username and input_port_uid can't be null can't be null" , "501");
    	
    	$username = $this->parameters['username'];
        $inputPortUid = $this->parameters['input_port_uid'];

    	# instantiate LockModel
    	$lockModel = new LockModel();

    	# check if user is admin and lock input port
    	$response = $lockModel->lock($username, $inputPortUid);
        $this->template->set('response', $response);
    	$this->template->render();
        
    }

    public function PUT() 
    {
    	throw new ErrorException("Not Implemented" , "501");
    }

    public function DELETE() 
    {
    	# username and input_port_uid can't be null
    	if(!isset($this->parameters['username']) && !isset($this->parameters['input_port_uid']))
    		throw new ErrorException("username and input_port_uid can't be null can't be null" , "501");
    	
    	$username = $this->parameters['username'];
        $inputPortUid = $this->parameters['input_port_uid'];

    	# instantiate LockModel
    	$lockModel = new LockModel();

    	# check if user is admin and lock input port
    	$response = $lockModel->unlock($username, $inputPortUid);
        $this->template->set('response', $response);
    	$this->template->render();
    }
}