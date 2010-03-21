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
 * File:        generator.class.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Generator class
 * 
 */
class Generator
{
	/**
	 * Database connection
	 * 
	 * @var Database  Defaults to null. 
	 */
	private $_connection = null;
	/**
	 * Module name
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_module = null;

	/**
	 * Module path
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_modulePath = null;

	/**
	 * default generation actions
	 * 
	 * @var array  Defaults to array('list', 'edit', 'detail'). 
	 */
	private $_actions = array('list', 'edit', 'detail');


	/**
	 * Constructor
	 * 
	 */
	public function __construct($module)
	{

		$this->setModule($module);
		$this->setModulePath($module);
		$this->_connection = new PDODatabase(BACK_DB_HOST, BACK_DB_DATABASE, BACK_DB_USER, BACK_DB_PASSWORD);
		$this->generateMainController();
		$this->generateModuleContainer($module);
        $this->generateMainView();
        $this->generateMainTemplate();
        $this->generateWebRoot($module);
        $this->generateConfigurationFile($module);
        $this->generateAppIniFile();
		$this->generateModuleIniFile($module);
	}


	/**
	 * sets the default module, checks if it is an absolute path or not, if not set the module under 
	 * MF_DEFAULT_FOLDER
	 *
	 * @param string $module
	 */
	public function setModulePath($module)
	{
	    (!Toolbox::checkAbsolutePath($module)) ? $this->_modulePath = MF_DEFAULT_FOLDER . rtrim($module, DS) . DS : $this->_modulePath = rtrim($module, DS) . DS;
	}

	/**
	 * Module path Getter
	 * 
	 * @return string The module path
	 */
	public function getModulePath()
	{
		return $this->_modulePath;
	}

	/**
	 * Module setter 
	 * 
	 * @param  string  $module The module
	 */
	public function setModule($module)
	{
		$this->_module = $module;
	}

	/**
	 * Module Getter
	 * 
	 * @return string the module
	 */
	public function getModule()
	{
		return $this->_module;
	}

	/**
	 * generate an object
	 *
	 * @param string $tableName The associated table name
	 */
	public function generate($table)
	{
		$query = $this->_connection->prepare("SHOW COLUMNS FROM $table->name");
        $query->execute();
        $fields = $query->fetchAll(PDO::FETCH_OBJ);
        if ($fields)
		{
			// Generate the model
            $this->generateBaseModel($fields, $table->name);
			$this->generateModel($fields, $table->name);
            
            // Generate the form
            $this->generateBaseForm($table->name);
            $this->generateForm($table->name);

			// Generate the controller
			$this->generateBaseController($table->name);
			$this->generateController($table->name);
            
			// Generate the main view
			$this->generateBaseView($table->name);
			$this->_generateGlobalView($table->name);
            
			// Generate the views
			foreach ($this->_actions as $view)
			{
				$this->_generateView($view, $fields, $table->name);
			}
		}
	}


	/**
	 * Returns an MVC Template
	 * 
	 * @param  string  $type The template type
	 * @return string The content of the template
	 */
	private function getMVCTemplate($type)
	{
		if (file_exists(MF_TEMPLATES_FOLDER . $this->_module . DS . $type . '.tpl'))
		{
			return Toolbox::getFileContent(MF_TEMPLATES_FOLDER . $this->_module . DS . $type . '.tpl');
		}
		else
		{
			return Toolbox::getFileContent(MF_TEMPLATES_FOLDER . MF_DEFAULT_TEMPLATE_FOLDER . $type . '.tpl');
		}
	}

    /**
     * Generates the Base Model
     * 
     * @param  array    $fields     The properties of the model  
     * @param  string   $tableName  The table name
     */
    private function generateBaseModel($fields, $tableName)
    {
        $baseFileName = $this->_modulePath . MF_MODELS_FOLDER . "Base/Base" . ucfirst($tableName) . "Model.php";
        $modelTemplate = $this->getMVCTemplate('BaseModel');
        $modelTemplate = str_replace('{objectName}', ucfirst($tableName), $modelTemplate);
        $modelTemplate = str_replace('{properties}', $this->_renderModelProperties($fields, $tableName), $modelTemplate);
        $modelTemplate = str_replace('{methods}', $this->_renderModelMethods($fields), $modelTemplate);
        Toolbox::saveFileWithContent($baseFileName, $modelTemplate);
    }
	/**
	 * Generates the model
	 * 
     * @param  array    $fields     The properties of the model  
     * @param  string   $tableName  The table name
	 */
	private function generateModel($fields, $tableName)
	{
        $fileName = $this->_modulePath . MF_MODELS_FOLDER . ucfirst($tableName) . "Model.php";
        
        if (!file_exists($fileName))
        {    
           $modelTemplate = $this->getMVCTemplate('Model');
           $modelTemplate = str_replace('{objectName}', ucfirst($tableName), $modelTemplate);
           Toolbox::saveFileWithContent($fileName, $modelTemplate);
        }

	}

