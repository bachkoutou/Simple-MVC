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
 * File:        CoreView.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * The Core View
 * 
 */
namespace Core\Mvc;
class View extends \ArrayObject
{

    /**
     * Message Type Success Constant
     *
     * @var string Defaults to 'success'
     */
	const MESSAGE_TYPE_SUCCESS = 'success';
    
    /**
     * Message Type Error Constant
     *
     * @var string Defaults to 'error'
     */
	const MESSAGE_TYPE_ERROR = 'error';
    
    /**
     * Message Type Warning Constant
     *
     * @var string Defaults to 'warning'
     */
	const MESSAGE_TYPE_WARNING = 'warning';

    /**
     * Message Type Info Constant
     *
     * @var string Defaults to 'error'
     */
	const MESSAGE_TYPE_INFO = 'info';
	
    /**
     * default extenstion of views
     *
     * @var string
     */
	private $_extension = null;

	/**
	 * The action name
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_action = null;

	/**
	 * The controller name
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_controller = null;

	/**
	 * The view name
	 * 
	 * @var string  Defaults to null. 
	 */
	private $_viewName = null;

	/**
	 * an array of The css files included
	 * 
	 * @var array  Defaults to array(). 
	 */
	protected $css = array();

	/**
	 * an array of The js files includes
	 * 
	 * @var array  Defaults to array(). 
	 */
	protected $js = array();
    
	/**
	 * an array of javascript scripts used
	 * 
	 * @var array  Defaults to array(). 
	 */
	protected $scripts = array();
    
    /**
     * Scripts to be executed after the view rendering
     * (CF problem with Chrome when loading javascripts in header)
     * 
     * @var array  Defaults to array(). 
     */
    protected $afterScripts = array();

	/**
	 * message
	 * 
	 * @var string  Defaults to ''. 
	 */
	protected $message = '';
	
	/**
	 * Indicates if The view should auto render The templates or not
     * Uses The auto_render_template param in the ini file .
	 * 
	 * @var boolean  Defaults to null
	 */
	public  $renderTemplate = null;


	/**
	 * default template
	 *
	 * @var string 
	 */
	private $_tplFile = null;

    /**
     * The configuration array
     *
     * @var array  Defaults to array(). 
     */
    protected $configuration = array();
	/**
	 * Constructor
	 * 
	 * @param  string $controller The controller name
	 */
	public function __construct($viewName, array $configuration = array())
	{
		parent::__construct(array(), \ArrayObject::ARRAY_AS_PROPS);
        $this->viewName = $viewName;
        $this->configuration = $configuration;
        $this->renderTemplate = isset($configuration['autorender_template']) ? (bool) $configuration['autorender_template'] : 1;
	}
    
    /**
     * Configuration Setter
     * 
     * @param  array  $configuration the configuration array
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    } 

    /**
     * Configuration Getter
     * 
     * @return array the configuration array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }    
	/**
	 * Renders The Template
	 * 
	 */
	public function renderTemplate()
	{
		include(BUSINESS . DS . TEMPLATES_PATH . $this->getTemplate());
	}

	/**
	 * echoes The main block in a view
	 */
	public function main()
	{
		$viewName  = ($this->getViewName()) ? $this->getViewName() : $this->getViewName();
        $file = BUSINESS . DS .  VIEWS_PATH . $this->getControllerName() . DS . $viewName  . $this->getExtension();
        echo $this->load($file);
	}

	/**
	 * echoes The view of a given action 
	 * 
	 * @param  string  $action     The action, Optional, defaults to The current action. 
	 * @param  string  $controller The controller, Optional, defaults to The current controller. 
	 */
	public function render($action = null, $controller = null)
	{
		(!$controller) ? $controller = $this->getControllerName() : 'main';
		if (!$action)  $action = $this->getViewName() ;
		$file = BUSINESS . DS .  VIEWS_PATH . $controller . DS . $action  . $this->getExtension();
		echo $this->load($file);
	}

	/**
	 * Loads a business block
     * Block should be located in BUSINESS_DIR/VIEWS_DIR/CONTROLLER_DIR/
	 * 
	 * @param  string  $file The file to include
	 */
	public function loadBlock($file)
	{
		$file = BUSINESS . DS .  VIEWS_PATH . $this->getControllerName() . DS . $file;
		echo $this->load($file);
	}
	
	/**
	 * Loads a file. (executes an include on it) 
     * returns The results as a string (could be tested before echo)
	 * 
	 * @param  string $file  The file to be echoed
	 * @return string The contents of The file
	 */
	public function load($file)
	{
		if (file_exists($file))
		{
			ob_start();
			include($file);
			return ob_get_clean();
		}
	}
    
	/**
	 * Extension Setter 
	 * 
	 * @param  string  $extension The extension to be used
	 */
	public function setExtension($extension)
	{
		$this->_extension = $extension;
	}

	/**
	 * Extension Getter
	 * 
	 * @return string The extension
	 */
	public function getExtension()
	{
		return $this->_extension;
	}

	/**
	 * Template Setter
	 * 
	 * @param  string  $tpl The template file to be used
	 */
	public function setTemplate($tpl)
	{
		$this->_tplFile = $tpl;
	}

