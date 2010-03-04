<?php 
class Generator
{
	private $_connection = null;
	private $_module = null;
	private $_modulePath = null;
	private $menusNodes = array();
	private $_actions = array('list', 'edit', 'detail');


	public function __construct($module)
	{

		$this->setModule($module);
		$this->setModulePath($module);

		$this->_connection = new PDODatabase(BACK_DB_HOST, BACK_DB_DATABASE, BACK_DB_USER, BACK_DB_PASSWORD);
		$this->generateMainController();
		$this->generateMainView();
		$this->generateMainTemplate();
        $this->generateWebRoot($module);
        $this->generateConfigurationFile($module);
	}


	/**
	 * sets the default module, checks if it is an absolute path or not, if not set the module under 
	 * MF_DEFAULT_FOLDER
	 *
	 * @param string $module
	 */
	public function setModulePath($module)
	{
	(!Toolbox::isAbsolutePath($module)) ? $this->_modulePath = MF_DEFAULT_FOLDER . rtrim($module, DS) . DS : $this->_modulePath = rtrim($module, DS) . DS;
	}

	public function getModulePath()
	{
		return $this->_modulePath;
	}

	public function setModule($module)
	{
		$this->_module = $module;
	}
	public function getModule()
	{
		return $this->_module;
	}


