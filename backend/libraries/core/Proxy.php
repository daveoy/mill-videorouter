<?php

class Proxy {

	protected $endpoint;
	protected $requestType = "GET";
	protected $fields;

	public function __construct() {
	}

	// Setters
	public function setEndpoint($endpoint) {
		$this->endpoint = $endpoint;
	}

	public function setRequestType($requestType) {
		$this->requestType = $requestType;
	}

	public function setFields($fields) {
		$this->fields = $fields;
	}

	// execute REST request
	public function execute() {
		return $this->executeRequest();
	}

	private function executeRequest() {
		$ch = curl_init($this->endpoint);
		// customize REST call
		switch($this->requestType) {
			case "GET": default:
				break;
		
			case "POST":
		        curl_setopt($ch, CURLOPT_POST, true);
			 	curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
			  	break;
			case "PUT": case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->requestType);
		     	curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
		      	break;
			}


		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;//json_decode($result);
	}

}
