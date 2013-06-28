<?php

/**
 * This is the model class for table "tagrem_indition2_admin_user".
 */
require_once(dirname(__FILE__).'/base/AdminUserBase.php');
class AdminUser extends AdminUserBase
{
    public $confirmPassword;
    
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    public function validateStatus()
    {
        $statuses = array(
            1   => TRUE,
            2   => TRUE, // need to change password
            0   => 'Your account is not activated.',
            -1  => 'Your account is banned',
            -9  => 'Your account is deleted'
        );
        return $statuses[$this->status];
    }
   
    public function getGroupOptions()
    {
        Yii::import('XUser.models.AdminUserGroup');
        return AdminUserGroup::model()->findAll("status = true");
    }
    
    public function getStatusOptions()
    {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        );
    }
    
    public function getStatusText()
    {
        $options = $this->getStatusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : Yii::t('app', 'Unknown {att}', array('{att}'=>$this->status));
    }
    
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('username', 'required', 'on'=>'insert, update'),
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'checkEmailExists', 'on'=>'insert, update'),
            array('validation_type, status', 'numerical', 'integerOnly'=>true),
            array('email', 'length', 'max'=>255),
            array('password', 'length', 'min'=>5, 'max'=>128),
            array('last_login, validation_expired, creation_datetime, last_update', 'safe'),
            array('username', 'CRegularExpressionValidator', 'pattern' => '/^([0-9a-zA-Z]+)$/', 'message' => 'Only letter and number'),
            //array('username, validation_code', 'length', 'max'=>64),
            
            // auto update creation and last update datetime
            array('creation_datetime','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
            array('last_update','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false),
            
            array('password', 'required', 'on'=>'insert, login'),
            array('confirmPassword', 'compare', 'compareAttribute' => 'password', 'on'=>'insert, update'),
            
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, email, password, last_login, validation_code, validation_type, validation_expired, status, creation_datetime, last_update, user_group_id, username', 'safe', 'on'=>'search'),
        );
    }
    
    public function checkEmailExists($attribute, $params)
    {
        if (!$this->hasErrors($attribute))
        {
            if (!empty($this->email))
            {
                $user = self::model()->findByAttributes(array('email' => $this->email));
                if (!is_null($user) && $user->id != $this->id){
                    $this->addError('email', 'This e-mail has already been taken.');
                    return false;
                }
            }
        }
    }
    
    public function getGroupText()
    {
        $group = AdminUserGroup::model()->findByPk($this->user_group_id);
        if (!is_null($group))
        {
            return $group->name;
        }
        return '';        
    }
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;
        
        $criteria->addSearchCondition('username',$this->username,true,'AND','ILIKE');
        $criteria->addSearchCondition('email',$this->email,true,'AND','ILIKE');
        $criteria->compare('user_group_id',$this->user_group_id);
        //$criteria->addSearchCondition('last_login',$this->last_login,true,'AND','ILIKE');
        $criteria->compare('status',$this->status);
        //$criteria->addSearchCondition('creation_datetime',$this->creation_datetime,true,'AND','ILIKE');
        //$criteria->addSearchCondition('last_update',$this->last_update,true,'AND','ILIKE');

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize' => defined('SETTINGS_BO_PAGE_SIZE') ? SETTINGS_BO_PAGE_SIZE : 50,
            ),
        ));
    }
    
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'last_login' => 'Last Login',
            'validation_code' => 'Validation Code',
            'validation_type' => 'Validation Type',
            'validation_expired' => 'Validation Expired',
            'status' => 'Status',
            'creation_datetime' => 'Creation Datetime',
            'last_update' => 'Last Update',
            'user_group_id' => 'User Group',
        );
    }
}