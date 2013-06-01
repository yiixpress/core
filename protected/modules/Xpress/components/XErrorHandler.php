<?php
/**
* @author Hung Nguyen
* @package Xpres
* @subpackage Exception
*/

/**
* XErrorHandler extends CErrorHandler to help handling errors logged with intension.
* An error when not thrown but logged using XErorrHandler->log() is considered managead
* error and will be handle by this class. Examples are errors from model->getErrors or
* errors logged by Api's services.
*
* The rule is to log errors and throw exceptions.
*   - Excetions are things the application
* cannot handle by itself (with all of its developers effort) such as database server
* down, permission denied on file system,...
*   - Errors are unwanted things that the application knows and can handle
*
* The CErrorHandler as defined by Yii is intended to handle "uncaught PHP errors and
* exceptions". XErrorHandler is to handle "caught" errors and the "gotcha" action is
* its log() method.
*
* Note: Do not log an exception as exceptions cant be handled.
*
* @package Xpress
* @subpackage Exception
*/
class XErrorHandler extends CErrorHandler
{
    /**
    * Errors logged
    * @var CMap
    */
    protected $errors = array();

    public $sendNotificationEmail = true;
    public $sendNotificationTo = '';
    public $excludedCodes = array(404,400);


    public function init(){
        parent::init();
        Yii::app()->attachEventHandler('onEndRequest', array($this,'processAbandonedErrors'));

        // deal with fatal error such as E_PARSE, E_CORE_ERROR
        if (!YII_DEBUG)
            register_shutdown_function(array($this,'onErrorShutdown'));
    }

    /**
    * Log the error to process them later
    *
    * @param mixed $errors array or a string or a CException derived object
    */
    public function log($errors)
    {
        if (!is_array($errors)) $errors = array($errors);
        foreach($errors as $err)
        {
            if (is_string($err))
                $ex = new XManagedError($err,0);
            elseif($err instanceof CException){
                if ($err instanceof XManagedError)
                    $ex = $err;
                else
                    $ex = new XManagedError($err->getMessage(), $err->getCode);
            }else
                throw new CException('Try to catch an error that is not extended from CException.',0);

            $this->errors[$ex->getHash()] = $ex;
            /**
            * If the application has XService component, log the service error.
            * The XService will take care of determining if the error belong to service or not
            */
            if (Yii::app()->getComponent('XService', false) instanceof XService)
            {
                Yii::app()->getComponent('XService')->logError($ex);
            }
        }
    }

    /**
    * Errors logged but not process are now sent to logger.
    * This function is supposed to use as the handler event
    * for application's onEndRequest only
    *
    */
    public function processAbandonedErrors()
    {
        if (empty($this->errors)) return;

        $errMsg = "<ul>";
        foreach($this->errors as $err){
            $errMsg.= "<li>".$err->getMessage()."</li>";
        }
        $errMsg .= "</ul>";
        Yii::log("<div id=\"abandoned-errors\"><p>".count($this->errors)." error(s) are not handled properly</p>{$errMsg}</div>",CLogger::LEVEL_WARNING);
    }


    /**
    * Check if there is any error in the application
    */
    public function hasErrors() {
        return count($this->errors) > 0;
    }

    /**
    * Get logged errors
    *
    * @param string $context default null get only errors belong to this context
    * @param object $owner object owns the error at time it is logged. Default is
    * null to get all errors in the context
    *
    * @return Array
    */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
    * Get error messages for the whole request
    *
    * @param mixed $discard
    * @return array
    */
    public function getErrorMessages($discard = true)
    {
        $messages = array();
        foreach($this->errors as $error)
        {
            if ($error instanceof XModelManagedError)
                $messages[] = $error->getModel()->getErrors();
            else
                $messages[] = $error->getMessage();

            if ($discard)
                $this->discardError($error);
        }
        return $messages;
    }

    /**
    * Discard an error. Should call only after the error is handled
    *
    * @param mixed $owner
    * @param string $code
    */
    public function discardError($error)
    {
        return $this->discardErrorByHash($error->getHash());
    }

    public function discardErrorByHash($hash)
    {
        if (!isset($this->errors[$hash])) return '';

        $errMsg = $this->errors[$hash]->getMessage();
        unset($this->errors[$hash]);
        return $errMsg;
    }

