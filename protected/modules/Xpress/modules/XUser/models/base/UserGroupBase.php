<?php

/**
 * This is the model class for table "tagrem_indition2_user_group".
 *
 * The followings are the available columns in table 'tagrem_indition2_user_group':
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $creation_datetime
 * @property string $last_update
 */
class UserGroupBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserGroup the static model class
	 */
	public static function model($className='UserGroup')
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return SITE_ID.'_user_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('creation_datetime, last_update', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, status, creation_datetime, last_update', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
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
        
        $criteria->addSearchCondition('name',$this->name,true,'AND','ILIKE');
		$criteria->compare('status',$this->status);
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
            'pagination'=>array(
//                'pageSize'=>defined('Settings::BO_PAGE_SIZE') ? Settings::BO_PAGE_SIZE : 50,
                'pageSize'=>50,
            ),
		));
	}
}