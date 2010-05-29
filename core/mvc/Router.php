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
    public function route(Container $container)
    {
        // general configuration
        $generalConfig =  isset($container['Config']['general']) ? $container['Config']['general'] : array();
        // per mvc component configuration
        $controllerConfig = array_merge($generalConfig, isset($container['Config']['controller']) ? $container['Config']['controller'] : array());
        $modelConfig =  array_merge($generalConfig, isset($container['Config']['model']) ? $container['Config']['model'] : array());
        $viewConfig =  array_merge($generalConfig, isset($container['Config']['view']) ? $container['Config']['view'] : array());
        // get the controller Object
        $controller = controllerFactory::getController($this->_controller, $controllerConfig);
        // inject any dependencies to the controller
        $container['Router'] = $this;
        $controller->setActionName($this->_action);
        $usedModels = $controller->getUsedModels();
        $database =  $container['database'];
        $controller->setContainer($container);
        if (0 < count($usedModels))
        {
            foreach ($usedModels as $model)
            {
                $modelName = $model . 'Model';
                $controller->setModel(modelFactory::getModel($modelName, $database, $modelConfig));
            }

        }
        $viewName = substr($this->_controller,0, -10);
        $view = viewFactory::getView($viewName, $viewConfig);
        $view->setController($this->_controller);
        $view->setViewName($this->_action);
        $view->setExtension('.php');
        $view->setTemplate('default.php');
        $controller->setView($view);

		$action = $this->_action . 'Action';
        if (method_exists($controller, $action))
        {
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
        	$setViewName = $controller->view->getViewName();
            if (method_exists($controller->view, $setViewName))
        	{
        		$controller->view->$setViewName();
        	}
        	else 
        	{
                if (method_exists($controller->view, $action))
                {
        		    $controller->view->$action();
                }
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
     * Redirects to a new address based on controller/action arguments
     * 
     * @param  string $action      The action to redirect in
     * @param  string $controller  The controller, Optional, defaults to current controller. 
     * @param  string $message     A message to add in the request, Optional, defaults to ''. 
     * @param  string $messageType A message type, Optional, defaults to 'success'. 
     * @param  array  $params      An array of additional parameters, Optional, defaults to null. 
     */
    public function redirect($action, $controller = null, $message = '', $messageType = 'success', $params = null)
    {
        if (!$action)
        {
            return false;
        }

        if('' != $message)
        {
            $message= '&message=' . $message . '&messageType=' . $messageType;
        }
        if ($params)
        {
            $paramsString = '&' . http_build_query($params);
        }    
        else
        {
            $paramsString = '';
        }   
        if (method_exists($controller . 'Controller', $action . 'Action'))
        {
            header("location: /?controller=$controller&action={$action}{$message}{$paramsString}");exit();
            exit();
        }
        return false;

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
