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
 * File:        modelFactory.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Model Factory 
 * 
 */
class modelFactory
{
    /**
     * Returns the model based on the model name
     * Model Classname should be under BUSINESS/MODEL_PATH
     * 
     * @param  string  $modelName       The model name
     * @param  PDODatabase $database    The database instance
     * @param array $configuration  a configuration array Defaults to array
     * @return CoreModel The model on sucess and NULL otherwise
     */
    public static function getModel($modelName, PDODatabase $database, array $configuration = array())
    {

        $modelFile = BUSINESS . DS . MODELS_PATH . DS . $modelName . '.php';
        if (file_exists($modelFile))
        {            
            require_once($modelFile);
            if(class_exists($modelName))
            {
                return new $modelName($database, $configuration);
            }
        }
        return null;
    }
}
