<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

/**
* ApiController is the base class for all API class. An API class can have multiple services using
* the same naming convention of action as in Yii's Controller/Action. However, in order to be called
* the service must be defined in the controllerMap array of its module class. Each service should 
* have 3 basic information - name, input model class, output model class.
* 
* It's suggested that you use an input model (CModel) to validate service inputs and do basic input
* calculation so that the service is not tampered with basic calculation code. Also, input validation
* with a CModel object is extremly easy and simple using rules() function, compare to writing that
* validation code yourself.
* 
* In the same way you are suggested to use an output model to return service result.
* 
* Input and Output model should be extended from XServiceModel if they serve solely as the
* input/ouput of service. The only benefit from XServiceModel is you do not have to write function
* attributeNames(). You can still use other models for input and output.
* 
* You can also pass into the service different input/output model than the defined ones. The only 
* requirement is your custom model should contain all attributes of the defined model. This is useful
* in many situations such as saving memory, customizing the inputs or output result. The customized
* input/output can be an model object or a Yii class path to create a new model object.
* 
* While declaring the service parameters, you must not use 3 reserved names 'class', 'input','ouput'
* 
* @package Xpress
*/
class ApiController extends CController {
    
    private $_action;
    
    /**
    * Contain parameters of actions
    * 
    * @var array
    */
    protected $params = array();
    
    /**
    * Service output object
    * 
    * @var mixed
    */
    protected $result;
    
    /**
    * Hold the definition for each service method. A definition for a method include:
    * 
    *  - title: friendly service name
    *  - input : path to model class used as the service input. If set, the service can easily verify input params using the model
    *  - output : path to model class used as the service result.
    *  - help : markdown help text for the method
    * 
    * @var array
    */
    protected $methods;
    
    protected $filters = array();
    
    public function init()
    {
        parent::init();
        $this->module->loadSettings();
    }
    
    public function setHooks($hooks)
    {
        $this->filters = $hooks;
    }   
    
    public function filters()
    {
        return $this->filters;
    }
     
    /**
    * Set service definitions for the api
    * 
    * This function is called by XServie while create the api controller object
    * for the first time. The mechanism is defined in CWebModule::controller Map
    * property. 'methods' is a property which is matched with 'methods' in the
    * WebModule class.
    * 
    * @param mixed $definition
    */
    public function setMethods($definition)
    {
        $this->methods = $definition;
    }

    /**
    * Check if a serviceId is defined in the api's owner module
    *     
    * @param mixed $serviceId
    * @return bool
    */
    public function isServiceDefined($serviceId)
    {
        return isset($this->methods[$serviceId]);
    }
    
    /**
    * Set parameters of the being run service before running it
    * 
    * If the service's definition has info of the input model,
    * params will be valdiated (using that input model) first.
    * The params are set only when validation is successful.
    * 
    * @param mixed $actionId
    * @param mixed $params
    */
    public function setApiParams($actionId, $params) {
//        if (isset($params['class']))
//            throw new XServiceException("Parameter name 'class' is reserved. Use other parameter name.", XServiceException::ERR_INVALID_PARAMS);
//        if (isset($params['input']))
//            throw new XServiceException("Parameter name 'input' is reserved. Use other parameter name.", XServiceException::ERR_INVALID_PARAMS);
//        if (isset($params['output']))
//            throw new XServiceException("Parameter name 'output' is reserved. Use other parameter name.", XServiceException::ERR_INVALID_PARAMS);
//        if (isset($params['scenario']))
//            throw new XServiceException("Parameter name 'scenario' is reserved. Use other parameter name.", XServiceException::ERR_INVALID_PARAMS);

        if (isset($params['input']))
        {
            $inputModel = $params['input'];
            if (is_string($inputModel))
            {
                $inputModel = Yii::createComponent(array('class' => $inputModel));
                $this->assignAttributeSafely($inputModel, $params);
            }
            elseif($inputModel instanceof CModel)
            {
                /**
                * If $params['input'] is an object then it might be used as the params for the service (to avoid dumplicating the params pass to service)
                * We don't want to exten CAction class so here we will work around the runWithParam to pass parameters into action
                * 
                */
                if (count($params) == 1 || (count($params)== 2 && isset($params['output'])))
                {     
                    $method=new ReflectionMethod($this, 'action'.$actionId);
                    if(($count = $method->getNumberOfParameters()) == 1)
                    {     
                        $runParams = $method->getParameters();
                        if (($name = $runParams[0]->getName()) && $runParams[0]->isArray())
                            $params[$name] = $inputModel->attributes;
                        else
                            $params[$name] = $inputModel;
                    }
                    elseif ($count > 1)
                    {
                        $params = CMap::mergeArray($inputModel->attributes, $params);
                    }                
                }
            }
        }
        else
        {
            // get default Input model class name and set it to the definition of service
            if (!isset($this->methods[$actionId]['input']))
            {
                $default = $this->getModule()->id.'.services.models.'.str_replace('Api','',get_class($this)).ucfirst($actionId).'Input';
                if( file_exists(Yii::getPathOfAlias($default).'.php'))
                    $this->methods[$actionId]['input'] = $default;
            }
                
            if (isset($this->methods[$actionId]['input']))
            {
                /** @var CModel **/
                $inputModel = Yii::createComponent(array('class' => $this->methods[$actionId]['input']));
                $this->assignAttributeSafely($inputModel, $params);
            }
            elseif (YII_DEBUG)
            {
                if (isset(Yii::app()->XService) && Yii::app()->XService->warnIfNoInputValidation == true)
                Yii::log($this->getFullQualifiedServiceId($actionId).' is not using any input model to support validation.', CLogger::LEVEL_WARNING);
            }
        }        
        
        // TODO: set scenario for validation
        if (isset($inputModel) && $inputModel->validate() == false)
        {
            errorHandler()->log(new XModelManagedError($inputModel, XServiceException::ERR_INVALID_PARAMS));
            return false;
        }
        
        
        $this->params[$actionId] = $params;
        return true;
    }
    
