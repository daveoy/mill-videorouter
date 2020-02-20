<?php

$config = array();
$config['http_base_url'] = getenv("CMS_HTTP_BASE_URL");
$config['https_base_url'] = getenv("CMS_HTTPS_BASE_URL");
$config['default_controller'] = 'main';
$config['default_action'] = 'index';
$config['error_controller'] = 'error';

$config['default_timezone'] = "Europe/London";

# The Mill Location
$config['the_mill']['location'] = array(
	1 => "London",
	2 => "New York",
	4 => "Los Angeles",
	6 => "Chicago"
);

# Database
$config['database']['mysql']['videorouter']['host'] = getenv('DB_HOST');
$config['database']['mysql']['videorouter']['user'] = getenv('DB_USER');
$config['database']['mysql']['videorouter']['password'] = getenv('DB_PASSWORD');
$config['database']['mysql']['videorouter']['dbname'] = getenv('DB_NAME');

# API
$config['api']['endpoint_http_base'] = getenv('API_HTTP_BASE_URL');
$config['api']['endpoint_https_base'] = getenv('API_HTTPS_BASE_URL');