	/**
	 * Template Getter
	 * 
	 * @return string The template file used
	 */
	public function getTemplate()
	{
		return $this->_tplFile;
	}

	/**
	 * Controller Name Setter 
	 * 
	 * @param  string  $controller The controller Name including The "Controller" Suffix
	 */
	public function setController($controller)
	{
		$this->_controller = $controller;
	}

	/**
	 * Controller Name Getter
	 * 
	 * @return string The controller name
	 */
	public function getController()
	{
		return $this->_controller;
	}

    /**
     * Returns the base controller name
     * 
     * @return string the base controller name
     */
    public function getControllerName()
    {
        $parts = explode('\\', $this->_controller);
        return $parts[count($parts) - 1];
    }    
	
	/**
	 * View name Setter
	 * 
	 * @param  string $viewName The view name
	 */
	public function setViewName($viewName)
	{
		$this->_viewName = $viewName;
	}

	/**
	 * View name Getter
	 * 
	 * @return string The view name
	 */
	public function getViewName()
	{
		return $this->_viewName;
	}	

	/**
	 * Name Getter
	 * 
	 * @return string The name
	 */
	public function getName()
	{
		return $this->_controller;
	}

	/**
	 * Context link Getter
	 * 
	 * @return string The Context link, including The controller
	 */
	public function getContextLink()
	{
		return "/?controller=" . $this->getControllerName();
	}

	/**
	 * Message Getter
	 * 
	 * @return string The message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Message Setter
	 * 
	 * @param  string  $message The message 
	 * @param  string  $type    The message Type
	 */
	public function setMessage($message,$type)
	{
		$this->message = new \stdClass();
		$this->message->type = $type;
		$this->message->message = str_replace('%20',' ',$message);
	}

	/**
     * Adds a CSS file, if the same file exists, it will NOT be added
	 * 
	 * @param  string $file The CSS File to be added
	 */
	public function addCss($file)
	{
		if (!in_array($file, $this->css))
		{
			$this->css[$file] = $file;
		}
	}

	/**
	 * Removes a CSS File
	 * 
	 * @param  string $file The CSS File to be removed
	 */
	public function removeCss($file)
	{
		if (isset($this->css[$file]))
		{
			unset($this->css[$file]);
		}
	}

	/**
	 * Renders the CSS links
     * Will render the Predefined CSS Files (see renderPredefinedCss)
     * and all those that are in the CoreView::css
	 */
	public function renderCss()
	{
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
	 * Adds a JavaScript file to the js Files 
	 * If the file exists already in the list, it will NOT be added
     *
	 * @param  string  $file The JavaScript file to be added
	 */
	public function addJs($file)
	{
		if (!in_array($file, $this->js))
		{
			$this->js[$file] = $file;
		}
	}

	/**
	 * Removes a JavaScript file from the list
	 * 
	 * @param  string $file The file to be removed
	 */
	public function removeJs($file)
	{
		if (isset($this->js[$file]))
		{
			unset($this->js[$file]);
		}
	}

	/**
	 * Adds a Javascript Script
	 * 
	 * @param  string  $script The script to be added, 
     *                         should not include the script tag
     * @param bool     $after to insert the script after the view rendering, Optional, Defaults to false
	 */
	public function addScript($script, $after = false)
    {
        $wrappedScript =  "<script type=\"text/javascript\">$script</script>";
        if (!$after)
        {
            $this->scripts[] = $wrappedScript;
        }
        else
        {
            $this->afterScripts[] = $wrappedScript;
        } 
    }
    
	/**
	 * Echoes the links to the JavaScript Files.
     * Will also includes the Predefined Javascript files (see renderPredefinedJs)
	 */
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

	/**
     * Echoes the JavaScript scripts
     *
	 * @params bool $after to render the after scripts, Optional, Defaults to False
	 */
	public function renderScripts($after = false)
    {
        $scripts = (!$after) ? $this->scripts : $this->afterScripts;
        if (is_array($scripts) && (0 < count($scripts)))
        {
            foreach ($scripts as $script)
            {
                echo $script;
            }
        }
    }


    /**
     * Renders a list of predefined JavaScript files
     * 
     */
    public function renderPredefinedJs()
    {
        $this->addJs('/scripts/jquery.min.js');
        //$this->addScript('jQuery.noConflict();');
    }

    /**
     * Renders a list of predefined CSS Files
     * 
     */
    public function renderPredefinedCss()
    {
        // Always add style.css
        //$this->addCss('/styles/style.css');
    }

    /**
     * Renders a list of messages
     * 
     */
    public function renderMessages()
    {
        $msg = $this->getMessage();
        if ('' != $msg->message)
        {
            echo '<div class="' . $msg->type .'">' . $msg->message . ' </div>';
        }
    }

    /**
     * Returns the current module (application name)
     * 
     * @return string the module, Defaults to the constant MODULE
     */
    public function getModule()
    {
        return MODULE;
    }    

    /**
     * Always Action
     * 
     */
    public function alwaysAction()
    {
        return true;
    }    
}
