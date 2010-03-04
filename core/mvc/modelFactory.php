<?php 
class modelFactory
{
    public static function getModel($modelName)
    {
       	$modelFile = BUSINESS . DS . MODELS_PATH . DS . $modelName . '.php';
        if (file_exists($modelFile))
        {            
            require_once($modelFile);
            if(class_exists($modelName))
            {
                return new $modelName();
            }
        }
        return null;
    }
}
