<?php
Yii::setPathOfAlias('site', dirname(__FILE__) . '/../../sites/' . SITE_DIR . '/protected');
Yii::setPathOfAlias('runtime', Yii::getPathOfAlias('site') . '/runtime');

return array(
    'id'=>SITE_ID.'_'.APP_ID,
    'runtimePath' => Yii::getPathOfAlias('runtime'),
    'modules' => include_once(Yii::getPathOfAlias('runtime').'/cache/modules.php'),
    'components' => array(
        'themeManager' => array(
            'basePath' => Yii::getPathOfAlias('site') . '/../themes',
            'baseUrl' => str_replace(DIRECTORY_SEPARATOR, '/', '/sites/' . SITE_DIR . '/themes'),
        ),
        'session'=>array(
            'sessionName'=>SITE_ID.'_'.APP_ID.'_sid',
            'cookieMode' => 'allow',
            'cookieParams' => array(
                'path' => '/',
                'domain' => $GLOBALS['TLD'],
                //'httpOnly' => true,
            ),
        ),
        'user' => array(
            'class' => 'Xpress.extensions.web.auth.XWebUser',
            'allowAutoLogin' => true,
            'userClass' => 'XUser.models.User',
            'loginUrl' => array('/user/sign-in'),
        ),
        'errorHandler'=>array(
            'class'=>'Xpress.components.XErrorHandler',
            // use 'site/error' action to display errors
            'errorAction'=>'Cms/defaultCms/error',
        ),
       /* 'cache'=>array(
            'class'=>'CFileCache',
        )*/
    ),
);