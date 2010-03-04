<?php 
class viewFactory
{
    public static function getview($viewName,$actionName)
    {
    	$viewFile = BUSINESS . DS . VIEWS_PATH . $viewName . '.php';

		$viewNameView = ucfirst($viewName) . 'View';
        if (file_exists($viewFile))
        {           
            require_once($viewFile);
            if(class_exists($viewNameView))
            {
                return new $viewNameView($viewName,$actionName);
            }
        }
        return new MainView($viewName);
    }
}
