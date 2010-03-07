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
 * File:        Router.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * The router class
 * 
 */
class Router
{

    /**
     * Controller name
     * 
     * @var string
     */
    protected $_controller;
    /**
     * Action name
     * 
     * @var string
     */
    protected $_action;

    /**
     * Request params
     * 
     * @var array
     */
    protected $_params;
    
   /**
     * Controller name Setter
     * 
     * @param  string  $controller The controller name 
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    /**
     * Action Setter 
     * 
     * @param  string  $action The action name
     */
    public function setAction($action)
    {
        $this->_action = $action;
    }

    /**
     * Params Setter
     * 
     * @param  array  $params Additional params
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }    

    /**
     * Executes a controller action
     * @Exception thrown if the action does not exist
     */
    public function route()
    {    
        // get the controller Object
        $controller = controllerFactory::getController($this->_controller, $this->_action);
		$action = $this->_action . 'Action';		
        if (method_exists($controller, $action))
        {
        	/**
        	 * Hook mechanism
        	 */
        	// execute always action every time, 
        	$controller->alwaysAction();
        	
        	// Execute beforeAction if exists 
            $beforeAction = "before" . ucfirst($action);
        	if (method_exists($controller, $beforeAction))
        	{
        		$controller->$beforeAction();
        	}
        	
        	// Execute the current action
            $controller->$action();
        	
            // Execute after if exists 
            $afterAction = "after" . ucfirst($action);
            if (method_exists($controller, $afterAction))
        	{
        		$controller->$afterAction();
        	}            
            
        	// Execute the view action . _executeView will indicate which view to execute
        	// if we indicate it via Controller::setView(view);
        	if (!$controller->view->getViewName())
        	{
                if (method_exists($controller->view, $action))
                {   
        		    $controller->view->$action();
                }
        	}
        	else 
        	{
        		$controller->view->{$controller->view->getViewName()}();
        	}
        	// render the template
        	if (true === $controller->view->renderTemplate)
            {
            	$controller->view->renderTemplate();
            }
            else
            {
                $controller->view->main();
            }    
                
        }
        else
        {
            throw new Exception('you have to specify a valid action for the controller ' . $this->_controller);
        }
    }
    

    /**
     * Params Getter
     * 
     * @return array params
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Controller name Getter
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
     * @return string The action name
     */
    public function getAction()
    {
        return $this->_action;
    }
}
