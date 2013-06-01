<?php
class ModuleBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Module the static model class
	 */
	public static function model($className='Module')
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return SITE_ID.'_'.'module';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('name', 'required'),
			array('name', 'unique'),
			array('ordering', 'numerical', 'integerOnly'=>true),
			array('name, version', 'length', 'max'=>64),
			array('friendly_name, icon', 'length', 'max'=>255),
			array('has_back_end', 'length', 'max'=>1),
			array('description, enabled, is_system', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, friendly_name, description, version, has_back_end, ordering, icon, enabled, is_system', 'safe', 'on'=>'search'),
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
			'friendly_name' => 'Friendly Name',
			'description' => 'Description',
			'version' => 'Version',
			'has_back_end' => 'Has Back End',
			'ordering' => 'Ordering',
			'icon' => 'Icon',
			'enabled' => 'Enabled',
			'is_system' => 'Is System',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('friendly_name',$this->friendly_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('has_back_end',$this->has_back_end,true);
		$criteria->compare('ordering',$this->ordering);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('is_system',$this->is_system);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'friendly_name',
            ),
            'pagination'=>array(
                'pageSize'=>100,
            ),
            
		));
	}
}