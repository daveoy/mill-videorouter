<?php

require_once _MODELS . "Input.php";
require_once _MODELS . "Output.php";
require_once _MODELS . "InputMock.php";
require_once _MODELS . "OutputMock.php";
require_once _MODELS . "Router.php";
require_once _MODELS . "RouterMock.php";
require_once _MODELS . "Group.php";
require_once _MODELS . "Log.php";

class Router extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('router/index');
    }

    public function GET()
    {
        global $config;
        $acceptedArguments = array("list", "input", "output");
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
        if(!$config['dev'])
        {
            $inputModel = new Input();
            $outputModel = new Output();
        }
        else
        {
            $inputModel = new InputMock();
            $outputModel = new OutputMock();
        }

        switch($operation)
        {
            case "input":
                $groupModel = new GroupModel();
                $inputList = $inputModel->getInput();
                $inputPorts = $groupModel->getPortsByType("input");

                foreach($inputList as $input)
                {
                    foreach($inputPorts as $key => $current)
                    {
                        if($input->Id == $current->port_uid)
                        {
                            if($current->active)
                            {
                                # build object
                                $groupName = $current->group_name;
                                $groupPosition = $current->group_position;

                                # build "locked" array
                                $locked = array();
                                if(!is_null($current->locked))
                                {
                                    $locked = array("username" => $current->locker, "date" => $current->date_lock);
                                }

                                # explode label
                                $explodedLabel = explode("|", $current->name);
                                $port = array();
                                $index = 1;
                                foreach($explodedLabel as $level)
                                {
                                    $port["level_" . $index] = $level;
                                    $index++;
                                }

                                $levelOneLabel = $port['level_1'];
                                unset($port['level_1']);
                                if(!isset($response['input'][$groupPosition][$groupName][$levelOneLabel]))
                                {
                                    $port['type'] = $current->type;
                                    // $port['friendly_label'] = $levelOneLabel;
                                    $port['label'] = $levelOneLabel;
                                    $port['short_label'] = $current->short_label;
                                    $port['data'][$current->port_uid] = array(
                                        "port_uid" => $current->port_uid,
                                        "sublevel_label" => "",
                                        "locked" => $locked
                                        );

                                    foreach($port as $key => $details)
                                    {
                                        if($key == "level_2")
                                        {
                                            $port['data'][$current->port_uid] = array(
                                                "sublevel_label" => $details,
                                                "port_uid" => $current->port_uid,
                                                "locked" => $locked

                                            );
                                            unset($port['level_2']);
                                        }
                                    }

                                    $response['input'][$groupPosition][$groupName][$levelOneLabel] = $port;
                                }
                                else
                                {
                                    $port['data'] = array(
                                        "sublevel_label" => "",
                                        "port_uid" => $current->port_uid,
                                        "locked" => $locked
                                    );
                                    foreach($port as $key => $details)
                                    {
                                        if($key == "level_2")
                                        {
                                            $port['data']['sublevel_label'] = $details;
                                            unset($port['level_2']);

                                        }
                                    }
                                    $response['input'][$groupPosition][$groupName][$levelOneLabel]['data'][$current->port_uid] = $port['data'];
                                }
                            }
                        }
                    }
                }


                ksort($response['input']);

                # sort py position
                $tmpInput = array();
                foreach($response['input'] as $position => $arrayPosition)
                {
                    // print_r("Position:".$position."\n\r");
                    foreach($arrayPosition as $groupName => $arrayGroup)
                    {
                        // print_r("Group Name:".$groupName."\n\r");
                        $tmpInput[$groupName] = $response['input'][$position][$groupName];
                    }
                }

                // print_r($tmpInput);die();
                $response['input'] = $tmpInput;

                break;

            case "output":
                $groupModel = new GroupModel();
		$outputList = $outputModel->getOutput();
                $outputPorts = $groupModel->getPortsByType("output");

                sort($outputList);

                foreach($outputList as $output)
                {
                    foreach($outputPorts as $key => $current)
                    {
                        if($output->Id == $current->port_uid)
                        {

                            if($current->active)
                            {
                                # build object
                                $floorName = !is_null($output->Floor) ? $output->Floor : 1;
                                $floorPosition = $output->Floor_position;

                                # explode label
                                $explodedLabel = explode("|", $current->name);
                                $port = array();
                                $index = 1;
                                foreach($explodedLabel as $level)
                                {
                                    $port["level_" . $index] = $level;
                                    $index++;
                                }

                                $levelOneLabel = $port['level_1'];
                                unset($port['level_1']);
                                if(!isset($response['output'][$floorPosition][$floorName][$levelOneLabel]))
                                {
                                    $port['type'] = $current->type;
                                    // $port['friendly_label'] = $levelOneLabel;
                                    $port['label'] = $levelOneLabel;
                                    $port['short_label'] = $current->short_label;
                                    $port['data'][$current->port_uid] = array(
                                        "port_uid" => $current->port_uid,
                                        "sublevel_label" => "",
                                        );

                                    foreach($port as $key => $details)
                                    {
                                        if($key == "level_2")
                                        {
                                            $port['data'][$current->port_uid] = array(
                                                "sublevel_label" => $details,
                                                "port_uid" => $current->port_uid
                                            );
                                            unset($port['level_2']);
                                        }
                                    }

                                    $response['output'][$floorPosition][$floorName][$levelOneLabel] = $port;
                                }
                                else
                                {
                                    $port['data'] = array(
                                        "sublevel_label" => "",
                                        "port_uid" => $current->port_uid
                                    );
                                    foreach($port as $key => $details)
                                    {
                                        if($key == "level_2")
                                        {
                                            $port['data']['sublevel_label'] = $details;
                                            unset($port['level_2']);

                                        }
                                    }
                                    $response['output'][$floorPosition][$floorName][$levelOneLabel]['data'][$current->port_uid] = $port['data'];
                                }
                            }
                        }
                    }
                }

                ksort($response['output']);

                # sort py position
                $tmpOutput = array();
                foreach($response['output'] as $position => $arrayPosition)
                {
                    // print_r("Position:".$position."\n\r");
                    foreach($arrayPosition as $floorName => $arrayFloor)
                    {
                        // print_r("Floor Name:".$floorName."\n\r");
                        $tmpOutput["'" . $floorName . "'"] = $response['output'][$position][$floorName];
                    }
                }

                $response['output'] = $tmpOutput;
                // print_r($response);die();

                break;

            case "list": default:
                # get list of routing
                $lists = array("input" => array(), "output" => array());

                $groupModel = new GroupModel();

                # get list of Input
                $inputList = $inputModel->getInput();
                $lists['input'] = $inputList;

                # get list of Output and get connection logs
                $logModel = new LogModel();
                $outputList = $outputModel->getOutput();
                # $outputList = $logModel->getLogs($outputList);
                $lists['output'] = $outputList;

                # group input
                $inputPorts = $groupModel->getPortsByType("input");

                $response = array("input" => array(), "output" => array());
                foreach($inputPorts as $key => $current)
                {
                    foreach($inputList as $input)
                    {
                        if($input->Id == $current->port_uid)
                            $response['input'][$current->group_name][$input->Label] = $input;
                    }
                }

		# get connections
		// connect to Memcache
                $this->memcache = new Memcache();
                $this->memcache->addServer($config['cache']['ip'], $config['cache']['port']);

		$routingListByOutput = $this->memcache->get('L');
                $routingListByOutput = is_null($routingListByOutput) ? array() : json_decode($routingListByOutput);

		# group output
                $outputPorts = $groupModel->getPortsByType("output");
                foreach($outputPorts as $key => $current)
                {
                    foreach($outputList as $output)
                    {
                        if($output->Id == $current->port_uid)
			{
			    // get source
			    $output->Source = (int) $routingListByOutput[($output->Id -1)]->Source + 1;
			    $output->Created = 0;
                            $response['output'][$current->floor_name][$output->Label] = $output;
			}
                    }
                }

                break;
        }

        $this->template->set('response' , $response);
        $this->template->render();
    }

    public function POST()
    {
        global $config;

        if(!$config['dev'])
        {
            $router = new RouterModel();
        }
        else
        {
            $router = new RouterMock();
        }

        $response = $router->route($this->parameters);
        # log action (Success/Failed)
        $status = "Failed";
        if(!empty($response))
        {
            if($response)
            {
                $status = "Success";
            }
        }

        $logModel = new LogModel();
        # log (user, ports, status)
        $logModel->log("GenericUser", $this->parameters, $status);

        $this->template->set('response' , $response);
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
