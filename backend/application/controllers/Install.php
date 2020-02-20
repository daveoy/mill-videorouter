<?php

require_once _MODELS . "Input.php";
require_once _MODELS . "Output.php";
require_once _MODELS . "Database.php";


class Install extends Controller 
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
        $inputModel = new Input();
		$outputModel = new Output();
		$db = new DatabaseModel();
		$db->__constructor();

		# get all input
		// $inputList = $inputModel->getInput();
		
		# get all output
		$outputList = $outputModel->getOutput();

		# put in random group
		// foreach($inputList as $input)
		// {

		// 	# populate vr_input
		// 	if($config['dev'])
		// 		$port->port_uid = ($input->Id + 1);
		// 	else
		// 		$port->port_uid = $input->Id;

		// 	$port = (object) array(
		// 		"port_uid" => $port->port_uid,
		// 		"name" => $input->Label
		// 	);



		// 	$db->insert(
		// 		array(
		// 			"tableName" => "vr_input",
		// 			"object" => $port
		// 		)
		// 	);

		// 	// # populate vr_label
		// 	// $port = (object) array(
		// 	// 	"name" 		=> $input->Label,
		// 	// 	"type"		=> "input",
		// 	// 	"group_uid" => 1,
		// 	// 	"active" 	=> 1
		// 	// );

		// 	// if($config['dev'])
		// 	// 	$port["port_uid"] = ($input->Id + 1);
		// 	// else
		// 	// 	$port["port_uid"] = $input->Id;

		// 	// $db->insert(
		// 	// 	array(
		// 	// 		"tableName" => "vr_label",
		// 	// 		"object" => $port
		// 	// 	)
		// 	// );
		// }

		foreach($outputList as $output)
		{

			# populate vr_output
			if($config['dev'])
				$port['port_uid'] = ($output->Id + 1);
			else
				$port['port_uid'] = $output->Id;

			$port = array(
				"port_uid" => $port['port_uid'],
				"name" => $output->Label,
				"floor_uid" => 1
			);

			// $db->insert(
			// 	array(
			// 		"tableName" => "vr_output",
			// 		"object" => $port
			// 	)
			// );


			// # populate vr_label
			if($config['dev'])
				$port["port_uid"] = ($output->Id + 1);
			else
				$port["port_uid"] = $output->Id;

			$port = (object) array(
				"port_uid" => $port["port_uid"],
				"name" => $output->Label
			);

			// $db->insert(
			// 	array(
			// 		"tableName" => "vr_output",
			// 		"object" => $port
			// 	)
			// );

			$port =  array(
				"name" 		=> $output->Label,
				"type"		=> "output",
				"group_uid" => 1,
				"active" 	=> 1
			);

			if($config['dev'])
				$port["port_uid"] = ($output->Id + 1);
			else
				$port["port_uid"] = $output->Id;

			$db->insert(
				array(
					"tableName" => "vr_label",
					"object" => $port
				)
			);
		}

		die("Done! Please check");
	}
}