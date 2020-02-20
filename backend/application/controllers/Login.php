<?php
require_once _MODELS . "Login.php";

class Login extends Controller
{
	
    public function __construct()
    {
        parent::__construct();
        $this->template = $this->getView('router/index');
    }

    public function GET() 
    {
        throw new ErrorException("Not Implemented" , "501");
    }

    public function POST() 
    {
        # get credentials 
        $username = isset($this->parameters['username']) ? $this->parameters['username'] : null;
        $password = isset($this->parameters['password']) ? $this->parameters['password'] : null;

        if(is_null($username) || is_null($password))
            throw new ErrorException("Wrong username or password" , "500");

        # instantiate LoginModel
        $loginModel = new LoginModel();

        # login
        $loginDetails = $loginModel->login(
            array(
                "username" => $username, 
                "password" => $password
            )
        );
        
        $response = array('response' => 200, 'error' => 0, 'data' => $loginDetails);
        $this->template->set('response', $response);
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
