<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if (is_object($form)):?>
<?php foreach($lookupColumns as $column): ?>
 * @property int<?php echo ' $'.$column->column_name."\n"; ?>
<?php endforeach; ?>
<?php if ($form->captcha):?>
* @property string $verifyCode;
<?php endif;?>
<?php endif;?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?>Base extends <?php echo $this->baseClass."\n"; ?>
{
<?php if ($form->captcha):?>
    public $verifyCode;
<?php endif;?>
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return <?php echo $modelClass; ?> the static model class
	 */
	public static function model($className='<?php echo $modelClass; ?>')
	{
		return parent::model($className);
	}

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '<?php echo $tableName; ?>';
    }

	/**
	 * @return string the associated database table name
	 */
	public function behaviors()
	{
		return array(
<?php if (is_object($form)):?>
<?php if (count($lookupColumns)):?>
<?php foreach ($lookupColumns as $column):
$voc = Vocabulary::model()->findByPk($column->lookup_value);
$module = is_object($voc) ? $voc->module : '';
?>
            '<?php echo $column->column_name;?>'=>array(
                'class'=>'TaxonomyBehavior',
                'name'=>'<?php echo $column->column_name;?>',
                'taxonomy'=><?php echo $column->lookup_value;?>,
                'module'=>'<?php echo $module;?>',
                'useActiveField'=>true,
            ),
<?php endforeach;?>
<?php endif;?>
<?php endif;?>
            'author'=>array(
                'class'=>'AuthorBehavior',
            ),
            'timestamp'=>array(
                'class'=>'TimestampBehavior',
            ),
<?php if (is_object($form)):?>
<?php if ($form->log):?>
            'logForm'=>array(
                'class'=>'LogFormBehavior',
                'tableName'=>SITE_ID.'_'.'form_submission_log',
            ),
<?php endif;?>
<?php endif;?>
        );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
<?php if (is_object($form)):?>
<?php if ($form->captcha):?>
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
<?php endif;?>
<?php endif;?>
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
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
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$label',\n"; ?>
<?php endforeach; ?>
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

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
        if (Yii::app()->db->driverName == 'pgsql')
            echo "\t\t\$criteria->addSearchCondition('$name',\$this->$name,true,'AND','ILIKE');\n";
        else
		    echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>defined('Settings::BO_PAGE_SIZE') ? Settings::BO_PAGE_SIZE : 50,
            ),
		));
	}
}