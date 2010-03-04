<?php
class Router
{

    protected $_controller, $_action, $_params;
    
   /**
     * TODO: short description.
     * 
     * @param  mixed  $controller 
     * @return TODO
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    /**
     * TODO: short description.
     * 
     * @param  array  $action 
     * @return TODO
     */
    public function setAction($action)
    {
        $this->_action = $action;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $params 
     * @return TODO
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }    

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
