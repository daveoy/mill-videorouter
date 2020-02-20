<?php

require_once _MODELS . "Group.php";

class Group extends Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('group/index');
    }

    public function GET() 
    {
    	throw new ErrorException("Not Implemented" , "501");
    }

    public function POST() 
    {
    	# group uid can't be null
    	if(!isset($this->parameters['uid']) && !isset($this->parameters['floor']))
    		throw new ErrorException("uid can't be null" , "501");
    	
    	$groupUid = isset($this->parameters['uid']) ? $this->parameters['uid'] : null;
        $type = isset($this->parameters['type']) ? $this->parameters['type'] : null;
    	$floor = isset($this->parameters['floor']) ? $this->parameters['floor'] : null;
    	# instantiate group model
    	$groupModel = new GroupModel();

        if(!is_null($floor))
        {
            $ports = $groupModel->getPortsByFloor($floor);
        }
        else
        {
            # get ports by group uid
            $ports = $groupModel->getPortsByGroupUid($groupUid, $type);    
        }
    	
    	$this->template->set('ports' , $ports);
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