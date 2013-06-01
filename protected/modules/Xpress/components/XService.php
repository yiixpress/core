<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

/**
* XService class is an AppicationComponent that load and run services.
* It works similar to CWebApplication when create the service (controller/action)
* but will keep the controller object in the pool for next call.
*
* @package Xpress
*/
class XService extends CApplicationComponent
{
    /**
    * The current Xpress context before service run
    *
    * @var string
    */
    private $_contextBeforeServiceRun;

    protected $controllers = array();
    protected $hooks = array();

    protected static $apiTrack = array();

    protected static $apiErrors = array();

    /**
    * Profiler & security options
    */
    private static $_totalExcutionTime = 0;
    // whether or not to log service that not use Input model for validation
    public $warnIfNoInputValidation = false;
    // show debug excution time on page where service is called
    public $showExcutionTimeOnPage = false;

    public function init()
    {
        $start = microtime(true);
        $diagnosting = false;
        if (basename($_SERVER['SCRIPT_FILENAME']) === 'diagnostics.php')
            $diagnosting = true;

        if (!file_exists(Yii::getPathOfAlias('runtime').'/cache/modules.php'))
        {
            $this->run('Xpress.Settings.generateModuleConfig');
            Yii::app()->request->redirect(Yii::app()->baseUrl);
            return;
        }

        $file = Yii::app()->runtimePath . '/cache/Settings.php';
        if (!file_exists($file))
            $this->run('Xpress.Settings.db2php');
        if (file_exists($file))
            include_once $file;
        elseif (!$diagnosting)
            Yii::app()->request->redirect(Yii::app()->baseUrl.'diagnostics.php');

        $moduleIds = array_keys((Yii::app()->Modules));
        foreach($moduleIds as $id)
        {
            if ($id == 'gii') continue;

//            if ($id!=='Admin' && $id!=='Xpress')
//            {
//                // load module setting
//                $file = Yii::getPathOfAlias('runtime').'/cache/'.$id.'Settings.php';
//                if (!file_exists($file))
//                {
//                    Yii::log('Generate settings for '.$file);
//                    $this->run('Xpress.Settings.db2php',array('module' => $id));
//                }
//                if (file_exists($file)) // still need to check this because some module doesnot has settings
//                    include_once $file;
//            }

            // load modules and module's hooks
            $module = Yii::app()->getModule($id);
            try{
                $hooks = $module->hookMap();
                foreach($hooks as $apiController => $serviceHooks)
                {
                    if (!isset($this->hooks[$apiController]))
                        $this->hooks[$apiController] =  array();
                    foreach($serviceHooks as $serviceId => $hookServices)
                    {
                        $filter = array("Xpress.components.XFilter + {$serviceId}",
                                        'hooks' => $hookServices,
                                        'apiId'=>$apiController.'.'.$serviceId
                                        );
                        $this->hooks[$apiController][] = $filter;
                    }
                }
            }catch(Exception $e){
                // nothing to do, the module just has no hook
            }
        }

        // profiler
        if ($this->showExcutionTimeOnPage) echo 'XService Init: ' . (microtime(true)-$start) . '<br />';
        self::$_totalExcutionTime +=(microtime(true)-$start);
    }

