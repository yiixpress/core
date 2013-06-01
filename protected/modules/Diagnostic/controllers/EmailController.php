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
require_once 'DiagnosticBaseController.php';
class EmailController extends DiagnosticBaseController
{
    public $title = 'Email';
    
    public function actionIndex()
    {
        $baseConfig = include(Yii::app()->basePath.'/config/base.php');
        if (!isset($baseConfig['components']['mail']))
        {
            $this->successful = 0;
            $message = 'Mail component is not found.';
            $this->render('index',array('message' => $message));
            return;
        }
        
        $mail = Yii::createComponent($baseConfig['components']['mail']);
        
        if ($mail && $mail->dryRun == false)
        {
            $this->render('testMail',array(''));
        }
        elseif($mail && $mail->dryRun == true)
        {
            $this->successful = -1;
            $message = 'Mail component is configured with dry-run mode. Email will not be sent.<br />Please edit base.php file.';
            $this->render('index',array('message' => $message));
        }
    }
    
    public function actionTestEmail() {
        if(isset($_POST['email'])) {
            try {
                $message = new YiiMailMessage;
                $message->setBody('Message content here with HTML', 'text/html');
                $message->subject = 'Test send an email';
                $message->addTo($_POST['email']);
                $message->from = Yii::app()->params['adminEmail'];
                Yii::app()->mail->transportType = SETTINGS_MAIL_METHOD;
                Yii::app()->mail->transportOptions['host'] = SETTINGS_SMTP_HOST;
                Yii::app()->mail->transportOptions['password'] = SETTINGS_SMTP_PASSWORD;
                Yii::app()->mail->transportOptions['username'] = SETTINGS_SMTP_USERNAME;
                Yii::app()->mail->transportOptions['port'] = SETTINGS_SMTP_PORT;
                Yii::app()->mail->transportOptions['encryption'] = SETTINGS_SMTP_SECURE;
                Yii::app()->mail->send($message);
                
                echo CJSON::encode(array(
                    'msg' => 'A test email is sent.',
                    'status'=>''
                ));
            } catch(Exception $e) {
                echo CJSON::encode(array(
                    'msg' => $e->getMessage(),
                    'status'=>'Failed'
                ));
            }
            
            Yii::app()->end();
        }
        
    }
}
?>
