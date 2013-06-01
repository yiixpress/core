<?php
require_once 'DiagnosticBaseController.php';
class ModuleController extends DiagnosticBaseController
{
    public $title = 'Modules will be loaded';
    
    public function actionIndex()
    {
		Yii::setPathOfAlias('site', Yii::app()->basePath . '/../sites/' . SITE_DIR . '/protected');
		Yii::setPathOfAlias('runtime', Yii::app()->basePath . '/../sites/' . SITE_DIR . '/protected/runtime');
        
		$file = Yii::app()->basePath.'/../sites/'.SITE_DIR.'/protected/runtime/cache/modules.php';
        if (!file_exists($file))
        {
            if (!Yii::app()->getComponent('XService'))
                $message = 'Module configuration is NOT FOUND. You can generate this configuration from the backend System &gt; Manage modules.';
            elseif ($this->successful != 'tried')
            {
                file_put_contents($file,'<?php return array(); ?>');
                $this->successful = 'tried';
                return $this->actionIndex();
            }
            else
                $message = 'Module configuration is NOT FOUND and cannot be generated.';
        }
        else
        {
            $this->successful = true;
            $message = '';
            $modules = include_once($file);
            if (empty($modules))
                $message = 'No module will be loaded. This can be fixed in the backend.';
            else
                foreach($modules as $id => $config)
                    $message .= "[{$id}] => {$config['class']}<br />";
        }
        $this->render('index',array('message' => $message));
    }
    
}
?>