    public function run($apiId, $params = array())
    {
        $start = microtime(true);
        // for backward compatible, while are moving the v1 code to v2
        if (strpos($apiId,'.') !== false)
            $apiId = str_replace('.','/',$apiId);

        $segments = explode('/',$apiId);
        if (count($segments) != 3)
            throw new XServiceException('Invalid service name',XServiceException::ERR_SERVICE_NAME);
        else
            list($moduleID, $controllerID, $actionID) = $segments;

        // 1. get controller from pool
        //$controllerID .= 'Api';
        // 1.1 check with Extended module
        $key = "{$moduleID}Ext_{$controllerID}Ext";
        if (isset($this->controllers[$key]) && $this->controllers[$key]->isServiceDefined($actionID))
        {
            $controller = $this->controllers[$key];
            $key = null; // set key to null so we do not check standard module any more
        }
        else
            $key = "{$moduleID}_{$controllerID}";
        // 1.2 check with standard module
        if (!is_null($key) && isset($this->controllers[$key]))
        {
            $controller = $this->controllers[$key];
            if (YII_DEBUG)
            {
                if (!$controller->isServiceDefined($actionID))
                    throw new XServiceException("{$actionID} service is not defined in the ".get_class($controller)." owner module.", XServiceException::ERR_UNDEFINED_SERVICE);
            }
        }
        // 2. controller is not in the pool, need to create it
        else
        {
            if (Yii::app()->hasModule($moduleID.'Ext'))
                $ca = $this->createCA($moduleID.'Ext',$controllerID.'ExtApi',$actionID);
            if (!isset($ca) || $ca == null)
                $ca = $this->createCA($moduleID,$controllerID.'Api',$actionID);
            else
                $key = "{$moduleID}Ext_{$controllerID}Ext";

            if($ca!==null)
            {
                list($controller,$actionID)=$ca;
                $controller->init();
                $controller->setHooks($this->getRegisterHooks("{$moduleID}.{$controllerID}Api"));
                $this->controllers[$key] = $controller;
            }
            else
                throw new XServiceException(Yii::t('Xpress.Service','Unable to resolve the service ID "{route}".
                Resolve: File {dir}/{file} must exist and make sure API "{api}" is defined in {module} together with "{service}" service',
                    array(
                        '{route}'=>$apiId,
                        '{file}' => "{$controllerID}Api.php",
                        '{dir}' => "{$moduleID}/services",
                        '{api}' => $controllerID,
                        '{module}' => "{$moduleID}Module.php",
                        '{service}' => $actionID,
                    )),
                XServiceException::ERR_SERVICE_NAME);
        }

        // 3. unset the error log on this service from previous call
        unset(self::$apiErrors[str_replace('/','.',$apiId)]);
        // 4. run action
        array_push(self::$apiTrack,$apiId);
        if ($controller->setApiParams($actionID, $params))
        {
            if (Yii::app() instanceof CWebApplication)
                $controller->run($actionID);
            else // for console application
                $controller->runActionWithFilters($controller->createAction($actionID), $controller->filters());
            $result = $controller->getApiResult();
        }
        else
        {
            $result = null;
        }
        array_pop(self::$apiTrack);

        self::$_totalExcutionTime += (microtime(true)-$start);
        if ($this->showExcutionTimeOnPage) echo "{$apiId} excution: " . (microtime(true)-$start) . ' / ' . self::$_totalExcutionTime. '<br />';
        return $result;
    }

    /**
    * Create controller/action
    * 
    * The difference between this function and application->createController is this function 
    * returns only good result if controller has action defined. application->createController
    * always returns controller object if founds regardless the action is defined or not.
    * 
    * @param mixed $moduleID
    * @param mixed $controllerID
    * @param mixed $actionID
    */
    protected function createCA($moduleID, $controllerID, $actionID)
    {
//        $start = microtime(true);
        /**
        * @var CWebApplication
        */
        $app = Yii::app();
        Yii::import('Xpress.controllers.ApiController');

        if ($app instanceof CWebApplication)
        {
            $ca=$app->createController("{$moduleID}/{$controllerID}/{$actionID}");
        }
        else
        {
            // deal with ConsoleApplication
            $module = $app->getModule($moduleID);
            $ca = array(Yii::createComponent($module->controllerMap[$controllerID], $controllerID, $module),$actionID);
        }
        if (count($ca) == 2)
        {
            $controller = $ca[0];
            if (!$controller instanceof ApiController)
                return null;
                //throw new XServiceException('API class is not an extended class of ApiController.', XServiceException::ERR_BAD_API_CLASS);
            elseif (!$controller->isServiceDefined($actionID))
                return null;
                //throw new XServiceException("{$actionID} service is not defined in the ".get_class($controller)." owner module.", XServiceException::ERR_UNDEFINED_SERVICE);
        }
//        self::$_totalExcutionTime += (microtime(true)-$start);
//        if ($this->showExcutionTimeOnPage) echo "Creating controller {$moduleID}/{$controllerID}/{$actionID}: " . (microtime(true)-$start) . ' / ' . self::$_totalExcutionTime. '<br />';
        return $ca;
    }

