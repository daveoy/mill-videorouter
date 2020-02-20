<?php

$config = array();
$config['http_base_url'] = getenv("API_HTTP_BASE_URL");
$config['https_base_url'] = getenv("API_HTTPS_BASE_URL");
$config['default_controller'] = 'main';
$config['default_action'] = 'index';
$config['error_controller'] = 'error';

$config['default_timezone'] = "Europe/London";

# Dev Mode
$config['dev'] = false;

# Database
$config['database']['mysql']['videorouter']['host'] = getenv('DB_HOST');
$config['database']['mysql']['videorouter']['user'] = getenv('DB_USER');
$config['database']['mysql']['videorouter']['password'] = getenv('DB_PASSWORD');
$config['database']['mysql']['videorouter']['dbname'] = getenv('DB_NAME');

# Telnet
$config['telnet'] = true;
$config['socket']['ip'] = getenv('VIDEOHUB_IP');
$config['socket']['port'] = 9990;

# Cache
$config['cache']['ip'] = getenv('CACHE_IP');
$config['cache']['port'] = 11211;

# The Mill Location
$config['the_mill']['location'] = array(
	1 => "London",
	2 => "New York",
	4 => "Los Angeles",
	6 => "Chicago"
);

# Lock Permissions
$config['admin_organizational_units'] = array("Engineers", "MCR", "Misc");
$config['permissions'] = array("admin" => "Administrator", "user" => "User");