    public function getApiResult(){
        return $this->result;
    }
    
    public function setApiResult($result, $setter)
    {
        if (!$setter instanceof XFilter)
            return;
        $this->result = $result;
    }

    /**
     * Runs the action after passing through all filters.
     * This method is invoked by {@link runActionWithFilters} after all possible filters have been executed
     * and the action starts to run.
     * @param CAction $action action to run
     */
    public function runAction($action)
    {
        /**
        * Fix bug : service is run but not return anything and not touch the $result object, 
        * so the returns result to caller could be result of the previous call to other service
        * in the same Api.
        * 
        * THIS NEED MANY TESTS TO BE SURE IT WORKS
        */
        $this->result = null;
        
        
        $priorAction=$this->_action;
        $this->_action=$action;
        if($this->beforeAction($action))
        {
            $params = $this->getActionParams($action->id);
            if (isset($params['output']))
            {
                $output = $params['output'];
                if (is_string($output))
                    $this->result = Yii::createComponent(array('class' => $output));
            }
            else
            {
                // get default ouput model class name and set it to the definition of service
                if (!isset($this->methods[$action->id]['output']))
                {
                    $default = $this->getModule()->id.'.services.models.'.str_replace('Api','',get_class($this)).ucfirst($action->id).'Output';
                    if( file_exists(Yii::getPathOfAlias($default).'.php'))
                        $this->methods[$action->id]['output'] = $default;
                }

                if (isset($this->methods[$action->id]['output']))
                    $this->result = Yii::createComponent(array('class' => $this->methods[$action->id]['output']));
                else
                {
                    // the service does not return a model, it return a scalar value
                }
            }
            unset($params['output']);
            
            if($action->runWithParams($this->getActionParams($action->id))===false)
                $this->invalidActionParams($action);
            else
                $this->afterAction($action);
        }
        $this->_action=$priorAction;
        
    }    

    /**
     * Returns the request parameters that will be used for action parameter binding.
     * 
     * This method overrides CController method where the params is get from GET/POST
     */
    public function getActionParams($actionId = null)
    {
        return $this->params[$actionId];
    }

    /**
    * Provide detailed information about the called service
     */
    public function invalidActionParams($action)
    {
        $serviceId = $this->getFullQualifiedServiceId($action->id);
        $errMsg = 'Called service '.$serviceId.' with invalid parameters.';
        
        $signature = array();
        $calledParams = $this->getActionParams($action->id);
        
        $method=new ReflectionMethod($this, 'action'.ucfirst($action->id));
        if($method->getNumberOfParameters()>0)
        {     
            foreach($method->getParameters() as $i=>$param)
            {
                $name=$param->getName();
                if (isset($calledParams[$name]))
                    $signature[] = "[{$name}] => {$calledParams[$name]}";
                elseif (($isDefault = $param->isDefaultValueAvailable()) === true)
                    $signature[] = "[{$name}] => default(".$param->getDefaultValue().')';
                else
                    $signature[] = "[{$name}] => REQUIRED";
            }
            $errMsg .= "\n".$serviceId."(\n".implode("\n  ", $signature)."\n)";
        }
        

        throw new XServiceException($errMsg, XServiceException::ERR_INVALID_PARAMS);
    }
    
    protected function getFullQualifiedServiceId($serviceName)
    {
        return $this->getModule()->id.'.'.str_replace('Api','',get_class($this)).'.'.$serviceName;        
    }
    
    /**
    * Safely assign values to only attributes the model has
    * 
    * @param CModel $model
    * @param Array $params
    */
    protected function assignAttributeSafely(&$model, &$params)
    {
        try
        {
            foreach($params as $attr => $value)
                if (!in_array($attr, array('class','input','output')))
                    $model->$attr = $value;
        }catch(CException $ex){}
    }
}