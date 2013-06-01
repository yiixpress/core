<?php
require_once 'DiagnosticBaseController.php';
class WritableFoldersController extends DiagnosticBaseController
{
    public $title = 'Folders have to be writable';

    public function actionIndex()
    {
        if (defined('SITE_DIR'))
        {
            $basedir = str_replace('protected/../','',Yii::app()->basePath.'/../sites/'.SITE_DIR);
            $dirs = array(
                $basedir.'/assets' => 'NOT OK',
                $basedir.'/uploads' => 'NOT OK',
                $basedir.'/protected/runtime' => 'NOT OK',
                $basedir.'/protected/runtime/cache' => 'NOT OK',
                $basedir.'/protected/runtime/cache/templates_c' => 'NOT OK',
            );
            
            $this->successful = true;
            foreach($dirs as $path => $ok)
                if (is_writable($path))
                    $dirs[$path] = 'OK';
                elseif (!file_exists($path))
                {
                    $dirs[$path] = 'NOT FOUND';
                    $this->successful = false;
                }
                else // not writable
                    $this->successful = false;
        }
        else
        {
            $dirs = array();
            $this->successful = false;
        }
        if(!$this->successful) {
            $this->render('index',array('dirs' => $dirs,'checkAgain'=>str_replace('Controller','',get_class($this))));
        } else {
            $this->render('index', array(
                'dirs' => $dirs
            ));
        }
        
    }
    public function actioncheckAgain()
    {
        if (defined('SITE_DIR'))
        {
            $basedir = str_replace('protected/../','',Yii::app()->basePath.'/../sites/'.SITE_DIR);
            $dirs = array(
                $basedir.'/assets' => 'NOT OK',
                $basedir.'/uploads' => 'NOT OK',
                $basedir.'/protected/runtime' => 'NOT OK',
                $basedir.'/protected/runtime/cache' => 'NOT OK',
                $basedir.'/protected/runtime/cache/templates_c' => 'NOT OK',
            );
            
            $this->successful = true;
            $message = '<ul>';
            foreach($dirs as $path => $ok){
                if (is_writable($path))
                    $message .= '<li>'.strtr($path.' is OK',$this->defaultKeys()).'</li>';
                elseif (!file_exists($path))
                {
                    $message .= '<li>'.strtr($path.' is NOT FOUND',$this->defaultKeys()).'</li>';
                    $this->successful = false;
                }
                else // not writable
                    $this->successful = false;
            }
            $message .= '</ul>';
        }
        else
        {
            $dirsPath = array();
            $this->successful = false;
        }
        echo CJSON::encode(array(
            'msg'=>$message
        ));
        // exit;
        Yii::app()->end();
        
    }
}
?>
