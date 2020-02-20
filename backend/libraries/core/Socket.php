<?php

class Socket {
	
	protected $ip;
	protected $port;
	protected $socket;
	protected $socketConnection;
	protected $response;
	protected $readBytes;

	public function __construct() {
		global $config;
		
		# socket 
		$this->ip = $config['socket']['ip'];
		$this->port = $config['socket']['port'];
		$this->readBytes = 32; //2048;
	}

	# connect to socket
	private function connect() 
	{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) 
		{
		    //echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		    throw new ErrorException("socket_create() failed: reason: " . socket_strerror(socket_last_error()), 500);
		}

		$this->socketConnection = socket_connect($this->socket, $this->ip, $this->port);
		if ($this->socketConnection === false) 
		{
		     throw new ErrorException("socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)), 500);
		}
	}

	public function send($arguments = null)
	{
		global $config;
		if(is_null($arguments))
			throw new ErrorException("List of arguments can't be null", 500);

		# connect to socket
		$this->connect();

		// # send NULL instruction (THIS IS LAME!)
		// socket_write($this->socket, "N             ", 128);
		// socket_read($this->socket, 128);
		if($config['telnet'] == false)
		{
			if(strlen($arguments) < 12)
			{
				for($i = 0; $i < (12 - strlen($arguments)); $i++)
				{
					$arguments .= "\0";
				}
			}
		}
		
		# send arguments to socket
		socket_write($this->socket, $arguments, strlen($arguments));

		# read response
		sleep(2);
		if (false !== ($buf = socket_read($this->socket, $this->readBytes))) 
		{
			$this->response = array("OK: Setting route completed"); //true; // router alive
		}
		//while ($out = socket_read($this->socket, $this->readBytes)) {
		//	$this->response .= $out;
		//}

		//if(!is_null($this->response))
		//{
		//	# cleanup response
		//	$this->response = rtrim($this->response);
		//	$this->response = ltrim($this->response);
		//	$this->response = json_decode($this->response);
		//}
		//else
		//{
		//	# Nothing to decode
		//	$this->response = "Nothing to decode";
		//}

		if(!$this->response)
		{
			# Nothing to decode
			$this->response = array("Nothing to decode");
		}

		# close socket connection
		socket_close($this->socket);

		return $this->response;
	}


}
