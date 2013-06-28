<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php $layoutName = str_replace('DefaultLayout', '', $this->controllerClass); ?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?>
{
	public $info = array(
        'path'=>'<?php echo $this->Module->Id; ?>.layouts.<?php echo $this->controllerClass; ?>',
        'name'=>'<?php echo $layoutName;?> Default Widget',
        'description'=>'<?php echo $layoutName;?> Default Widget',
        'images'=>'',
        'author'=>'',
        'version'=>1,
    );
    
    /**
    * 
    * @return array
    * 
    * 'name' => array(
                'type' => CHtml::function or Yii path to a widget. 
                            If CHtml function then the function name is in lower case. 
                            Suffixes like 'field' or 'button' must be removed
                'rules' => array('rule name - see Yii rule classes' => array(config)),
                'items' => array(key => value) //used for dropDownList of similar CHtml functions
                'htmlOptions' => array()
                // following items are used to setup the widget setting info while installing  
                'label'           => 'Short friendly param name'
                'value'           => 'default value'
                'description'     => 'Full description'
                'setting_group'   => 'General Settings'
                'ordering'        => 0
                'visible'         => 0
            ),
    */
    public function getDefaultConfig()
    {
        return CMap::mergeArray(parent::getDefaultConfig(),array(
            'view'=>array(
                'label'=>'View file',
                'value'=>'view',
                'description'=>'View file',
                'setting_group'=>'General Settings',
                'type'=>'dropdownlist',
                'items'=>array('view'=>'Default'),
                'visible'=>true,
            ),
        ));
    }
}
