<?php

class View {

	private $pageVars = array();
	private $template;

	public function __construct($template)
	{
		$this->template = _APPLICATION .'views/'. $template .'.php';
	}

	public function set($var, $val)
	{
		$this->pageVars[$var] = $val;
	}

	public function render()
	{
		extract($this->pageVars);

		ob_start();
		// require("/var/www/mill/videorouter/public_html/head.php");
		// require(_APPLICATION . "../public_html/head.php");
		require($this->template);
		echo ob_get_clean();
	}
    
}

?>