    /**
     * Generates the Base Form
     * 
     * @param  string   $tableName  The table name
     */
    private function generateBaseForm($tableName)
    {
        $baseFileName = $this->_modulePath . MF_FORMS_FOLDER . "Base/Base" . ucfirst($tableName) . "Form.php";
        $formTemplate = $this->getMVCTemplate('BaseForm');
        $formTemplate = str_replace('{objectName}', ucfirst($tableName), $formTemplate);
        Toolbox::saveFileWithContent($baseFileName, $formTemplate);
    }
	/**
	 * Generates the Form
	 * 
     * @param  string   $tableName  The table name
	 */
	private function generateForm($tableName)
	{
        $fileName = $this->_modulePath . MF_FORMS_FOLDER . ucfirst($tableName) . "Form.php";
        
        if (!file_exists($fileName))
        {    
           $formTemplate = $this->getMVCTemplate('Form');
           $formTemplate = str_replace('{objectName}', ucfirst($tableName), $formTemplate);
           Toolbox::saveFileWithContent($fileName, $formTemplate);
        }

	}


    /**
     * Generates the Base Controller
     * 
     * @param  string   $tableName  The table name
     */
    private function generateBaseController($tableName)
    {
        $baseFileName = $this->_modulePath . MF_CONTROLLERS_FOLDER . "Base/Base" . ucfirst($tableName) . "Controller.php";
		$controllerTemplate = $this->getMVCTemplate('BaseController');
		$controllerTemplate = str_replace('{objectName}', ucfirst($tableName), $controllerTemplate);
		Toolbox::saveFileWithContent($baseFileName, $controllerTemplate);
    }
	/**
	 * Generates the Controller
	 * 
     * @param  string   $tableName  The table name
	 */
	private function generateController($tableName)
	{
        $fileName = $this->_modulePath . MF_CONTROLLERS_FOLDER . ucfirst($tableName) . "Controller.php";
        if (!file_exists($fileName))
        {    
            $controllerTemplate = $this->getMVCTemplate('Controller');
            $controllerTemplate = str_replace('{objectName}', ucfirst($tableName), $controllerTemplate);
            Toolbox::saveFileWithContent($fileName, $controllerTemplate);
        }

    }
    /**
     * Generates the module Container
     */
    private function generateModuleContainer($moduleName)
    {
        
        $fileName = $this->_modulePath . MF_CONTAINER_FOLDER . ucfirst($moduleName) . "Container.php";
        if (!file_exists($fileName))
        {    
            $containerTemplate = $this->getMVCTemplate('ModuleContainer');
            $containerTemplate = str_replace('{moduleName}', ucfirst($moduleName), $containerTemplate);
            Toolbox::saveFileWithContent($fileName, $containerTemplate);
        }


    }

    /**
     * Generates the main controller
     */
    private function generateMainController()
    {
        
        $fileName = $this->_modulePath . MF_CONTROLLERS_FOLDER . "MainController.php";
        if (!file_exists($fileName))
        {    
            $controllerTemplate = $this->getMVCTemplate('MainController');
            Toolbox::saveFileWithContent($fileName, $controllerTemplate);
        }


    }
    /**
     * Generates the Base view
     * 
     * @param  string   $tableName  The table name
     */
    private function generateBaseView($tableName)
    {
        $baseFileName = $this->_modulePath . MF_VIEWS_FOLDER . "Base/Base" . ucfirst($tableName) . "View.php";
		$controllerTemplate = $this->getMVCTemplate('BaseView');
		$controllerTemplate = str_replace('{objectName}', ucfirst($tableName), $controllerTemplate);
		Toolbox::saveFileWithContent($baseFileName, $controllerTemplate);
    }


    /**
     * Generates the Main view
     */
    private function generateMainView()
    {
       $fileName = $this->_modulePath . MF_VIEWS_FOLDER . "MainView.php";
        if (!file_exists($fileName))
        {    
            $viewTemplate = $this->getMVCTemplate('MainView');
            Toolbox::saveFileWithContent($fileName, $viewTemplate);
        }
    }

    /**
     * Generates the Global View
     * 
     * @param  string   $tableName  The table name
     */
    private function _generateGlobalView($tableName)
    {
        $fileName = $this->_modulePath . MF_VIEWS_FOLDER . ucfirst($tableName) . ".php";
        if (!file_exists($fileName))
        {    
            $viewsTemplate = $this->getMVCTemplate('View');
            // Replace the object name
            $viewsTemplate = str_replace('{objectName}', ucfirst($tableName), $viewsTemplate);
            Toolbox::saveFileWithContent($fileName, $viewsTemplate);
        }    
    }

    /**
     * Generated the Main template
     */
    private function generateMainTemplate()
    {
        if (!file_exists($this->_modulePath . MF_MVC_TPL_FOLDER . "default.php"))
        {
            Toolbox::saveFileWithContent($this->_modulePath . MF_MVC_TPL_FOLDER . "default.php", $this->getMVCTemplate('Template'));
        }
    }


