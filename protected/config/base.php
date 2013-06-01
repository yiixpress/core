<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$config = array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'YiiXpress Demo',

    // preloading components, do not change order of errorHandler and log
    'preload'=>array('log','errorHandler','Xpress','XService','mail'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.Xpress.extensions.web.widgets.*',
    ),

    'modules'=>array(
        'Xpress',
        'XUser' => array(
            'class' => 'Xpress.modules.XUser.XUserModule',
        ),
    ),
    // application components
    'components'=>array(
        'Xpress' => require_once(dirname(__FILE__).'/../modules/Xpress/config/main.php'),
        'XService'=>array(
            'class'=>'Xpress.components.XService'
        ),
        'authManager'=>array(
            'class'=>'Xpress.extensions.web.auth.XAuthManager',
            'assignmentTable'=>SITE_ID.'_authassignment',
            'itemTable'=>SITE_ID.'_authitem',
            'itemChildTable'=>SITE_ID.'_authitemchild',
        ),
        'clientScript'=>array(
            'class'=>'Xpress.components.XClientScript',
        ),
        'user'=>array(
            'class'=>'CWebUser',
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        'session'=>array(
            'sessionName'=>SITE_ID.'_'.APP_ID.'_sid',
        ),
        // uncomment the following to enable URLs in path-format
        /*
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        */
        'errorHandler'=>array(
            'class'=>'Xpress.components.XErrorHandler',
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
//                    'levels'=>'error, warning',
					'levels'=>'error, warning, trace, info',
                    'categories'=>'trace',
//					'categories'=>'system.db.*',
                ),
//                array(
//                    'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
//                    'ipFilters'=>array('127.0.0.1'),
//                ),
            ),
        ),

        //'cache'=>array(
//            'class'=>'CFileCache',
//        ),
        /*'cacheSchema'=>array(
            'class'=>'CFileCache',
        ),*/

        'mail' => array(
            'class' => 'Xpress.extensions.vendors.mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => true
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
);
// todo : change this to dynamically config the db components and the debuger

if (YII_DEBUG)
{
// add debug toolbar
//    $config['components']['log']['routes'][] = array(
//        'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
//        'ipFilters'=>array('127.0.0.1'),
//    );
}

// database
if (isset($dbs) && is_array($dbs))
    foreach($dbs as $key => $dbConfig)
    {
        $config['components'][$key]=array(    
            'class' => 'CDbConnection',
            'connectionString' => $dbConfig['connectionString'],
            'username' => $dbConfig['username'],
            'password' => $dbConfig['password'],
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
             // cache
            'schemaCacheID'=>'cacheSchema',
            'schemaCachingDuration'=> 6000, //60*30, // 30 minutes
    //        'queryCacheID'=>'cacheSchema',
    //        'queryCachingDuration'=>60,
    //        'queryCachingCount'=> 0,
        );
    }

return $config;