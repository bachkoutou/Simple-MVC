<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:        frontDispatcher.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Front dispatcher class
 * 
 */
class frontDispatcher
{

    /**
     * controller name.
     * 
     * @var string
     */
    protected $_controller;
    
    /**
     * action name.
     * 
     * @var string
     */
    protected $_action;
    
    /**
     * request params
     * 
     * @var array
     */
    protected $_params;

    /**
     * files array (will be mapped
     * from files) Defaults to array
     * 
     * @var array 
     */
    protected $files = array();

    /**
     * the dispatcher instance
     * 
     * @var frontDispatcher
     */
    static $_instance;
    
    /**
     * Returns the instance of the dispatcher
     * 
     * @return frontDispatcher the instance
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * private constructor
     * 
     */
    private function __construct()
    {
        // TODO : wait for PHP support for REQUEST in filter_array_input 
        // $clean = Toolbox::cleanParameters($_REQUEST);
        $clean = $_REQUEST;
        $this->_controller = !empty($clean['controller']) ? $clean['controller'] . 'Controller' : 'mainController';
        $this->_action = !empty($clean['action']) ? $clean['action']  : 'index';
        $this->_params = $clean;
        
        // get any files
        if (isset($_FILES) && count($_FILES))
        {
            $this->setFiles($_FILES);
        }
        $this->router  = new Router();
        $this->router->setController($this->_controller);
        $this->router->setAction($this->_action);
        $this->router->setParams($clean);
    }
   
    /**
     * Route function
     *
     * @param Container $container The injection Container 
     */
    public function route(Container $container)
    {
        $container['Dispatcher'] = $this;
        $this->router->route($container);
    }
    
    /**
     * Returns the router object
     * 
     * @return Router the router
     */
    public function getRouter()
    {
        return $this->router;
    }    

    /**
     * Params Getter
     * 
     * @return array the params
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Files Setter
     * 
     * @param  array  $files array of files
     * should have the same structure as $_FILES
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * Files Getter
     * 
     * @return array a list of files
     */
    public function getFiles()
    {
        return $this->files;
    }


    /**
     * Controller Getter
     * 
     * @return string the controller name
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Action Getter
     * 
     * @return string the action
     */
    public function getAction()
    {
        return $this->_action;
    }
}