    /**
    * A helper to format errors of a field
    *
    * @param CModel $model
    * @param string $field
    * @param CActiveForm $form default null
    */
    public function handleModelErrors($model, $field, $form = null)
    {
        if (! $model instanceof CModel) return;

        if ($total = count($model->getErrors($field)) > 0)
        {
            if ($form instanceof CActiveForm)
                $msg = $form->error($model, $field);
            else
                $msg = CHtml::error($model, $field);
            for($i = 0;$i<$total;$i++)
                $this->discardError($model,"{$field}-{$i}");

            return $msg;
        }
        return null;
    }

    /**
    * Override CErrorHandler handle event to process error stack
    *
    * @param mixed $event
    */
    public function handle($event) {
        // If a XManagedError is thrown it should be hancled friendly as the way exception is handled in NON DEBUG mode
        // Therefore, we will cast the exception into CHttpException so that CErrorHandler will runs errorAction
        if(YII_DEBUG && $event instanceof CExceptionEvent && $event->exception instanceof XManagedError && $this->errorAction != '')
        {
            $exception = $event->exception;
            $event->exception = new CHttpException(500, $exception->getMessage());
        }
        elseif (YII_DEBUG)
            // Yii tries to run the errorAction if it's set event in debug mode
            // while we do not like that. It helps only if the action can provide
            // more details than Yii default error/exception debug report.
            $this->errorAction = NULL;

        parent::handle($event);

        // Send email to admin if debug is turn off
        if (YII_DEBUG == false && $this->sendNotificationEmail == true)
        {
            if (array_search($this->Error['code'], $this->excludedCodes) !== false) return;
            $this->notifyByMail($this->Error);
        }
    }

    public function onErrorShutdown()
    {
        if (($e = error_get_last()) ===  NULL)
            return;


        switch($e['type'])
        {
            case E_ERROR: // 1 // Fatal error
            case E_PARSE: // 4 // Compile error

                //TODO: find a way to send email notification. Problem is the code is running while PHP engine shutting down
//                $emailTo = $this->sendNotificationTo;
//                if (empty($emailTo))
//                    $emailTo = array(SETTING_ADMIN_EMAIL);
//                foreach($emailTo as $to)
//                    mail($to, 'PHP Erorr Occurred', print_r($e,true));
                $url = Yii::app()->urlManager->createUrl($this->errorAction);
                Yii::app()->request->redirect($url, false);
                break;
            default:
                break;
        }
    }

    protected function notifyByMail($error)
    {
        if (file_exists(Yii::app()->runtimePath . '/cache/Settings.php'))
            include_once Yii::app()->runtimePath . '/cache/Settings.php';
        try {
            $mail = Yii::app()->getComponent('mail');
            $mail->viewPath = 'Xpress.views.mails';
            Yii::import('Xpress.extensions.vendors.mail.YiiMailMessage');
            $message = new YiiMailMessage;
            $message->view = 'error_notification';
            $message->setSubject('Error happens on '.SETTINGS_SITE_NAME.' ('.Yii::app()->request->getHostInfo().')');

            /**
            * @var CHttpException
            */
            $message->setBody(array(
                'error' =>$error,
            ));

            if (!empty($this->sendNotificationTo))
                foreach(explode(',',$this->sendNotificationTo) as $to)
                    $message->addTo(trim($to));
            else
                $message->addTo(SETTINGS_ADMIN_EMAIL);

            $message->setFrom(SETTINGS_ADMIN_EMAIL);

            $mail->send($message);
        } catch(Exception $ex){
            throw $ex; // well, there is really nothing we can do here !!!
        }
    }
}


class XManagedError extends CException
{
    /**
    * Hash value to identify the error when it is handdled so that it can be removed
    * from the log
    *
    * @var string
    */
    private $_hash;

    protected $_code;

    /**
    * @param string $message
    * @param string $code
    * @return XManagedError
    */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message);
        $this->_hash = md5($message . $code).'_'.microtime();
    }

    public function getHash()
    {
        return $this->_hash;
    }

    public function getErrorCode()
    {
        return $this->_code;
    }
}

class XModelManagedError extends XManagedError
{
    private $_model;

    public function __construct(&$model, $code)
    {
        $this->_model = $model;
        parent::__construct(CHtml::errorSummary($model,'',''),$code);
    }

    public function getModel()
    {
        return $this->_model;
    }
}