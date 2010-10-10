<?php 
/**
 * Authentication Manager
 * 
 */
namespace Core\Auth;
class AuthManager
{
    /**
     * Specifies the actions to be accessed or not
     * 
     * @var array  Defaults to array(). 
     */
    private $actions = array();

    /**
     * Constructor
     * 
     * @param  array $action a list of actions to allow / deny    
     */
    public function __construct(array $actions)
    {
        $this->setActions($actions);
    }

    /**
     * Actions Setter
     * 
     * @param  array  $actions 
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * Actions Getter
     * 
     * @return array the actions array
     */
    public function getActions()
    {
        return $this->actions;
    }    
    /**
     * The Session
     * 
     * @var SessionManager the Session
     */
    private $session = null;

    /**
     * The session Setter
     * 
     * @param  SessionManager  $session 
     */
    public function setSession(\Core\Session\SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Session Getter
     * 
     * @return Session the session object
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Session check
     *
     * @return bool true if user is connected, false otherwise
     */
    public function check()
    {
        return ($this->session->get('user')) ? true : false;
    }

    /**
     * Authentication method
     * 
     * @param  frontDispatcher  $front 
     * @param  string   $authUrl the Auth URL
     * @param string $from, if set to 'ajax', it will return a json with error = false instead of 
     *                      routing.
     * @return boolean 
     */
    public function authenticate(\Core\MVC\FrontDispatcher $front, $authUrl, $from)
    {
        $actions = $this->getActions();
        $controller = explode('Controller', $front->getController());
        $controller = $controller[0];
        $action = $front->getAction();
        if (!isset($actions[$controller]) || (isset($actions[$controller]) && isset($actions[$controller][$action]) && '1' === $actions[$controller][$action]))
        {
            // add this for preserved areas
            if (false === $this->check())
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

