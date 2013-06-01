<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

class XFilter extends CFilter
{
    public $apiId;
    public $hooks;
    
    /**
    * Run pre hooks of a service.
    * 
    * A pre hook is called with the same parameters passed into the service
    * and should return either
    * 
    *  - false : prevent all up coming prehook and the service to run
    *  - true : continue with up coming prehooks
    *  - array : continue withup comming prehooks but with the modified the parameters
    * 
    * @param mixed $filterChain
    * @return boolean
    */
    public function preFilter($filterChain)
    {
        $params = $filterChain->controller->getActionParams($filterChain->action->id);
        foreach($this->hooks as $hook)
            if (strpos($hook,'before') !== false)
            {
                $result = Yii::app()->XService->run($hook,$params);
                // hook returns false or hook logs errors which case XService return null
                if ($result === false || $result === null)
                    return false;
                // go ahead
                elseif ($result === true)
                    Yii::trace($hook.' run successfully.');
                // go ahead but params are changed
                elseif (is_array($result))
                {
                    foreach($params as $key => $value)
                        if (isset($result[$key]))
                            $params[$key] = $result[$key];
                    Yii::trace($hook.' run successfully.');
                }    
            }
        // this will cause the whole process of checking input model and validation happens AGAIN!
        // but it seems to be reasonable to as the filter can modified params drastically
        $filterChain->controller->setApiParams($filterChain->action->id, $params);
        return true;
    }
    
    public function postFilter($filterChain)
    {
        $params = $filterChain->controller->getActionParams($filterChain->action->id);
        $params['apiResult'] = $filterChain->controller->getApiResult();
        foreach($this->hooks as $hook)
            if (strpos($hook,'after') !== false)
            {
                $result = Yii::app()->XService->run($hook,$params);
                if ($result === false)
                    return false;
                else
                {
                    $filterChain->controller->setApiResult($result,$this);                    
                    Yii::trace($hook.' run successfully.');
                }
//                elseif ($result === true)
//                    Yii::trace($hook.' run successfully.');
//                elseif (is_array($result))
//                {
//                    $params = CMap::mergeArray($params, $result);
//                    Yii::trace($hook.' run successfully.');
//                }    
            }
        // return true on postFilter is not required
        // return true;        
    }
}