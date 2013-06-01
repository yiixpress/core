<?php
require_once 'DiagnosticBaseController.php';
class DatabasesController extends DiagnosticBaseController
{
    public $title = 'Database connection';

    public function actionIndex()
    {
        $dbs = Yii::app()->session->get('dbs');
        $this->successful = true;
        if (is_array($dbs) && count($dbs))
        {
            foreach($dbs as $key => &$db)
            {
                $db['error'] = null;
                $con = new CDbConnection(
                    $db['connectionString'],
                    $db['username'],
                    $db['password']
                );
                try
                {
                    $con->active = true;
                    //
                    $con->active = false;
                }
                catch(CDbException $ex)
                {
                    $db['error'] = $ex->getMessage();
                    $this->successful = false;
                    
                }
            }    
        }
        if($this->successful) {
            $this->render('index', array(
                'dbs' => $dbs
            ));
        } else {
            $this->render('index', array(
                'dbs' => $dbs,'checkAgain'=>str_replace('Controller','',get_class($this))
            ));
        }
    }
    public function actioncheckAgain()
    {
        $request = new CHttpRequest();
        $hostInfo = parse_url($request->getHostInfo(), PHP_URL_HOST);
        
        // top level domain element
        $tldElements = array_slice(explode('.', $hostInfo), -2);
        // environment file locates in /sites/env folder using this naming convention: config.domain.php
        $envFile = 'config.'.$hostInfo.'.php';
        $envFile = Yii::app()->basePath.'/../sites/env/'.$envFile;
        $envFile = str_replace('protected/../','',$envFile);
        include($envFile);
        $this->successful = true;
        if (is_array($dbs) && count($dbs))
        {
            foreach($dbs as $key => &$db)
            {
                $db['error'] = null;
                $con = new CDbConnection(
                    $db['connectionString'],
                    $db['username'],
                    $db['password']
                );
                try
                {
                    $con->active = true;
                    $con->active = false;
                    echo CJSON::encode(array(
                        'msg' => strtr('<ul><li>Connect to DEFAULT database is OK</li></ul>',$this->defaultKeys()),
                    ));
                    // exit;
                    Yii::app()->end();
                }
                catch(CDbException $ex)
                {
                    echo CJSON::encode(array(
                        'msg' => $ex->getMessage(),
                    ));
                    // exit;
                    Yii::app()->end();
                }
            }    
        }
    }
}
?>
