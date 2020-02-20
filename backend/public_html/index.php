<?php

##
# Start the session
##
session_start();

ini_set('display_errors', 0);
//error_reporting(E_ALL);

##
# Defines
##
define('DS', "/");
define('_ROOT', realpath(dirname(__FILE__)) . DS);
define('_APPLICATION', _ROOT . '..' . DS . 'application' . DS);
define('_LIBRARIES', _ROOT . '..' . DS . 'libraries' . DS);
define('_INTERFACES', _ROOT . '..' . DS . 'libraries' . DS . 'interfaces' . DS);
define('_MODELS', _ROOT . '..' . DS . 'application' . DS . 'models' . DS);
define('THIRD_PARTY', _LIBRARIES . 'third-party' . DS);

##
# Requires
##
require(_LIBRARIES . 'core' . DS . 'Bootstrap.php');
require(_LIBRARIES . 'core' . DS . 'Controller.php');
require(_LIBRARIES . 'core' . DS . 'Model.php');
require(_LIBRARIES . 'core' . DS . 'View.php');

##
# Config
##
require(_APPLICATION . 'config' . DS . 'Config.php');
global $config;

define('BASE_URL', $config['http_base_url']);
define('MILL_BASE', '/usr/local/mill');

##
# Set default timezone
##
date_default_timezone_set($config['default_timezone']);

##
# Init bootstrap
##
bootstrap();
// try
// {
//     bootstrap();
// }
// catch(ErrorException $e)
// {
//     echo json_encode(array("response" => $e->getCode(), "error" => $e->getCode(),  "data" => array("message" => $e->getMessage())));
// }


?>
