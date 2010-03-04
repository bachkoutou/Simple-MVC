<?php
class CoreController implements IController
{
	/**
	 * The core view object 
	 *
	 * @var mainview
	 */
	public $view = null;
	
	// String executeView will tell if we want to bypass the view
	private $_viewName = null;
	
	public $request;
	
	private $_controllerName = null;
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

	public function setControllerName($controllerName)
	{
		$parts = explode('Controller', $controllerName);
		$this->_controllerName = $parts[0];
	}

	public function getControllerName()
	{
		return $this->_controllerName;
	}

	public function setActionName($actionName)
	{
		$this->_actionName = $actionName;
	}

	public function getActionName()
	{
		return $this->_actionName;
	}

}
