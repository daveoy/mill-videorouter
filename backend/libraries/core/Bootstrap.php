<?php

require_once _LIBRARIES . '/core/Security.php';
require_once _LIBRARIES . '/core/Utils.php';

function bootstrap()
{
	global $config;

	##
	# Get request url
	##
	$requestUri = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';

	$url = strlen($requestUri) > 1 ? substr($requestUri, 1, strlen($requestUri)) : '';
	##
	# Split url
	##
	$split = explode(DS, $url);
	##
	# Get controller
	##
	$controller = isset($split[3]) && $split[3] != '' ? $split[3] : $config['default_controller'];
	// $action = isset($split[1]) && $split[1] != '' ? $split[1] : $config['default_action'];
	# RESTful get method
	if(isset($split[1]) && is_numeric($split[1]))
	{
		$uid = isset($split[1]) && $split[1] != '' ? $split[1] : null;
	}
	##
	# Get controller
	##
	$path = _APPLICATION . 'controllers' . DS . ucfirst($controller) . '.php';
	if(file_exists($path)){
        	require_once($path);
	} else {
        	$controller = $config['error_controller'];
        	//require_once(_APPLICATION . 'controllers' . DS . ucfirst($controller) . '.php');
        	//$controller = new $controller();
        	throw new ErrorException("Error");
	}

    ##
	# get Action
	##
	$action = $_SERVER['REQUEST_METHOD'];
	$class = new $controller;

	##
	# get parameters
	##
	switch($action) {

		case "GET":
			$class->parameters = isset($uid) ? array("uid" => $uid) : $_GET;
			break;

		case "POST":
			$class->parameters = $_POST;
			break;

		case "PUT":
			# just for dev, please uncomment below
			parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT);
			$class->parameters = $_PUT;
			break;

		case "DELETE":
			if(isset($uid))
			{
				$class->parameters = array("uid" => $uid);
			}
			else
			{
				parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_DELETE);
				$class->parameters = $_DELETE;
			}

			break;

		default:
			$controller->parameters = array();
			break;
	}

	##
	# clean parameters
	##
	$class->parameters = Security::cleanParameters($class->parameters);
	##
	# Create class and call method
	##
	//$class = new $controller;
	if(method_exists($class, $action))
    	die(call_user_func_array(array($class, $action), array_slice($split, 2)));
	else {
		throw new ErrorException("Controller Not Found", 404);
	}

}

?>
