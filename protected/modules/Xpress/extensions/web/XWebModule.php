<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

Yii::import('Xpress.extensions.web.IXModule');

/**
* Base class for Xpress module
* @package Xpress
*/
abstract class XWebModule extends CWebModule implements IXModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        if (Yii::getPathOfAlias($this->Id))
            $this->setImport(array(
                $this->Id.'.models.*',
                $this->Id.'.components.*',
            ));
        
        // api map
        $this->controllerMap = CMap::mergeArray(
            $this->controllerMap, 
            XService::loadApiMap(Yii::getPathOfAlias($this->Id))
        );
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
    
    /**
    * Load module settings
    */
    public function loadSettings()
    {
        static $loaded = false;
        if ($loaded) return;
        if (!array_search($this->id, array('gii','Admin','Xpress')))
        {            
            $file = Yii::getPathOfAlias('runtime').'/cache/'.$this->id.'Settings.php';
            if (!file_exists($file))
            {
                Yii::log('Generate settings for '.$file);
                app()->XService->run('Xpress.Settings.db2php',array('module' => $this->id));
            }
            if (file_exists($file)) // still need to check this because some module doesnot has settings
            {
                include_once $file;
            }
        }        
        $loaded = true;
    }
    
    public function getBackendMenuItems()
    {
        return array(
            //array('title' => 'Menu title', 'url' => '/ModuleName/admin/controller/action'),
            //array('title' => '---', 'url' => ''), // this is a separator
        );
    }
    
    public function getPages()
    {
        return array();
        // the following code is sample for you to copy and use in the derived module class
        return array(
            'page_alias' => array(
                'titie' => 'page title',
                'route' => 'Module/controller/action',
                'view'  => 'view render',
                'urls'  => array(
                    '/page/url/with/[param1]/[param2:regex]',
                    //more urls for the page
                ),
                'allow_guess' => true, //optional
                'allow_search' => true, //optional
                'allow_cache' => true, //optional
                'use_ssl' => true, //optional
            ),
            // more pages
        );
    }
    
    /**
    * Parse the requested URL
    * @param pathInfo URL path info without suffix (see CHttpRequest->PathInfo)
    * @param rawPathInfo URL path info with suffix (see CHttpRequest->PathInfo)
    * return string the route for application to run or false if module does not handle requested URL
    */
    public function parseUrl($pathInfo, $rawPathInfo)
    {
        return false;
    }

    /**
    * Module metadata
    */
    /*
    Copy the following code to the derived module class to define its metadata
    public function getMetaData()
    {
        return array(
            'friendly_name' => '...',
            'description'   => '...',
            'is_system'     => false,
            'version'       => '1.0',
            'has_backend'   => 'n',
        );
    }
    */
    
    public function install()
    {
        $module = new Module();
        $module->attributes = $this->getMetaData();
        $module->name = str_replace('Module','',get_class($this));
        $module->enabled = true;
        $module->save();
    }
    
    public function uninstall()
    {
        
    }
    
    public function activate()
    {
        
    }
    
    public function deactivate()
    {
        
    }
}
?>