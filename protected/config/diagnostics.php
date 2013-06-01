<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Indition Diagnostic Tool',
    'defaultController'=>'Diagnostic/default',

	// preloading components, do not change order of errorHandler and log
	'preload'=>array('log','errorHandler','Xpress'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.Xpress.extensions.web.widgets.*',
	),

	'modules'=>array(
        'Xpress',
        'Diagnostic'
	),
	// application components
	'components'=>array(
        'Xpress'=>require_once(dirname(__FILE__).'/../modules/Xpress/config/main.php'),
        'XService'=>array(
            'class'=>'Xpress.components.XService'
        ),
		'user'=>array(
            'class'=>'CWebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
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
					'levels'=>'error, warning',
//					'levels'=>'error, warning, trace',
//					'categories'=>'system.db.*',
				),
			),
		),
    ),
);


$request = new CHttpRequest();
$hostInfo = parse_url($request->getHostInfo(), PHP_URL_HOST);
// top level domain element
$tldElements = array_slice(explode('.', $hostInfo), -2);
// support single login to manage different sub domain    
$domain = '.'.implode('.', $tldElements);
$GLOBALS['TLD'] = $domain;
// environment file locates in /sites/env folder using this naming convention: config.domain.php
$envFile = 'config.'.$hostInfo.'.php';
$envFile = dirname(__FILE__).'/../../sites/env/'.$envFile;

if (file_exists($envFile))
{
    include ($envFile);
    
    //$config['preload'][] = 'XService';

    $config['components']['authManager']=array(
        'class'=>'Xpress.extensions.web.auth.XAuthManager',
        'assignmentTable'=>SITE_ID.'_authassignment',
        'itemTable'=>SITE_ID.'_authitem',
        'itemChildTable'=>SITE_ID.'_authitemchild',
    );

    // database
    if (count($dbs))
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
}

return $config;