<?php
class DiagnosticBaseController extends CController
{
    public $title = 'Check ...';
    public $successful = false;
    
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        Yii::app()->theme = 'global';
        $this->layout = 'basic';        
    }
    
    public function actionIndex()
    {
        echo get_class($this).' should overrides actionIndex() function.';
    }
    
    public function defaultKeys() {
        return $keywords = array(
            // bad result
            'NOT FOUND' => '<span class="label label-important">not found</span>',
            'NOT OK' => '<span class="label label-important">NOT OK</span>',
            'FAIL' => '<span class="label label-important">fail</span>',
            'INVALID' => '<span class="label label-important">invalid</span>',
            'NOT READABLE'=>'<span class="label label-important">not readable</span>',
            // good result
            'FOUND' => '<span class="label label-success">found</span>',
            'OK' => '<span class="label label-success">OK</span>',
            'SUCCESSFUL' => '<span class="label label-success">successful</span>',
            'VALID' => '<span class="label label-success">valid</span>',
            'DEFAULT' => '<strong>default</strong>',
            'READABLE'=>'<span class="label label-success">readable</span>',
        );
    }
}
?>
