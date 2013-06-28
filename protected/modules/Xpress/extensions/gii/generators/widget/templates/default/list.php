<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php $widgetName = str_replace('Controller', '', $this->getControllerClass('List')); ?>
<?php echo "<?php\n"; ?>

Yii::import('<?php echo $this->Module->Id; ?>.controllers.<?php echo $this->baseControllerClass; ?>');
class <?php echo $this->getControllerClass('List'); ?> extends <?php echo $this->baseControllerClass; ?> implements ICmsWidget
{
	public $info = array(
        'path'=>'<?php echo $this->Module->Id; ?>/widgets/<?php $widgetName[0]=strtolower($widgetName[0]);echo $widgetName;?>',
        'name'=>'<?php echo $widgetName;?> Widget',
        'description'=>'<?php echo $widgetName;?> Widget',
        'images'=>'',
        'video'=>'',
        'author'=>'',
        'version'=>1,
    );
    
    public function getDefaultConfig()
    {
        return CMap::mergeArray(parent::getDefaultConfig(), array(
            'currentLayout'=>array(
                'label'=>'Layout',
                'value'=>'default',
                'description'=>'Widget Layout',
                'setting_group'=>'General Settings',
                'type'=>'dropdownlist',
                'items'=>array('default'=>'Default'),
                'visible'=>true,
            ),
            'PageSize' => array(
                'label'=>'Page Size',
                'value' => Settings::PAGE_SIZE,  
                'description'=>'Page Size',
                'rules' => array('numerical' => array('max' => 50, 'min' => 1)),
                'setting_group'=>'General Settings',
                'visible' => true,
            ),
            'PagerStyle' => array(
                'label'=>'Pager Style',  
                'value' => 1,
                'type' => 'dropdownlist',  
                'description'=>'Pager Style',
                'items' => array(0 => 'Next and Previous', 1 => 'Show page numbers'),
                'setting_group'=>'General Settings',
                'visible' => true,
            ),
            'ShowResultCoutner' => array(  
                'label'=>'Show Result Coutner',
                'value' => 1,
                'description'=>'Show Result Coutner',
                'type' => 'checkbox',
                'htmlOptions' => array('value' => 1),
                'setting_group'=>'General Settings',
                'visible' => true,
            ),
        ));
    }
    
    public function __construct($id,$module=null)
    {
        //new instance of widget => load widget settings
        $this->getWidgetSettings();
        parent::__construct($id,$module);
    }
    
    public function actionInstall()
    {
        $this->installWidget();
    }
    
    public function actionUninstall()
    {
        $this->uninstallWidget();
    }
    
    public function actionConfig()
    {
        $this->widgetConfig();
    }
    
    public function actionIndex()
    {
        $criteria = @unserialize($this->criteria);
        if (!is_object($criteria))
            $criteria = new CDbCriteria;
        $dataProvider=new CActiveDataProvider('<?php echo $this->modelClass; ?>', array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>$this->PageSize,
            ),
        ));
            
        $this->render($this->getWidgetLayout()->getViewFile(),array(
            'dataProvider'=>$dataProvider,
        ));
    }
    
    public function actionUpdate()
    {
        $formParams = $this->getFormParams();
        $formLayoutParams = $this->getFormLayoutParams();
        
        $widgetSetting = array();
        if (Yii::app()->request->IsPostRequest) {
            //save widget settings
            $widgetSetting['widget'] = $formParams->saveParams($_POST, ParamForm::TO_ARRAY);
            //save widget layout settings
            $widgetSetting['layout'] = $formLayoutParams->saveParams($_POST, ParamForm::TO_ARRAY);
            //TODO: create/update data search
            $criteria = $widgetSetting['widget']['criteria'] = $_POST['criteria'];
            
            if (parent::updateWidgetSettings($widgetSetting))
            {
                if (isset($_POST['continue']))
                    $this->refresh();
                else
                {
                    //close fancybox, reload page
                    Yii::app()->clientScript->registerScript('ReloadPage', "parent.window.location = parent.window.location;");
                }
            }
        }
        else
        {
            //TODO: load data search
            $criteria = new CDbCriteria;
            $criteria = serialize($criteria);
        }
        
        $this->render('form', array(
            'criteria' => $criteria,
            'formParams' => $formParams,
            'formLayoutParams' => $formLayoutParams,
        ));
    }
}
