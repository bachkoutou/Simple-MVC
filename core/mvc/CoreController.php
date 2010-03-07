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
 * File:        CoreController.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents the core controller
 * 
 */
class CoreController implements IController
{
	/**
	 * The core view object 
	 *
	 * @var mainview
	 */
	public $view = null;
	
	/**
	 * the view name 
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_viewName = null;
	
	/**
	 * the request
	 * 
	 * @var array
	 */
	public $request;
	
	/**
	 * the controller name
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_controllerName = null;

	/**
	 * The action name
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_actionName = null;

    /**
     * array of the used variables. must be overriden
     * by child if need to access models
     *
     * @var array
     */
	protected $_useModels = array();
	
    /**
     * Initialises the models if any
     * Initialises the view
     *
     * @param string $controllerName the name of the controller | default : 'main'
     */
	public function __construct($controllerName = 'main', $actionName = 'list')
	{
		$dispatcher = frontDispatcher::getInstance();
		$this->request = $dispatcher->getParams();
		$this->setControllerName($controllerName);
		$this->setActionName($actionName);

		// initialize models
        // include relationModel if exists
        if (class_exists('relationModel'))
        {
            $this->relationModel = new relationModel($modelName);
        }    

		if (0 < count($this->_useModels))
		{
			foreach ($this->_useModels as $model)
			{
				$modelName = $model . 'Model';
				$this->$modelName = modelFactory::getModel($modelName);
			}

		}
		// Generate the view
		$viewName = $this->_controllerName;
		$this->view = viewFactory::getView($viewName, $this->_actionName);
		$this->view->setExtension('.php');
		$this->view->setTemplate('default.php');
	}

	/**
     * always function, to be executed every time
     *
     */
	public function alwaysAction()
	{
		$dispatcher = frontDispatcher::getInstance();
		$this->view->setMessage(
		Toolbox::getArrayParameter($dispatcher->getParams(),'message',''),
		Toolbox::getArrayParameter($dispatcher->getParams(),'messageType','success')
		);
	}

	/**
	 * Redirects to a new address based on controller/action arguments
	 * 
	 * @param  string $action      The action to redirect in
	 * @param  string $controller  The controller, Optional, defaults to current controller. 
	 * @param  string $message     A message to add in the request, Optional, defaults to ''. 
	 * @param  string $messageType A message type, Optional, defaults to 'success'. 
	 * @param  array  $params      An array of additional parameters, Optional, defaults to null. 
	 * @return TODO
	 */
	public function redirect($action, $controller = null, $message = '', $messageType = 'success', $params = null)
	{
		if (!$action)
		{
			return false;
		}

		if(!$controller)
		{
			$controller = $this->getControllerName();
		}
		if('' != $message)
		{
			$message= '&message=' . $message . '&messageType=' . $messageType;
		}
        if ($params)
        {
            $paramsString = http_build_query($params);
        }    
        else
        {
            $paramsString = '';
        }    
		if (method_exists($this, $action . 'Action'))
		{
			header("location: /?controller=$controller&action=$action&$message&$paramsString");
			exit();
		}
		return false;

	}

	/**
	 * Controller name Setter
	 * 
	 * @param  string  $controllerName The controller name - including the "Controller" suffix
	 */
	public function setControllerName($controllerName)
	{
		$parts = explode('Controller', $controllerName);
		$this->_controllerName = $parts[0];
	}

	/**
	 * Controller name Getter
	 * 
	 * @return string The controller name
	 */
	public function getControllerName()
	{
		return $this->_controllerName;
	}

	/**
	 * Action name Setter
	 * 
	 * @param  string  $actionName The name of the action
	 */
	public function setActionName($actionName)
	{
		$this->_actionName = $actionName;
	}

	/**
	 * Action name Getter
	 * 
	 * @return string The action name
	 */
	public function getActionName()
	{
		return $this->_actionName;
	}

}
