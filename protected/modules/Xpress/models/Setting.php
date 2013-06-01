<?php
/**
-------------------------
GNU GPL COPYRIGHT NOTICES
-------------------------
This file is part of FlexicaCMS.

FlexicaCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

FlexicaCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with FlexicaCMS.  If not, see <http://www.gnu.org/licenses/>.*/

/**
 * $Id$
 *
 * @author FlexicaCMS team <contact@flexicacms.com>
 * @link http://www.flexicacms.com/
 * @copyright Copyright &copy; 2009-2010 Gia Han Online Solutions Ltd.
 * @license http://www.flexicacms.com/license.html
 */


/**
* 
* Setting Param model class. This model is for 'settings' table which contains
* CMS setting parameters. For other module, a '<module>_settings' table should 
* be used for model setting paramters and the model class for this table should
* extends this class.
* 
* $param->Html give the HTML code to render the parameter input control. Based
* on metadata key 'widget', the parameter input can be render simply as a textbox
* or more specifically as a checkbox, dropdown, etc. (supported by CHtml helper) 
* or even as a custom widget. See SettingParams model for structure of a parameter
* meta array.
* 
* Do not define rules for this model as the only attribute to be saved is Value
* which has its own set of rules define in param's 'metadata key 'rules'. 
* Consequently, validate() method is overrided to used defined rules in metadata
*/
require_once(dirname(__FILE__).'/base/SettingBase.php');
class Setting extends SettingBase
{
    protected $meta = null;
    protected function getMeta(){
        if (empty($this->meta)){
            $this->meta = array(
                'widget' => 'textField',
                'rules' => array(),
                'params' => array(),
                'reader' => '',
                'writer' => ''
            );
            if (empty($this->Module))
            {
                Yii::import('Core.models.SettingParams');
            } elseif (file_exists(Yii::getPathOfAlias($this->Module.'.models.SettingParams').'.php')) {
                Yii::import($this->Module.'.models.SettingParams');
            }
            $settingParams = SettingParams::params();
            if (isset($settingParams[$this->Name]))
                foreach($settingParams[$this->Name] as $key => $value)
                    $this->meta[$key] = $value;
        }
        return $this->meta;
    }
    
    public function getHtml(){
        $meta = $this->Meta;
        $widget = $meta['widget'];
        $params = $meta['params'];
                    
        if (is_string($widget) && is_callable(array('FHtml',$widget))){
            $params['name'] = $this->Name;
            $params['value'] = $this->Value;
            
            return call_user_func_array(array('FHtml', $widget), $params);
        }else{
            $path = $widget['class'];
            $params[$widget['nameParam']] = $this->Name;
            $params[$widget['valueParam']] = $this->Value;
            
            //$widget = Yii::app()->controller->createWidget($path, $params);
            return Yii::app()->controller->createWidget($path, $params)->run();
        }
    }
    
    public function __set($name, $value){
        if ($name != 'Value') 
            return parent::__set($name, $value);
            
        $meta = $this->Meta;
        if ($meta['writer'] != ''){
            $result = Cms::rawService($meta['writer'], array('Value' => $value));
            if($result->ReturnCode == ServiceResult::RETURN_SUCCESS)
                $value = $result->ReturnedData['Value'];
            else{
                $this->addError('Value', $result->ErrorMessages[0]);
            }
        }
        return parent::__set($name, $value);
    }
    
    
    public function validate($attributes=null, $clearErrors=true){
        //If the writer service already set error, just return false
        if($this->getError('Value') != null) return false;
        
        $meta = $this->Meta;
        $valid = 1;
        foreach($meta['rules'] as $rule => $params){
            if (!strpos($rule,' ') && !strpos($rule,'[param]')){
                $validator = CValidator::createValidator($rule, $this, array('Value'), $params);
                
                $validator->validate($this);
                $error = $this->getError('Value');
                if (!is_null($error)){
                    //Error encounterred
                    $error = str_replace('Value', $this->Label, $error);
                    $this->clearErrors('Value');
                    $this->addError('Value', $error);
                    $valid = 0;
                    break;
                }
            }else{
                /**
                * Complicated validation expression
                * In case Yii supported validators are not enough, a custom rule 
                * in format $exp => $error. 
                * An 'numerical' Yii validator can be defined in this way as 
                * '[param] > 0 && [param] < 50' => 'Param should be between 0-50'
                */
                $str = '$valid = ('.str_replace('[param]', $this->Value, $rule).');';
                $error = $params;
                eval($str);
                if (!$valid){
                    $this->addError('Value', $error);
                    break;
                }
            }
        }
        return $valid;
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('Name, Label', 'required'),
            array('Ordering', 'numerical', 'integerOnly'=>true),
            array('Name, Label, GroupName', 'length', 'max'=>64),
            array('Description', 'length', 'max'=>255),
            array('Value', 'safe'),
        );
    }
}