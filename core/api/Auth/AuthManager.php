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
    public function __construct($actions)
    {
        $this->setActions($actions);
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

    public static function check()
    {
        return (SessionManager::get('user')) ? true : false;
    }

    /**
     * Authentication 
     * 
     * @param  frontDispatcher  $front 
     * @param  string   $authUrl the Auth URL
     * @param string $from, if set to 'ajax', it will return a json with error = false instead of 
     *                      routing.
     * @return boolean 
     */
    public function authenticate(frontDispatcher $front, $authUrl, $from)
    {
        $actions = $this->getActions();
        $controller = explode('Controller', $front->getController());
        $controller = $controller[0];
        $action = $front->getAction();
        if (!isset($actions[$controller]) || (isset($actions[$controller]) && isset($actions[$controller][$action]) && '1' === $actions[$controller][$action]))
        {
            // add this for preserved areas
            if (false === AuthManager::check())
            {
                if ('ajax' == $from)
                {
                    header('content-type: application/json');
                    $params = array(
                        'error'=> 'true',
                        'url' => $authUrl,
                    );
                    echo json_encode($params);
                }
                else
                {
                    header("location: $authUrl");
                }
                exit();
            }
        }

    }
}    