	/**
	 * generate an object
	 *
	 * @param unknown_type $tableName
	 */
	public function generate($table)
	{
		// Requête "Select" retourne un jeu de résultats
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
     * TODO: short description.
     * 
     * @param  mixed  $fields    
     * @param  mixed  $tableName 
     * @return TODO
     */
    private function generateBaseModel($fields, $tableName)
    {
        $baseFileName = $this->_modulePath . MF_MODELS_FOLDER . "Base/Base" . ucfirst($tableName) . "Model.php";
        $modelTemplate = $this->getMVCTemplate('BaseModel');
        $modelTemplate = str_replace('{objectName}', ucfirst($tableName), $modelTemplate);
        $modelTemplate = str_replace('{properties}', $this->_renderModelProperties($fields, $tableName), $modelTemplate);
        $modelTemplate = str_replace('{methods}', $this->_renderModelMethods($fields), $modelTemplate);
        Toolbox::saveToFile($baseFileName, $modelTemplate);
    }
	/**
	 * TODO: short description.
	 * 
	 * @param  mixed  $fields    
	 * @param  mixed  $tableName 
	 * @return TODO
	 */
	private function generateModel($fields, $tableName)
	{
        $fileName = $this->_modulePath . MF_MODELS_FOLDER . ucfirst($tableName) . "Model.php";
        
        if (!file_exists($fileName))
        {    
           $modelTemplate = $this->getMVCTemplate('Model');
           $modelTemplate = str_replace('{objectName}', ucfirst($tableName), $modelTemplate);
           Toolbox::saveToFile($fileName, $modelTemplate);
        }

	}

    /**
     * TODO: short description.
     * 
     * @param  mixed  $fields    
     * @param  mixed  $tableName 
     * @return TODO
     */
    private function generateBaseForm($tableName)
    {
        $baseFileName = $this->_modulePath . MF_FORMS_FOLDER . "Base/Base" . ucfirst($tableName) . "Form.php";
        $formTemplate = $this->getMVCTemplate('BaseForm');
        $formTemplate = str_replace('{objectName}', ucfirst($tableName), $formTemplate);
        Toolbox::saveToFile($baseFileName, $formTemplate);
    }
	/**
	 * TODO: short description.
	 * 
	 * @param  mixed  $fields    
	 * @param  mixed  $tableName 
	 * @return TODO
	 */
	private function generateForm($tableName)
	{
        $fileName = $this->_modulePath . MF_FORMS_FOLDER . ucfirst($tableName) . "Form.php";
        
        if (!file_exists($fileName))
        {    
           $formTemplate = $this->getMVCTemplate('Form');
           $formTemplate = str_replace('{objectName}', ucfirst($tableName), $formTemplate);
           Toolbox::saveToFile($fileName, $formTemplate);
        }

	}


    /**
     * TODO: short description.
     * 
     * @param  mixed  $tableName 
     * @return TODO
     */
    private function generateBaseController($tableName)
    {
        $baseFileName = $this->_modulePath . MF_CONTROLLERS_FOLDER . "Base/Base" . ucfirst($tableName) . "Controller.php";
		$controllerTemplate = $this->getMVCTemplate('BaseController');
		$controllerTemplate = str_replace('{objectName}', ucfirst($tableName), $controllerTemplate);
		Toolbox::saveToFile($baseFileName, $controllerTemplate);
    }
	/**
	 * TODO: short description.
	 * 
	 * @param  mixed  $tableName 
	 * @return TODO
	 */
	private function generateController($tableName)
	{
        $fileName = $this->_modulePath . MF_CONTROLLERS_FOLDER . ucfirst($tableName) . "Controller.php";
        if (!file_exists($fileName))
        {    
            $controllerTemplate = $this->getMVCTemplate('Controller');
            $controllerTemplate = str_replace('{objectName}', ucfirst($tableName), $controllerTemplate);
            Toolbox::saveToFile($fileName, $controllerTemplate);
        }

    }

    private function generateMainController()
    {
        
        $fileName = $this->_modulePath . MF_CONTROLLERS_FOLDER . "MainController.php";
        if (!file_exists($fileName))
        {    
            $controllerTemplate = $this->getMVCTemplate('MainController');
            Toolbox::saveToFile($fileName, $controllerTemplate);
        }


    }
    /**
     * TODO: short description.
     * 
     * @param  mixed  $tableName 
     * @return TODO
     */
    private function generateBaseView($tableName)
    {
        $baseFileName = $this->_modulePath . MF_VIEWS_FOLDER . "Base/Base" . ucfirst($tableName) . "View.php";
		$controllerTemplate = $this->getMVCTemplate('BaseView');
		$controllerTemplate = str_replace('{objectName}', ucfirst($tableName), $controllerTemplate);
		Toolbox::saveToFile($baseFileName, $controllerTemplate);
    }


    private function generateMainView()
    {
       $fileName = $this->_modulePath . MF_VIEWS_FOLDER . "MainView.php";
        if (!file_exists($fileName))
        {    
            $viewTemplate = $this->getMVCTemplate('MainView');
            Toolbox::saveToFile($fileName, $viewTemplate);
        }

    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $tableName 
     * @return TODO
     */
    private function _generateGlobalView($tableName)
    {
        $fileName = $this->_modulePath . MF_VIEWS_FOLDER . ucfirst($tableName) . ".php";
        if (!file_exists($fileName))
        {    
            $viewsTemplate = $this->getMVCTemplate('View');
            // Replace the object name
            $viewsTemplate = str_replace('{objectName}', ucfirst($tableName), $viewsTemplate);
            Toolbox::saveToFile($fileName, $viewsTemplate);
        }    
    }

    private function generateMainTemplate()
    {
        if (!file_exists($this->_modulePath . MF_MVC_TPL_FOLDER . "default.php"))
        {
            Toolbox::saveToFile($this->_modulePath . MF_MVC_TPL_FOLDER . "default.php", $this->getMVCTemplate('Template'));
        }
    }


    private function _generateView($viewName, $fields, $tableName)
    {
        $fileName = $this->_modulePath . MF_VIEWS_FOLDER . ucfirst($tableName) . DS . $viewName . ".php";
        if (!file_exists($fileName))
        {    
            $viewTemplate = $this->getMVCTemplate('views'. DS . $viewName);
            $viewTemplate = str_replace('{objectName}', $tableName, $viewTemplate);
            Toolbox::saveToFile($fileName, $viewTemplate);
        }    
    }


    private function _renderModelProperties($fields, $tableName)
    {
        $keys = $this->_renderObjectKeys($fields);
        $fields = $this->_renderObjectFields($fields);
        return
            "
            // Protected - contains the table name - Mandatory or will throw an exception
            protected \$_tableName =  '$tableName' ;
        protected \$_tableKeys = array($keys);

        // list of rendered fields
        $fields

            ";
    }

    /**
     * TODO : implement
     *
     * @return unknown
     */
    private function _renderModelMethods()
    {
        return '';
    }

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

    private function _renderObjectFields($fields)
    {
        $output = "// Public properties - Mapped to the table fields \n";
        foreach ($fields as $obj)
        {
            $output.= "\tpublic \$" . $obj->Field . " = null;\n";
        }
        return $output;
    }


    /**
     * TODO: short description.
     * 
     * @return TODO
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
            Toolbox::saveToFile($fileName, $indexTemplate);
        }    
    }    

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    private function generateConfigurationFile($moduleName)
    {
        $baseFolder = MF_ADMIN_FOLDER . '..' . DS . "conf" . DS;
        $fileName = $baseFolder . "configuration_" . $moduleName . ".php";
        if (!file_exists($fileName))
        {    
            $confTemplate = $this->getMVCTemplate('configuration');
            $confTemplate = str_replace('{moduleName}', $moduleName, $confTemplate);
            $confTemplate = str_replace('{mysqlHost}', BACK_DB_HOST, $confTemplate);
            $confTemplate = str_replace('{mysqlUser}', BACK_DB_USER, $confTemplate);
            $confTemplate = str_replace('{mysqlPassword}', BACK_DB_PASSWORD, $confTemplate);
            $confTemplate = str_replace('{mysqlDatabase}', BACK_DB_DATABASE, $confTemplate);
            Toolbox::saveToFile($fileName, $confTemplate);
        }    
    }    

}