    /**
     * Generates the view
     * 
     * @param  string  $viewName    The view name 
     * @param  array  $fields       The properties of the model
     * @param  string  $tableName   The table name
     */
    private function _generateView($viewName, $fields, $tableName)
    {
        $fileName = $this->_modulePath . MF_VIEWS_FOLDER . ucfirst($tableName) . DS . $viewName . ".php";
        if (!file_exists($fileName))
        {    
            $viewTemplate = $this->getMVCTemplate('views'. DS . $viewName);
            $viewTemplate = str_replace('{objectName}', $tableName, $viewTemplate);
            Toolbox::saveFileWithContent($fileName, $viewTemplate);
        }    
    }


    /**
     * Renders the model properties
     * 
     * @param  array  $fields       The properties of the model
     * @param  string  $tableName   The table name
     */
    private function _renderModelProperties($fields, $tableName)
    {
        $keys = $this->_renderObjectKeys($fields);
        $fields = $this->_renderObjectFields($fields);
        return
            "
            // Protected - contains the table name - Mandatory or will throw an exception
            protected \$_tableName =  '$tableName' ;
            protected \$_tableKeys = array($keys);

            // Properties
            $fields
            ";
    }

    /**
     * Renders the Model Methods
     *
     * @return string empty string for the moment
     */
    private function _renderModelMethods()
    {
        return '';
    }

    /**
     * Renders the model keys
     * 
     * @param  array  $fields The properties of the model
     */
    private function _renderObjectKeys($fields)
    {
        $keys = array();
        foreach ($fields as $obj)
        {
            if ('PRI' == $obj->Key)
            {
                $keys[] = $obj->Field;
            }
        }
        if (count($keys) > 1)
        {
            return '"' . join('","', $keys) . '"';
        }
        else if (count($keys) > 0 )
        {
            return '"' . $keys[0] . '"';
        }

    }

    /**
     * Renders the fields of the model
     * 
     * @param  array $fields The properties of the model
     */
    private function _renderObjectFields($fields)
    {
        $output = "\t// Public properties - Mapped to the table fields \n";
        foreach ($fields as $obj)
        {
            $output.= "\tpublic \$" . $obj->Field . " = null;\n";
        }
        return $output;
    }


    /**
     * Generates the webroot folder
     * 
     * @param  string  $moduleName  The module name
     */
    private function generateWebRoot($moduleName)
    {
        $baseFolder = MF_ADMIN_FOLDER . '..' . DS . "document_" . $moduleName . DS;
        $fileName = $baseFolder . "index.php";
        if (!file_exists($fileName))
        {    
            mkdir($baseFolder, 0777);
            $indexTemplate = Toolbox::getFileContent(MF_ADMIN_FOLDER . '/templates/default/web/document_template/index.tpl');
            mkdir($baseFolder . 'images' . DS, 0777);
            mkdir($baseFolder . 'scripts' . DS, 0777);
            mkdir($baseFolder . 'styles' . DS, 0777);
            $indexTemplate = str_replace('{moduleName}', $moduleName, $indexTemplate);
            Toolbox::saveFileWithContent($fileName, $indexTemplate);
        }    
    }    

    /**
     * Generates the configuration file
     * 
     * @param  string  $moduleName  The module name
     */
    private function generateConfigurationFile($moduleName)
    {
        $baseFolder = MF_ADMIN_FOLDER . '..' . DS . "conf" . DS;
        $fileName = $baseFolder . "configuration_" . $moduleName . ".php";
        if (!file_exists($fileName))
        {    
            $confTemplate = $this->getMVCTemplate('configuration');
            $confTemplate = str_replace('{moduleName}', $moduleName, $confTemplate);
            Toolbox::saveFileWithContent($fileName, $confTemplate);
        }    
    } 

    /**
     * Generates the global framework file
     * 
     */
    private function generateAppIniFile()
    {
        $baseFolder = MF_ADMIN_FOLDER . '..' . DS . "business" . DS . "conf" . DS; 
        $fileName = $baseFolder . "apps.ini";
        if (!file_exists($fileName))
        {    
            $confTemplate = $this->getMVCTemplate('AppIniFile');
            $confTemplate = str_replace('{moduleName}', $moduleName, $confTemplate);
            $confTemplate = str_replace('{mysqlHost}', BACK_DB_HOST, $confTemplate);
            $confTemplate = str_replace('{mysqlUser}', BACK_DB_USER, $confTemplate);
            $confTemplate = str_replace('{mysqlPassword}', BACK_DB_PASSWORD, $confTemplate);
            $confTemplate = str_replace('{mysqlDatabase}', BACK_DB_DATABASE, $confTemplate);
            Toolbox::saveFileWithContent($fileName, $confTemplate);
        }    
    }


    /**
     * Generates the module config file
     * 
     * @param  string  $moduleName  The module name
     */
    private function generateModuleIniFile($moduleName)
    {
        $baseFolder = MF_ADMIN_FOLDER . '..' . DS . "business" . DS . $moduleName . DS . "conf" . DS; 
        $fileName = $baseFolder . $moduleName . ".ini";
        if (!file_exists($fileName))
        {    
            $confTemplate = $this->getMVCTemplate('ModuleIniFile');
            $confTemplate = str_replace('{moduleName}', $moduleName, $confTemplate);
            Toolbox::saveFileWithContent($fileName, $confTemplate);
        }    
    } 

}
