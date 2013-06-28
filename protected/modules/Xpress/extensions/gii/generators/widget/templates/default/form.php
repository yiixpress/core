<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php $widgetName = str_replace('Controller', '', $this->getControllerClass('Form')); ?>
<?php echo "<?php\n"; ?>

Yii::import('<?php echo $this->Module->Id; ?>.controllers.<?php echo $this->baseControllerClass; ?>');
class <?php echo $this->getControllerClass('Form'); ?> extends <?php echo $this->baseControllerClass; ?> implements ICmsWidget
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
            'success'=>array(
                'label'=>'Success Message',
                'value'=>'text',
                'description'=>'Success Message',
                'setting_group'=>'General Settings',
                'type'=>'radiolist',
                'items'=>array('text'=>'Show Text','redirect'=>'Redirect Url'),
                'visible'=>true,
            ),
            'success_text'=>array(
                'label'=>'Show text',
                'value'=>'Success! Your submission has been saved!',
                'description'=>'Show text',
                'setting_group'=>'General Settings',
                'visible'=>true,
            ),
            'success_redirect'=>array(
                'label'=>'Redirect Url',
                'value'=>'http://',
                'description'=>'Redirect Url',
                'setting_group'=>'General Settings',
                'visible'=>true,
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
                $redirectUrl = $this->success_redirect;
                if ($this->success == 'redirect' && !empty($redirectUrl))
                {
                    $this->redirect($this->success_redirect);
                }
                else
                {
                    $this->message = $this->success_text;
                    $this->refresh();
                }
            }
        } else {
            $model = new <?php echo $this->modelClass; ?>();
        }
         
        $this->render($this->getWidgetLayout()->getViewFile(),array(
            'model'=>$model,
        ));
    }
    
    public function actionUpdate()
    {
        $formParams = $this->getFormParams();
        $formLayoutParams = $this->getFormLayoutParams();
        
        $widgetSetting = array();
        if (Yii::app()->request->IsPostRequest) {
            $this->message = Yii::t('Xpress','Item has been saved successfully.');
            //save widget settings
            $widgetSetting['widget'] = $formParams->saveParams($_POST, ParamForm::TO_ARRAY);
            //save widget layout settings
            $widgetSetting['layout'] = $formLayoutParams->saveParams($_POST, ParamForm::TO_ARRAY);
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
        
        $this->render('form', array(
            'formParams' => $formParams,
            'formLayoutParams' => $formLayoutParams,
        ));
    }
}
