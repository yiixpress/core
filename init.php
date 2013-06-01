<?php
configureDebug(array(
    '127.0.0.1',
    //add more ip you want to run in debug mode below
));

loadFramework($yiiPath);
configureEnvironment();
////////////////////////////// Supporting functions /////////////////////////////////

/**
 * Configure the application to run in debug mode or not.
 * If the site run on localhost it will automatically run on debug mode
 *
 * @param array devIPs an array of IPs that will run the app in debug mode
 * @return void
 */
function configureDebug($devIPs = array('127.0.0.1'))
{
    if (in_array($_SERVER['REMOTE_ADDR'], $devIPs))
        define("YII_DEBUG", true);

    defined('YII_DEBUG') or define('YII_DEBUG', FALSE);

    if (YII_DEBUG) {
        set_time_limit(0);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        define('YII_TRACE_LEVEL', 10);
    }
}

/**
 * Load Yii framework
 * Framework path is configured in index.php
 */
function loadFramework($yiiPath)
{
    if (is_file($yiiPath))
        require_once($yiiPath);
    else {
        echo '<pre>', '
There is an error trying to load the framework at 

' . dirname(__FILE__) . '/' . $yiiPath . '

Edit the index.php and admin/index.php to correct path to Yii framework.
        ', '</pre>';
        die;

    }
}

/**
 * Load the environment file and Yii config file for the application
 * The Yii config file must be named as main-APP_ID where APP_ID is defined in the index.php
 *
 * @return void
 */
function configureEnvironment()
{
    if (!defined('APP_ID'))
        die('APP_ID is not defined in'.$_SERVER['SCRIPT_FILENAME']);
    
    $GLOBALS['APP_ID'] = APP_ID;
    $appConfig = null;

    $request = new CHttpRequest();
    $hostInfo = parse_url($request->getHostInfo(), PHP_URL_HOST);
    // environment file locates in /sites/env folder using this naming convention: config.domain.php
    $envFile = dirname(__FILE__) . '/sites/env/config.' . $hostInfo . '.php';
    if (file_exists($envFile))
        include ($envFile);
    else
        diagnostic();

    // support single login to manage different sub domain    
    $GLOBALS['TLD'] = '.' . implode('.', array_slice(explode('.', $hostInfo), -2));
    // load application config
    $appConfig = dirname(__FILE__) . '/protected/config/main-' . $GLOBALS['APP_ID'] . '.php';
    if (file_exists($appConfig))
        $appConfig = include($appConfig);
    else
        diagnostic();

    // merge with base config
    $base = require(dirname(__FILE__) . '/protected/config/base.php');
    $GLOBALS['config'] = CMap::mergeArray($base, $appConfig);
}

function diagnostic()
{        
    if(YII_DEBUG)
    {
        header('location: /diagnostics.php'); 
        die;
    } 
    else 
    {
        die('Cannot load environment file'); 
        // for security, in production mode use the line below to hide detailed information
        //die();
    }
}

?>
