<?php

class Controller {
    protected $template;
    public $parameters;
    public $database;

    public function __construct()
    {
        global $config;
        $this->template = $this->getView('main/index');
    }

    public function isPost()
    {
        if(!empty($_POST)) {
            return true;
        }
        return false;
    }

    public function redirect($loc)
    {
		global $config;
		header('Location: '. $config['base_url'] . $loc);
    }

    public function getModel($modelName)
    {
        require(_APPLICATION .'models' . DS . ucfirst($modelName) .'.php');
        $model = new $modelName;
        return $model;
    }

    public function getView($viewName)
    {
    	
        $view = new View($viewName);
        return $view;
    }
    
}

?>
