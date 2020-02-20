<?php

require_once _MODELS . "Input.php";
require_once _MODELS . "Output.php";
require_once _MODELS . "InputMock.php";
require_once _MODELS . "OutputMock.php";
require_once _MODELS . "Database.php";


class Test extends Controller 
{
	public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('main/index');
    }

    public function GET() 
    {
        global $config;

        # instantiate Input/Output
        if($config['dev'])
        {
        	$inputModel = new InputMock();
			$outputModel = new OutputMock();
        }
        else
        {
        	$inputModel = new Input();
			$outputModel = new Output();
        }
        
        $db = new DatabaseModel();
		$db->__constructor();

		# get all input from vr_input and put into vr_input_test
		$inputList = $db->query("SELECT * FROM vr_input");
		
		# get all output from vr_output and put into vr_output_test
		$outputList = $db->query("SELECT * FROM vr_output");

		# put in random group
		foreach($inputList as $input)
		{
			$port = (object) array(
				"port_uid" 	=> $input->port_uid,
				"label"		=> $input->name
			);

			$db->insert(
				array(
					"tableName" => "vr_input_test",
					"object" => $port
				)
			);
		}

		foreach($outputList as $output)
		{
			$port = (object) array(
				"port_uid" 	=> $output->port_uid,
				"label" 		=> $output->name,
				"source" => rand(1, 133)
			);

			$db->insert(
				array(
					"tableName" => "vr_output_test",
					"object" => $port
				)
			);
		}

		die("Done! Please check");
	}
}