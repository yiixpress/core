<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php $widgetName = str_replace('Controller', '', $this->getControllerClass('View')); ?>
<?php echo "<?php\n"; ?>

Yii::import('<?php echo $this->Module->Id; ?>.controllers.<?php echo $this->baseControllerClass; ?>');
class <?php echo $this->getControllerClass('View'); ?> extends <?php echo $this->baseControllerClass; ?> implements ICmsWidget
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
            'id'=>array(
                'label'=>'id',
                'value'=>0,
                'description'=>'id',
                'setting_group'=>'General Settings',
                'visible'=>false,
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
        //1. get id from settings, if not isset id go 2
        //2. get id from $_GET
        $id = $this->id;
        $this->render($this->getWidgetLayout()->getViewFile(),array(
            'model'=>FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.get', array('id'=>$id))->model,
        ));
    }
    
    public function actionUpdate()
    {
        $formParams = $this->getFormParams();
        $formLayoutParams = $this->getFormLayoutParams();
        
        $widgetSetting = array();
        if (Yii::app()->request->IsPostRequest) {
            // save posted data
            $_POST['validateOnly'] = ($this->post('ajax','') == '<?php echo $this->class2id($this->modelClass); ?>-form');
            $result = FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.save', $_POST);
            $model = $result->model; 

            if ($this->post('ajax','') == '<?php echo $this->class2id($this->modelClass); ?>-form'){
                echo $result->getActiveErrorMessages($result->model);
                Yii::app()->end();
            }   
            if (! $result->hasErrors())
            {
                $this->message = Yii::t('Xpress','Item has been saved successfully.');
                //save widget settings
                $widgetSetting['widget'] = $formParams->saveParams($_POST, ParamForm::TO_ARRAY);
                //save widget layout settings
                $widgetSetting['layout'] = $formLayoutParams->saveParams($_POST, ParamForm::TO_ARRAY);
                //overwrite, save custom settings
                if (!is_object(self::$pageWidget))//create new
                    $widgetSetting['widget']['id']=$result->model->id;
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
        }
        else
        {
            $model = null;
            //1. get id from settings, if not isset id go 2
            //2. get id from $_GET
            $id = $this->id;
            if ($id)
                $model = FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.get', array('id' => $id))->model;
            if (!is_object($model))
                $model = new <?php echo $this->modelClass; ?>();
        }
        
        $this->render('form', array(
            'model' => $model,
            'formParams' => $formParams,
            'formLayoutParams' => $formLayoutParams,
        ));
    }
}
