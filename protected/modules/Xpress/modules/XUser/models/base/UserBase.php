<?php

/**
 * This is the model class for table "tagrem_indition2_user".
 *
 * The followings are the available columns in table 'tagrem_indition2_user':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $last_login
 * @property string $validation_code
 * @property integer $validation_type
 * @property string $validation_expired
 * @property integer $status
 * @property string $creation_datetime
 * @property string $last_update
 */
class UserBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className='User')
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return SITE_ID.'_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'required'),
			array('validation_type, status', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>255),
			array('password', 'length', 'max'=>128),
			array('validation_code', 'length', 'max'=>64),
			array('last_login, validation_expired, creation_datetime, last_update', 'safe'),
			array('creation_datetime','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('last_update','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, last_login, validation_code, validation_type, validation_expired, status, creation_datetime, last_update, user_group_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
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
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

        $this->id = trim($this->id);
		if (!empty($this->id))
        {
            if (is_numeric($this->id))
                $criteria->compare('id',intval($this->id));
            else
                $criteria->compare('id',-1);
        }    
        $criteria->compare('user_group_id',$this->user_group_id);
		$criteria->addSearchCondition('email',$this->email,true,'AND','ILIKE');
		$criteria->addSearchCondition('password',$this->password,true,'AND','ILIKE');
		$criteria->addSearchCondition('last_login',$this->last_login,true,'AND','ILIKE');
		$criteria->addSearchCondition('validation_code',$this->validation_code,true,'AND','ILIKE');
		$criteria->compare('validation_type',$this->validation_type);
		$criteria->addSearchCondition('validation_expired',$this->validation_expired,true,'AND','ILIKE');
		$criteria->compare('status',$this->status);
		$criteria->addSearchCondition('creation_datetime',$this->creation_datetime,true,'AND','ILIKE');
		$criteria->addSearchCondition('last_update',$this->last_update,true,'AND','ILIKE');

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>defined('SETTINGS_BO_PAGE_SIZE') ? SETTINGS_BO_PAGE_SIZE : 50,
            ),
            'sort' => array(
                'defaultOrder' => 't.id DESC, t.creation_datetime DESC',
            ),
		));
	}
    
    public function behaviors()
    {
        return array(
            'extended'=>array('class' => app()->Xpress->extendModel('User.models.User'))
        );
    }

}