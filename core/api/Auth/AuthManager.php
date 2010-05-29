<?php 
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class AuthManager
{
    /**
     * Specifies the actions to be accessed or not
     * 
     * @var array  Defaults to array(). 
     */
    private $actions = array();

    /**
     * TODO: short description.
     * 
     * @param  mixed    
     */
    public function __construct($accessFile)
    {
        $accessParser = new INIConfigurationParser();
        $accessParser->loadFile($accessFile);
        $accessParser->parse();
        $config = $accessParser->getConfigs();
        $this->setActions($config);
    }

    /**
     * Actions Setter
     * 
     * @param  array  $actions 
     * @return TODO
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getActions()
    {
        return $this->actions;
    }    
    /**
     * TODO: description.
     * 
     * @var string
     */
    private $session = null;

    /**
     * TODO: short description.
     * 
     * @param  string  $session 
     * @return TODO
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public static function check()
    {
        // implement access here
        if (false === SessionManager::get('user'))
        {
            return false;
        }
        return true;
    }

    /**
     * TODO: short description.
     * 
     * @param  frontDispatcher  $front 
     * @return TODO
     */
    public function authenticate(frontDispatcher $front, $sessionName = 'simplemvc_')
    {
        $actions = $this->getActions();
        $controller = explode('Controller', $front->getController());
        $controller = $controller[0];
        $action = $front->getAction();
            
        SessionManager::init($sessionName);
        if (isset($actions[$controller]) && isset($actions[$controller][$action]) && '1' === $actions[$controller][$action])
        {    
            // add this for preserved areas
            if (false === AuthManager::check())
            {
                header("location: /?controller=User&action=login");
                exit();
            }
        }
    }
}    
