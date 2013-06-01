<?php
require_once 'DiagnosticBaseController.php';
class EnvironmentController extends DiagnosticBaseController
{
    public $title = 'Environment File';
    
    public function actionIndex()
    {
        /**
        * This check is done in the config file bug here we need the filename so the same code
        * is used. If the file exists, it's included into the config file, all the constants
        * and database parameters are available.
        */
        
        $request = new CHttpRequest();
        $hostInfo = parse_url($request->getHostInfo(), PHP_URL_HOST);
        
        // top level domain element
        $tldElements = array_slice(explode('.', $hostInfo), -2);
        // support single login to manage different sub domain    
        $domain = '.'.implode('.', $tldElements);
        $GLOBALS['TLD'] = $domain;
        // environment file locates in /sites/env folder using this naming convention: config.domain.php
        $envFile = 'config.'.$hostInfo.'.php';
        $envFile = Yii::app()->basePath.'/../sites/env/'.$envFile;
        $envFile = str_replace('protected/../','',$envFile);
        
        $message = strtr("Environment file {file} is {found}", array(
            '{file}' => $envFile,
            '{found}' => (file_exists($envFile) ? 'FOUND' : 'NOT FOUND')
        ));
        
        if(isset($_POST['EnvForm'])) {
            if(Yii::app()->getRequest()->getIsAjaxRequest()) {
                $model = new EnvForm();
                $validation = CActiveForm::validate( array( $model));
                if($validation != '[]') {
                    echo $validation;
                    Yii::app()->end();
                } 
                //
            }
            
            $contents = $this->envTemplate;            
            $contents = strtr($contents, array(
                '{dbs}' => '$dbs',
                '{SITE_DIR}' => $_POST['EnvForm']['site_owner'].'/'.$_POST['EnvForm']['site_id'],
                '{SITE_OWNER}' => $_POST['EnvForm']['site_owner'],
                '{SITE_ID}' => $_POST['EnvForm']['site_id'],
                '{server_host}' => $_POST['EnvForm']['server_host'],
                '{dbport}' => $_POST['EnvForm']['dbport'],
                '{dbname}' => $_POST['EnvForm']['dbname'],
                '{dbusername}' => $_POST['EnvForm']['dbusername'],
                '{dbpassword}' => $_POST['EnvForm']['dbpassword'],
            ));
            
            try {
                file_put_contents($envFile, $contents);
                chmod($envFile, 0755);
                chmod(Yii::app()->basePath.'/../sites/env',0755);
            } catch (CException $ex) {
                echo CJSON::encode(array(
                    'msg' => $ex->getMessage(),
                    'url' => $this->createUrl('/')
                ));
            }
            
            echo CJSON::encode(array(
                'msg' => 'Enviroment file created.',
                'url' => $this->createUrl('/')
            ));
            // exit;
            Yii::app()->end();
        }
        
        if (file_exists($envFile)) {
            include($envFile);
            Yii::app()->session->add('dbs',$dbs);
            $this->successful = true;
            $this->render('index', array(
                'message' => $message
            ));
        } else if(!file_exists($envFile)) {
        	
            $model = new EnvForm();
            $envDir = Yii::app()->basePath.'/../sites/env';
            $msg = '';
            
            if(!is_writable($envDir)) {
                $msg = 'Make sure the folder "'.$envDir.'" is writable during the diagnostic.';
            }
            $this->successful = false;
            $this->render('index', array(
                'message' => $message,'msg'=>$msg, 'model'=>$model, 'checkAgain'=>str_replace('Controller','',get_class($this))
            ));
        }
    }
    
    public function actionCheckAgain() {
        $request = new CHttpRequest();
        $hostInfo = parse_url($request->getHostInfo(), PHP_URL_HOST);
        // environment file locates in /sites/env folder using this naming convention: config.domain.php
        $envFile = 'config.'.$hostInfo.'.php';
        $envFile = Yii::app()->basePath.'/../sites/env/'.$envFile;
        $envFile = str_replace('protected/../','',$envFile);
        
        $envDir = Yii::app()->basePath.'/../sites/env';
        $msg = '';
        if(!file_exists($envFile)) {
            if(!is_writable($envDir)) {
                $msg = 'Make sure the folder "'.$envDir.'" have to be writable.';
            }
        }
        $message = strtr("Environment file {file} is {found} ".$msg, array(
            '{file}' => $envFile,
            '{found}' => (file_exists($envFile) ? 'FOUND' : 'NOT FOUND')
        ));
        $message = strtr($message,$this->defaultKeys());
        echo CJSON::encode(array(
            'msg' => $message,
        ));
        // exit;
        Yii::app()->end();
    }
    
    
    public function getEnvTemplate() {
        return "<?php
/**
* Path to custom code folder under sites/<site owner>/<site> 
*/

defined('SITE_DIR') or define('SITE_DIR', '{SITE_DIR}');

/**
* The site's owner, support one user/organization to maintain many sites.
* This constant is currently used only by [SITE_OWNER]_sites table to group
* all sites belong to one owner.
* 
* It's suggested that the  site owner is part of the SITE_ID value
*/

defined ('SITE_OWNER') or define('SITE_OWNER', '{SITE_OWNER}');

/**
* The site id identify the site among other sites of the same owner.
* This value is used as table prefix ans as the session's cookie name
*/

defined ('SITE_ID') or define('SITE_ID', '{SITE_ID}');

/***** STARTING DEFINITION FOR ALL DATABASES ******/
{dbs} = array();
/* the default db will be mapped to Yii's db component */

{dbs}['db']['connectionString'] = 'mysql:host={server_host};port={dbport};dbname={dbname};';
{dbs}['db']['username'] = '{dbusername}';
{dbs}['db']['password'] = '{dbpassword}';


/**
* It's recommended that you consider the 'partition' option for your database
* for purpose of performance turning and security. Yii allows you to have different
* db components pointing to the same database so that each component should be a
* partition of your database. One partition can hold only hardly-ever change table
* so you can cache query result. Other partican can hold critical / secure data
* require special credential to write.
*/";
    }
}
?>
