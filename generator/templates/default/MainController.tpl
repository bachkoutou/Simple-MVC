<?php
class MainController extends CoreController 
{
	public function __construct($moduleName, $controllerName, $actionName = "list")
	{
		
		parent::__construct($moduleName, $controllerName, $actionName);
		// specify three column template
		$this->view->setTemplate('default.php');
	}
    
	public function index()
    {
       //echo $this->view->render();
    }
    
}
