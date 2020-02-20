<?php

require_once _MODELS . "Input.php";
require_once _MODELS . "Output.php";
require_once _MODELS . "InputMock.php";
require_once _MODELS . "OutputMock.php";
require_once _MODELS . "Router.php";
require_once _MODELS . "RouterMock.php";
require_once _MODELS . "Group.php";

class Main extends Controller
{	
    public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('main/index');
    }

    public function GET() 
    {
        global $config;
        
	$response = array("response" => 200,
			  "error" => 0,
			  "data" => "Welcome to API Videorouter 1.0"
	);
	$this->template->set('response' , $response);
	$this->template->render();
    }


    public function POST() 
    {
        global $config;

        if(!$config['dev'])
        {
            $router = new Router();
        }
        else
        {
            $router = new RouterMock();
        }

        $response = $router->route($this->parameters);
        return $response;
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
