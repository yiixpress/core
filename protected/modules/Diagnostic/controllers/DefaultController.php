<?php
/**
* @author Hung Nguyen
* @package Xpress
* @subpackage Diagnostic
*/

/**
* Perform basic diagnostics for the application. 
* This diagnostics are done on the assumption that the system has been able to run Yii application
* 
* @package Core
* @subpackage Diagnostic
*/
class DefaultController extends CController
{
    private $_tests = array(
        'Environment',
        'WritableFolders',
        'Databases',
        'BaseConfig',
        'Module',
        'Email',
    );
    
    /**
    * Initialize the theme/layout used by actions
    * 
    * If action is ajax, disable both theme and layout
    */
    public function init()
    {
        Yii::app()->theme = 'global';
        $this->layout = 'main';        
    }
    
    /**
    * Diagnostic homepage
    * 
    */
	public function actionIndex()
	{
        if(isset($_POST['toolstarted'])) {
            Yii::app()->session['toolIsStarted'] = $_POST['toolstarted'];
        }
        
        if(isset(Yii::app()->session['toolIsStarted']) && Yii::app()->session['toolIsStarted'] == 1) {
            $this->render('index',array('controllers' => $this->_tests));
        } else {
            $this->render('index');
        }
	}
    
    /**
    * Dispaly PHP information
    */
    public function actionPhpInfo()
    {
        phpinfo();
    }
    
    
}