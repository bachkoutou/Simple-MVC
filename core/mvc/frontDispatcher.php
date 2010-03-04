<?php
class frontDispatcher
{

    protected $_controller, $_action, $_params;
    static $_instance;

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->_controller = !empty($_REQUEST['controller']) ? $_REQUEST['controller'] . 'Controller' : 'mainController';
        $this->_action = !empty($_REQUEST['action']) ? $_REQUEST['action']  : 'index';
        $this->_params = $_REQUEST;
        $this->router = new Router();
        $this->router->setController($this->_controller);
        $this->router->setAction($this->_action);
        $this->router->setParams($_REQUEST);
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function route()
    {
        $this->router->route();
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function getAction()
    {
        return $this->_action;
    }
}