    /**
    * Log error for the current service
    *
    * @param XManagedError $error
    */
    public static function logError($error)
    {
        if (empty(self::$apiTrack)) return;

        $currentApi = str_replace('/','.', end(self::$apiTrack));

        if (!isset(self::$apiErrors[$currentApi]))
            self::$apiErrors[$currentApi] = array();

        if ($error instanceof XManagedError)
        {
            self::$apiErrors[$currentApi][] = $error->getHash();
        }
    }

    /**
    * Get logged errors of an executed api
    *
    * @param string $api full qualified api ID
    * @return array of XManagedError
    */
    public static function getErrors($api)
    {
        if (isset(self::$apiErrors[$api]))
        {
            $apiErrors = array();
            $errors = errorHandler()->getErrors();
            foreach(self::$apiErrors[$api] as $errHash)
                $apiErrors[] = $errors[$errHash];

            return $apiErrors;
        }else
            return null;
    }
    /**
    * Get single logged error of an executed api
    *
    * @param string $api full qualified api ID
    * @return XManagedError
    */
    public static function getError($api)
    {
        if (isset(self::$apiErrors[$api]))
        {
            $apiErrors = array();
            $errors = errorHandler()->getErrors();
            return $errors[self::$apiErrors[$api][0]];
        }else
            return null;
    }

    /**
    * Get error messages of all errors logged by a service
    *
    * @param string $api
    * @returns array
    */
    public static function getErrorMessages($api)
    {
        $errors = self::getErrors($api);
        if (is_array($errors))
        {
            $msg = array();
            foreach($errors as $err)
                $msg[]=$err->getMessage();
            return $msg;
        }
        return null;
    }

    /**
    * Get error messages of first error logged by a service
    *
    * @param string $api
    * @returns string error message
    */
    public static function getErrorMessage($api)
    {
        $errors = self::getErrors($api);
        if (is_array($errors))
        {
            return $errors[0]->getMessage();
        }
        return null;
    }

    /**
    * Check if an executed api has error
    *
    * If the api is excuted in a loop, all errors from all exections will be returned
    *
    * @param string $api full qualified api ID
    */
    public static function hasErrors($api)
    {
        if (isset(self::$apiErrors[$api]) && count(self::$apiErrors[$api]))
            return true;
        else
            return false;
    }

    /**
    * Return hooks register by modules for a specific API
    *
    * @param string $api ModuleId.ApiControllerId
    */
    protected function getRegisterHooks($api)
    {
        return isset($this->hooks[$api]) ? $this->hooks[$api] : array();
    }

    public static function loadApiMap($folder)
    {
        $map = array();
        $mapPath = $folder.'/services/maps';
        if (!file_exists($mapPath) || !is_readable($mapPath))
            return $map;

        $files=scandir($mapPath);
        foreach($files as $file)
        {
            if(is_file($mapPath.'/'.$file) && strpos($file,'ServiceMap.php') !== false)
            {
                $map = CMap::mergeArray($map, include($mapPath.'/'.$file));
            }
        }
        return $map;
    }
} // class XService

/**
* XServiceException represent execption in the service.
*
* @package Xpress
*/
class XServiceException extends CException
{
    const ERR_SERVICE_NAME = 1;
    const ERR_BAD_API_CLASS = 2;
    const ERR_INVALID_PARAMS = 3;
    const ERR_UNDEFINED_SERVICE = 4;

}