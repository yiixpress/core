<?php

/**
* Shortcut to Yii::app()
* return CWebApplication
*/
function app()
{
    return Yii::app();
}


/**
* @author Hung Nguyen
* @package Xpress
* @subpackage Utilities
*/

/**
* Shortcut to Xpress component
*
* @return XPress
*/
function Xpress()
{
    return Yii::app()->getComponent('Xpress');
}

/**
* Shortcut to errorHandler component
* @return XErrorHandler
*/
function errorHandler()
{
    return Yii::app()->getErrorHandler();
}

/**
 * shortcut to create url
 *
 * @param mixed $route
 * @param mixed $params
 * @param mixed $ampersand
 */
function url($route, $params = array(), $ampersand = '&', $forceGetFormat = false)
{
    if ($forceGetFormat)
        $urlManager = new CUrlManager();
    else
        $urlManager = Yii::app()->urlManager;
    return $urlManager->createUrl($route, $params, $ampersand);
}

/**
 * site base url
 */
function baseUrl()
{
    return Yii::app()->request->getBaseUrl(true);
}

/**
* theme base url
*/
function themeUrl(){
    if (app()->controller instanceof BackOfficeController)
        return app()->theme->baseUrl.'/';

    $url = cdnBaseUrl() . '/sites/' . SITE_DIR . '/themes/' . (is_object(Yii::app()->theme) ? Yii::app()->theme->name : hasParam('SETTINGS_THEME')) . '/';
    return $url;
}

/**
 * URL to site's uploads folder
 */
function uploadUrl()
{
    return str_replace(DIRECTORY_SEPARATOR, '/', '/sites/' . SITE_DIR . '/uploads/');
}

/**
 * Application base path
 */
function basePath()
{
    return Yii::app()->basePath;
}

/**
 * Application runtime path
 */
function runtimePath()
{
    return Yii::app()->runtimePath;
}

/**
 * Application cache path
 */
function cachePath()
{
    return Yii::app()->runtimePath . '/cache';
}

/**
 * Application clientScript object
 * @return CClientScript
 */
function cs()
{
    return Yii::app()->clientScript;
}

/**
 * current logged in user
 * @return XWebUser
 */
function user()
{
    return Yii::app()->user;
}

/**
 * Check if a setting class has a defined constant
 *
 * @param mixed $setting param name
 * @param mixed $default default value if param is not defined
 * @return mixed parameter value or false
 */
function hasParam($setting, $default = null)
{
    // turn off E_WARNING
//    if (YII_DEBUG)
        $oldErrorReporting = error_reporting(E_ALL ^ E_WARNING);
    // old setting syntax using class::constant
    if (strpos($setting, '::') !== false)
    {
        list($class, $param) = explode('::', $setting);
        if (class_exists($class, false)) {
            $class = new ReflectionClass($class);
            return $class->getConstant($param);
        }
    }
    else
    {
    // new setting syntax using define
        if (!constant($setting) && $default === null)
        {
//            if (YII_DEBUG)
                error_reporting($oldErrorReporting);
            defined($setting) OR define($setting,$default);
            return false;
        }
        elseif (!constant($setting) && $default !== null)
        {
//            if (YII_DEBUG)
                error_reporting($oldErrorReporting);
            defined($setting) OR define($setting,$default);
            return $default;
        }
        else{
            eval ("\$value = {$setting};");
            return $value;
        }
    }
    return false;
}

/**
 * Send email to an address
 *
 * @param string $email receiver's address
 * @param string $view path to view used by mailer (Yii's alias format)
 * @param array $data associative array passed to view
 */
function quickMail($email, $view, $data, $subject = 'Flexicore Member Registration Confirmation')
{
    Yii::import('Core.extensions.vendors.mail.YiiMailMessage');
    $viewName = end(explode('.', $view));
    Yii::app()->mail->viewPath = str_replace('.' . $viewName, '', $view);
    //send mail
    $message = new YiiMailMessage;
    $message->view = $viewName;
    $message->setSubject($subject);
    $message->setBody($data, 'text/html');
    $message->addTo($email);
    if (hasParam('Settings::ADMIN_EMAIL'))
        $message->setFrom(array(Settings::ADMIN_EMAIL => Settings::SITE_NAME));

    try {
        Yii::app()->mail->send($message);
    } catch (Exception $ex) {
        FErrorHandler::logError($ex->getMessage());
    }

}

/**
 *
 * Create an service URL which use service controller to call an API given its serviceId
 * @param $serviceId
 * @param string $type possible values ajax / ajax.text / ajax.full / widget / command
 * @param array $params
 * @param string $ampersand
 * @return string
 */
function serviceUrl($serviceId, $type = 'ajax', $params = array(), $ampersand = '&')
{
    $params['SID'] = $serviceId;
    // receive simplified result in case of AJAX. There is no error info in result.
    if ($type == 'ajax.full')
        $type = 'ajax';
    elseif ($type == 'ajax.text') {
        $type = 'ajax';
        $params['FORMAT'] = 'text';
    } elseif ($type == 'ajax') {
        $type = 'ajax';
        $params['SIMPLIFIED'] = 1;
    }

    return url('/Core/service/' . $type, $params, $ampersand);
}

/*
 * Shorthand to format currency
 * @param $value
 * @param $currencyCode
 */
function currency($value,$currencyCode){
    return Yii::app()->numberFormatter->formatCurrency($value,$currencyCode);
}

/**
 * Send mail function
 * @param string $body Mail content
 * @param string $toAddress Address (one or many) for send mail to
 * @param string $from Sender address. Default value is SETTINGS_ADMIN_EMAIL param
 * @param bool $htmlFormat true for HTML, false for plain text
 *
 * @return bool Send mail result
 */
function xmail($subject, $body, $toAddresses, $from = '', $htmlFormat = true) {
    // Set default value for $from if empty
    if (empty($from))
        $from = hasParam('SETTINGS_ADMIN_EMAIL', '');

    $message = new YiiMailMessage;
    $contentType = 'html/text';
    if (!$htmlFormat)
        $contentType = 'text/plain';
    $message->setBody($body, $contentType);
    $message->subject = $subject;
    $message->addTo($toAddresses);
    $message->from = $from;
    return Yii::app()->mail->send($message);
}

?>
