<?php
require_once 'DiagnosticBaseController.php';
class BaseConfigController extends DiagnosticBaseController
{
    public $title = 'Basic configuration';
    
    public function actionIndex()
    {
        $file = Yii::app()->basePath.'/config/base.php';
        if (!file_exists($file))
        {
            $message = strtr("Basic configuration file {file} is {found}", array(
                '{file}' => $file,
                '{found}' => 'NOT FOUND'
            ));
            $message = strtr($message,$this->defaultKeys());
            $this->render('index',array('message'=>$message,'checkAgain'=>str_replace('Controller','',get_class($this))));
            return;
        } else if (!is_readable($file)) {
            $message = strtr("Basic configuration file {file} is {readable}", array(
                '{file}' => $file,
                '{readable}' => 'NOT READABLE'
            ));
            $message = strtr($message,$this->defaultKeys());
            $this->render('index',array('message'=>$message,'checkAgain'=>str_replace('Controller','',get_class($this))));;
        } else {
            $this->successful = true;
            $baseConfig = include($file);
            $message = strtr("Basic configuration file {file} is {found} and {readable}", array(
                '{file}' => $file,
                '{found}' => 'FOUND',
                '{readable}' => 'READABLE'
            ));
            $message = strtr($message,$this->defaultKeys());
            $this->render('index',array('message'=>$message,'config' => $baseConfig));
        }        
    }
    /*public function actionReloadContent() {
        $file = Yii::app()->basePath.'/config/base.php';
        $this->renderPartial('index',array('config' => $baseConfig));
        Yii::app()->end();
    }*/
    public function actionCheckAgain()
    {
        $file = Yii::app()->basePath.'/config/base.php';
        if (!file_exists($file))
        {
            $message = strtr("Basic configuration file {file} is {found}", array(
                '{file}' => $file,
                '{found}' => 'NOT FOUND'
            ));
            $message = strtr($message,$this->defaultKeys());
            echo CJSON::encode(array(
                'msg' => $message,
            ));
            Yii::app()->end();
        } else if (!is_readable($file)) {
            $message = strtr("Basic configuration file {file} is {readable}", array(
                '{file}' => $file,
                '{readable}' => 'NOT READABLE'
            ));
            $message = strtr($message,$this->defaultKeys());
            echo CJSON::encode(array(
                'msg' => $message,
            ));
            Yii::app()->end();
        } else {
            $this->successful = true;
            $baseConfig = include($file);
            $message = strtr("Basic configuration file {file} is {found} and {readable}", array(
                '{file}' => $file,
                '{found}' => 'FOUND',
                '{readable}' => 'READABLE'
            ));
            $message = strtr($message,$this->defaultKeys());
            echo CJSON::encode(array(
                'msg' => $message, 'config' => 'Has data'
            ));
            Yii::app()->end();
        }
    }
    
}
?>
