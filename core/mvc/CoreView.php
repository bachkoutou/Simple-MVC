<?php
class CoreView extends ArrayObject
{
	const MESSAGE_TYPE_SUCCESS = 'success';
	const MESSAGE_TYPE_ERROR = 'error';
	const MESSAGE_TYPE_WARNING = 'warning';
	const MESSAGE_TYPE_INFO = 'info';
	/**
     * default extenstion of views
     *
     * @var unknown_type
     */
	private $_extension = null;
	private $_action = null;
	private $_controller = null;
	private $_viewName = null;
	protected $css = array();
	protected $js = array();
	protected $scripts = array();
	protected $message = '';
	
	public  $renderTemplate = AUTO_RENDER_TEMPLATE;


	/**
	 * default template
	 *
	 * @var unknown_type
	 */
	private $_tplFile = null;

	public function __construct($controller, $action = 'list', $extension = '.php')
	{
		parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);

		$this->setController($controller);
		$this->setAction($action);

		$this->setExtension($extension);

		// Check if there is a css file or a js file to include, if yes, include it

		$this->autoIncludeJs();
		$this->autoIncludeCss();
	}

	public function renderTemplate()
	{
		if (file_exists(BUSINESS. DS . TEMPLATES_PATH  . $this->getTemplate()))
        {    
		    include(BUSINESS . DS . TEMPLATES_PATH . $this->getTemplate());
        }    
	}



	public function main()
	{
		$viewName  = ($this->getViewName()) ? $this->getViewName() : $this->getAction();
		$file = BUSINESS . DS .  VIEWS_PATH . $this->getController() . DS . $viewName  . $this->getExtension();
		echo $this->load($file);
	}

	public function render($action = null, $controller = null)
	{
		(!$controller) ? $controller = $this->getController() : 'main';
		if (!$action)  $action = $this->getAction() ;
		$file = BUSINESS . DS .  VIEWS_PATH . $controller . DS . $action  . $this->getExtension();
		echo $this->load($file);
	}

	public function loadBlock($file)
	{
		$file = BUSINESS . DS .  VIEWS_PATH . $this->getController() . DS . $file;
		echo $this->load($file);
	}
	
	public function load($file)
	{
		if (file_exists($file))
		{
			ob_start();
			include($file);
			return ob_get_clean();
		}
	}
	public function setExtension($extension)
	{
		$this->_extension = $extension;
	}

	public function getExtension()
	{
		return $this->_extension;
	}

	public function setTemplate($tpl)
	{
		$this->_tplFile = $tpl;
	}

	public function getTemplate()
	{
		return $this->_tplFile;
	}

	public function setController($controller)
	{
		// remove 'Controller' from the name
		$parts = explode('Controller', $controller);
		$this->_controller = $parts[0];
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function setAction($action)
	{
		$this->_action = $action;
	}

	public function getAction()
	{
		return $this->_action;
	}
	
	public function setViewName($viewName)
	{
		$this->_viewName = $viewName;
	}

	public function getViewName()
	{
		return $this->_viewName;
	}	

	public function getName()
	{
		return $this->getController();
	}

	public function getContextLink()
	{
		return "/?controller=" . $this->getController();
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($message,$type)
	{
		$this->message = new stdClass();
		$this->message->type = $type;
		$this->message->message = str_replace('%20',' ',$message);
	}

	/**
     * Css Manipulation
     */
	public function addCss($file)
	{
		if (!in_array($file, $this->css))
		{
			$this->css[$file] = $file;
		}
	}

	public function removeCss($file)
	{
		if (isset($this->css[$file]))
		{
			unset($this->css[$file]);
		}
	}

	public function renderCss()
	{
		// Render predefined JS
		$this->renderPredefinedCss();

		$output = '';
		if (is_array($this->css) && (0 < count($this->css)))
		{
			foreach ($this->css as $file)
			{
				$output.= "<link rel=\"stylesheet\" href=\"" . $file . "\" type=\"text/css\" media=\"screen\" />\n";
			}
		}
		echo $output;
	}



	/**
     * JS Manipulation
     */
	public function addJs($file)
	{
		if (!in_array($file, $this->js))
		{
			$this->js[$file] = $file;
		}
	}

	public function removeJs($file)
	{
		if (isset($this->js[$file]))
		{
			unset($this->js[$file]);
		}
	}

	public function addScript($script)
	{
		$this->scripts[] =  "<script type=\"text/javascript\">
					$script 
				</script>
				";
	}

	public function renderJs()
	{
		// Render predefined JS
		$this->renderPredefinedJs();

		$output = '';
		// Render User Defined Files
		if (is_array($this->js) && (0 < count($this->js)))
		{
			foreach ($this->js as $file)
			{
				$output.= "<script type=\"text/javascript\" src=\"" . $file . "\"></script>\n";
			}
		}
		echo $output;

		$this->renderScripts();
	}

	public function renderScripts()
	{
		if (is_array($this->scripts) && (0 < count($this->scripts)))
		{
			foreach ($this->scripts as $script)
			{
				echo $script;
			}
		}
	}




	/**
     * checks if there is a js file under scripts, if yes include it
     *
     */
	public function autoIncludeJs()
	{
		// include files in the styles/{module} file
		$file = 'scripts/' . $this->getModule() . '.js';
		if (file_exists(WEB . $file))
		{
			$this->addCss('/' . $file);
		}

		// include file in the styles/{modules}/{controller}.css file
		$file = 'scripts/' . $this->getModule() . '/' . $this->getController() . '.js';
		if (file_exists(WEB . $file))
		{
			$this->addJs('/' . $file);
		}

		// include files in the styles/{module}/{controller}/{action} file
		$file = 'scripts/' . $this->getModule() . '/' . $this->getController() . '/' . $this->getAction() . '.js';
		if (file_exists(WEB . $file))
		{
			$this->addJs('/' . $file);
		}
	}

	/**
	 * checks if there is a css file under styles, if yes include it
	 *
	 */
	public function autoIncludeCss()
	{
		// include files in the styles/{module} file
		$file = 'styles/' . $this->getModule() . '.css';
		if (file_exists(WEB . $file))
		{
			$this->addCss('/' . $file);
		}

		// include file in the styles/{modules}/{controller}.css file
		$file = 'styles/' . $this->getModule() . '/' . $this->getController() . '.css';
		if (file_exists(WEB . $file))
		{
			$this->addCss('/' . $file);
		}

		// include files in the styles/{module}/{controller}/{action} file
		$file = 'styles/' . $this->getModule() . '/' . $this->getController() . '/' . $this->getAction() . '.css';
		if (file_exists(WEB . $file))
		{
			$this->addCss('/' . $file);
		}
	}

	/**
     * Form Check validation
     */

	public function renderFormValidateClass($field)
	{
		echo (ENABLE_FORMCHECK) ? $this->renderFormCheckValidation($field) : '';
	}

	/**
     * try to hint the appropriate formCheck Validator depending on the type of the field
     *
     * @param unknown_type $field
     */
	public function renderFormCheckValidation($field)
	{
		if ('' != (string)$field->required)
		{
			$required = "'required'";

			if ('' != (string) $field->inputTypeConfirm)
			{
				$typeConfirm = ",'" . (string) $field->inputTypeConfirm . "'";
			}

			if ('' != (string) $field->inputTypeTest)
			{
				$testConfirm = ",'" . (string) $field->inputTypeTest . "'";
			}
			else
			{
				// Try to hint the file type
				//$testConfirm = ",'" . $this->formatType($field->Type) . "'";
			}

			return "validate[{$required}{$typeConfirm}{$testConfirm}] text-input";
		}
		return "";
	}


	/**
	 * default field type
	 *
	 * @return unknown
	 */
	public function renderFieldType($field)
	{
		return ('' != (string)$field->inputType) ? (string) $field->inputType : 'text';
	}

	/**
	 * A simple type hint , need optimization
	 *
	 * @param unknown_type $field
	 * @return unknown
	 */
	public function formatType($field)
	{
		$type = (string) $field;
		if (strstr(strtoupper($type),'INT') || strstr(strtoupper($type),'TINYINT') ||  strstr(strtoupper($type),'SMALLINT') || strstr(strtoupper($type),'MEDIUMINT') || strstr(strtoupper($type),'BIGINT') || strstr(strtoupper($type),'FLOAT')
		|| strstr(strtoupper($type),'DOUBLE') || strstr(strtoupper($type),'DECIMAL') || strstr(strtoupper($type),'BIT') || strstr(strtoupper($type),'BOOL'))
		{
			return 'number';
		}
		else
		{
			return 'alphanum';
		}
	}

	public function renderPredefinedJs()
	{
		//$this->addJs('/scripts/jquery-1.3.2.min.js');
		//$this->addScript('jQuery.noConflict();');
	}

	public function renderPredefinedCss()
	{
		// Always add style.css
		//$this->addCss('/styles/style.css');
	}

	public function renderMessages()
	{
		$msg = $this->getMessage();
		if ('' != $msg->message)
		{
			echo '<div class="' . $msg->type .'">' . $msg->message . ' </div>';
		}
	}

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getModule()
    {
        return MODULE;
    }    
}
