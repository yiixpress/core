<?php

/**
 * This is the model class for table "{{blog_post}}".
 *
 * The followings are the available columns in table '{{blog_post}}':
 * @property string $id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property integer $revision
 * @property string $revision_log
 * @property integer $status
 * @property integer $views
 * @property string $date_added
 * @property string $date_updated
 */
class BlogPostBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return BlogPost the static model class
	 */
	public static function model($className='BlogPost')
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{blog_post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('revision_log', 'required'),
			array('revision, status, views', 'numerical', 'integerOnly'=>true),
			array('revision_log', 'length', 'max'=>512),
			array('title, alias, content, date_added, date_updated', 'safe'),
			array('date_added','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('date_updated','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, alias, content, revision, revision_log, status, views, date_added, date_updated', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'alias' => 'Alias',
			'content' => 'Content',
			'revision' => 'Revision',
			'revision_log' => 'Revision Log',
			'status' => 'Status',
			'views' => 'Views',
			'date_added' => 'Date Added',
			'date_updated' => 'Date Updated',
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

		$criteria->compare('id',$this->id, true);
		$criteria->compare('title',$this->title, true);
		$criteria->compare('alias',$this->alias, true);
		$criteria->compare('content',$this->content, true);
		$criteria->compare('revision',$this->revision);
		$criteria->compare('revision_log',$this->revision_log, true);
		$criteria->compare('status',$this->status);
		$criteria->compare('views',$this->views);
		$criteria->compare('date_added',$this->date_added, true);
		$criteria->compare('date_updated',$this->date_updated, true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>defined('SETTINGS_BO_PAGE_SIZE') ? SETTINGS_BO_PAGE_SIZE : 50,
            ),
		));
	}
}