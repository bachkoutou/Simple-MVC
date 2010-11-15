<?php
header('Content-Type=text/html; charset=UTF-8');
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

//set_error_handler("exception_error_handler");
require_once(dirname(__FILE__) . '/../conf/configuration_front.php');
require_once(dirname(__FILE__) . '/../core/autoloadManager.php');
autoloadManager::setSaveFile('/tmp/front.php');
autoloadManager::addFolder(CORE);
autoloadManager::addFolder(BUSINESS);
spl_autoload_register('autoloadManager::loadClass');
$_REQUEST['controller'] = Toolbox::getArrayParameter($_REQUEST, 'controller', 'Feed');
$_REQUEST['action'] = Toolbox::getArrayParameter($_REQUEST, 'action', 'index');

// jquery based ajax application
$from =  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ('XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'])) ? 'ajax' : 'http';

try
{
    $front = frontDispatcher::getInstance();
    // Init Session
    // Save header for ajax call, so that we can either root or return false for ajax calls
    $actions = AccessHelper::getActions();
    
    // authenticate
    $AuthManager = new AuthManager($actions);
    $AuthManager->authenticate($front, '/?controller=Feed&action=index', $from);
    // Inject Dynamically changing objects
    $Container = ContainerFactory::get('front');
    $Container['Access'] = $actions;
    $Container['AuthManager'] = $AuthManager;
    $Container['Request'] = $_REQUEST;
    $Container['Session'] = SessionManager::getSession('front');
    
    // Route
    $front->route($Container);
}
catch (Exception $e)
{
    if ('ajax' == $from)
    {
        header('content-type: application/json');
        $params = array(
            'error'=> 'false',
            'message' => $e->getMessage());
        echo json_encode($params);
        exit();
    }
    else
    {
        $console = new DebugConsole($e, true);
        $console->render();
    }    
}

