<?php

require_once _MODELS . "Input.php";
require_once _MODELS . "Output.php";
require_once _MODELS . "Group.php";

class Cms extends Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('cms/index');
    }

    public function GET() 
    {
        global $config;
        $acceptedArguments = array("input", "output");
        $listRouting = array();
        $response = array();
        
        $operation = null;

        foreach($acceptedArguments as $argument)
        {
            if(array_key_exists($argument, $this->parameters))
            {
                $operation = $argument;
                break;
            }
        }

        # instantiate Input and Output Models
        $inputModel = new Input();
        $outputModel = new Output();
    
        switch($operation)
        {
            case "input":
                $groupModel = new GroupModel();
                $response['input'] = $inputModel->getInput();
                break;

            case "output":
                $groupModel = new GroupModel();
                $response['output'] = $outputModel->getOutput();
                break;
        }
        
        $this->template->set('response' , $response);
        $this->template->render();
    }

    public function POST() 
    {
        throw new ErrorException("Not Implemented" , "501");	
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