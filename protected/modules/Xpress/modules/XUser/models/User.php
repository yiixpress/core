<?php

/**
 * This is the model class for table "tagrem_indition2_user".
 */
require_once(dirname(__FILE__).'/base/UserBase.php');
Yii::import('XUser.models.UserGroup');
class User extends UserBase
{
    const VALIDATION_TYPE_ACTIVATE = 0;
    const VALIDATION_TYPE_RESET_PASSWORD = 1;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_UNAPPROVED = -1;
    const STATUS_BANNED = -999;
    const STATUS_DELETED = -9;

    public $confirmPassword;
    public $passwordOld;
    public $remember;

    public function getModule()
    {
        return 'XUser';
    }

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
        Yii::import('XUser.models.UserGroup');
        return UserGroup::model()->findAll("status = true");
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
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'checkEmailExists', 'on'=>'insert, update'),
            array('email', 'checkEmailNotExists', 'on'=>'recoverPassword'),
            array('validation_type, status', 'numerical', 'integerOnly'=>true),
            array('email', 'length', 'max'=>255),
            array('password, passwordOld', 'length', 'min'=>5, 'max'=>128),
            array('validation_code', 'length', 'max'=>64),
            array('last_login, validation_expired, creation_datetime, last_update', 'safe'),

            // auto update creation and last update datetime
            array('creation_datetime','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert,signup'),
            array('last_update','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false),

            // login scenario & change password
            array('password', 'required', 'on'=>'change_password, recoverChangePassword, insert, login'),
            array('confirmPassword', 'compare', 'compareAttribute' => 'password', 'on'=>'change_password, insert, update'),
            array('passwordOld', 'required', 'on'=>'change_password'),

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, email, password, last_login, validation_code, validation_type, validation_expired, status, creation_datetime, last_update, user_group_id', 'safe', 'on'=>'search'),
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

    public function checkEmailNotExists($attribute, $params)
    {
        if (!$this->hasErrors($attribute))
        {
            if (!empty($this->email))
            {
                $user = self::model()->findByAttributes(array('email' => $this->email));
                if (!$user){
                    $this->addError('email', 'This email doesn\'t exist in our system.');
                    return false;
                }
            }
        }
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

    public function getGroupText()
    {
        if (class_exists('BUser', false) && method_exists('BUser','getGroupTextExt'))
            return $this->getGroupTextExt();

        $group = UserGroup::model()->findByPk($this->user_group_id);
        if (!is_null($group))
        {
            return $group->name;
        }
        return '';
    }

    public function beforeValidate() {
        $this->email = strtolower($this->email);
        return parent::beforeValidate();
    }
}