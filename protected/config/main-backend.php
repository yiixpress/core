<?php
Yii::setPathOfAlias('site',dirname(__FILE__).'/../../sites/'.SITE_DIR.'/protected');
Yii::setPathOfAlias('runtime', Yii::getPathOfAlias('site').'/runtime');

$modules = include_once(Yii::getPathOfAlias('runtime').'/cache/modules.php');
// Add gii and Admin module
$modules['gii'] = array(
    'class'=>'system.gii.GiiModule',
    'password'=>'yiixpress',
    'generatorPaths'=>array('Xpress.extensions.gii.generators'),
    // If removed, Gii defaults to localhost only. Edit carefully to taste.
    // 'ipFilters'=>array('127.0.0.1','::1'),
    'params'=>array(
        'model'=>array(
            'created_time_field_name'=>'creation_datetime',
            'last_update_time_field_name'=>'last_update',
        ),
    )
);

$modules['Admin']= array(
    'class' => 'Xpress.modules.Admin.AdminModule',
);

return array(
    'runtimePath'=>Yii::getPathOfAlias('runtime'),
    'defaultController'=>'Admin/default',
	// preloading components, do not change order of errorHandler and log
	'preload'=>array('log','errorHandler','Xpress','XService','mail','bootstrap'),


    'modules' => $modules,
    
    'components'=>array(
//        'assetManager' => array(
//            'basePath'=>Yii::getPathOfAlias('site').'/../assets',
//            'baseUrl'=>str_replace(DIRECTORY_SEPARATOR,'/','/sites/'.SITE_DIR.'/assets'),
//        ),
        'themeManager'=>array(
            'baseUrl'=>$request->getHostInfo().'/admin/themes/',
            'basePath'=>dirname(__FILE__).'/../../admin/themes',
        ),
        'errorHandler'=>array(
            'class'=>'Xpress.components.XErrorHandler',
            // use 'site/error' action to display errors
            'errorAction'=>'Admin/default/error',
        ),
        'user'=>array(
            'class'=>'UserExt.extensions.web.auth.ExtWebUser',
            'allowAutoLogin'=>true,
            'userClass'=>'XUser.models.AdminUser',
            'loginUrl'=>array('/Admin/auth/login'),
        ),
        'errorHandler'=>array(
            'class'=>'Xpress.components.XErrorHandler',
            // use 'site/error' action to display errors
            'errorAction'=>'Admin/default/error',
        ),
        'bootstrap'=>array(
            'class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => false,
        ),
    ),
);
