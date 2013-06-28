<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

/**
* Extract data from input which is usually $_POST, $_GET or $_COOKIES
* 
* This utility class improve CHttpRequest::getParam (or getPost, getQuery) by not only
* return default value for unavailable input data but also filter the data, force value
* to be in the same data type of the expected value (same type of the default value).
* 
* It is recommended that you always call getInput function with default value.
* 
* You can also get a model object from input data quickly using getModel. There is always
* a model object returned even if data is not valid to set its attributes. If data represents
* an existing model in database, that data will be queried and model is ready for update
* 
* As Yii widely use ClassName[Attribute] for form input names, you can directly extract this
* data from the input using that syntax for name parameter.
* 
* @package Xpress
*/
class XInputFilter{
    /**
    * Array that contain input data
    * Possible values: $_POST, $_GET, $_COOKIE
    * 
    * @var mixed
    */
    protected $data;
    
    /**
    * Filter options
    * 
    * - xss: clean xss code
    * - notag: strip tags
    * - newline: standardize newline character
    * 
    * @var mixed
    */
    protected $filters = array('xss', 'notag', 'newline');
    
    public function __construct($data){
        $this->data = &$data;
    }
    
    /**
    * Extract an data from input data
    * 
    * @param string $name can be an normal name or 'Class[attribute]' to get input of a model attribute
    * 
    * @param mixed $defaultValue it is recommended to specify default value even when you
    * are sure that the input has data you want to extract.
    * 
    * @param string $filter 'xss,notag,newline' filters the data is run through before returned
    * do not combine xss with notag as it slow down the performance.
    */
    public function getInput($name, $defaultValue = null, $filter = ''){
        if (is_array($parsedName = $this->parseName($name))){
            $name = $parsedName[0];                         
            $key = $parsedName[1];
        }
        
        if (!array_key_exists($name, $this->data))
            return $defaultValue;
        else{
            $value = $this->data[$name];
            if (isset($key) && is_array($value)) $value = $value[$key];
            if (is_null($value)) return $defaultValue;
        }
            
        //Run filters
        if (is_string($value) || is_array($value)){
            foreach($this->filters as $flt){
                $value = call_user_func(array($this, $flt), $value, $filter);
            }
        }
        if (is_array($value)) return $value;
        
        $value = trim($value);
        //Convert to expected type
        if (is_null($defaultValue)) return trim($value);
        
        if (is_string($defaultValue)) return CPropertyValue::ensureString($value);
        if (is_int($defaultValue)) return CPropertyValue::ensureInteger($value);
        if (is_numeric($defaultValue)) return CPropertyValue::ensureFloat($value);
        
        if (is_array($defaultValue))
            if (!is_array($value)) 
                return array($value);
            else 
                return $value;
        
        return $value;
    }
    
    /**
    * Get a model object from input
    * 
    * The model object will be object of class parameter. It will be created
    * either new or from database and update with attributes from the input.
    * Input in this case must be an associative array of model attributes or
    * a model object of the same class.
    * 
    * All attributes in input will be set and this process does not use mass
    * attribute assignment so it is not affect by the safe rule of the model
    * 
    * @param mixed $class
    * @param mixed $excludedFilter array of filer rules for each attribute,
    * each filter is an array item in form 'attribute' => 'filter string'
    * @return CActiveRecord
    */
    public function getModel($class, $excludedFilter  = array()){
        $model = new $class;

        //Data must be an array of model attributes or a model instance
        if (!is_array($this->data) && !$this->data instanceof CModel){
            return $model;
        }
        
        if ($model instanceof CActiveRecord)
        {
            $pk = $model->getTableSchema()->primaryKey;
            if (! is_array($pk) && isset($this->data[$pk]) && !empty($this->data[$pk]))
                $model = $model->findByPk($this->data[$pk]);
        }

        //Assign model attributes
        if (is_array($this->data)){
            foreach ($this->data as $attr => $value) {
                $attrFitler = isset($excludedFilter[$attr])?$excludedFilter[$attr]:'';
                $model->$attr = $this->getInput($attr, null,$attrFitler);
            }
        } else
            $model = $this->data;
            
        return $model;
    }     
    
    
    /**
    * Parse the name in case it's posted in form of $_POST['Article']['Id']
    * In this case, the name is Article[Id]
    * 
    * @param mixed $name
    * @return array
    */
    protected function parseName($name){
        if (preg_match('/(\w+)\[(\w+)\]/', $name, $matches))
            return array($matches[1], $matches[2]);
        else
            return $name;
    }
    
    /**
    * Clean up malicious HTML tag bug keep safe tags remain. The cleaner tool is HTML Purifier
    * 
    * @param mixed $value
    * @param mixed $filter
    * @return Purified
    */
    protected function xss($value, $filter){
        if (strpos($filter, 'xss') !== false) 
            return $value;
        $purifier = new CHtmlPurifier();
        if (!is_array($value))
            return $purifier->purify($value);
        else{
            foreach($value as $k => &$v)
            {
                if (is_string($v))
                    $v = $purifier->purify($v);
                elseif (is_array($v))
                    $v = $this->xss($v,$filter);
            }
            return $value;
        }
    }
    
    /**
    * Remove tags completely
    * 
    * @param mixed $value
    * @param mixed $filter
    */
    protected function notag($value, $filter){
        if (strpos($filter, 'tag') !== false) 
            return $value;
        else
            if (!is_array($value))
                return strip_tags($value);
            else{
                foreach($value as $k => &$v)
                {
                    if (is_string($v))
                        $v = strip_tags($v);
                    elseif (is_array($v))
                        $v = $this->notag($v,$filter);
                }
                return $value;
            }
    }
    
    /**
    * Deal with NEWLINE character in Unix and Windows
    * while in Unix it is \r in Windows it \r\n
    * 
    * @param mixed $value
    * @param mixed $filter
    */
    protected function newline($value, $filter){
        if (strpos($filter, 'newline') !== false) 
            return $value;
        if (!is_array($value)){
            $value = str_replace("\n\r","\n", $value);
            $value = str_replace("\r","\n", $value);
            return $value;
        }else{
                foreach($value as $k => &$v){
                    if (is_string($v))
                    {
                        $v = str_replace("\n\r","\n", $v);
                        $v = str_replace("\r","\n", $v);
                    }
                    elseif (is_array($v))
                        $v = $this->newline($v,$filter);
                }
                return $value;            
        }
    }
}
?>
