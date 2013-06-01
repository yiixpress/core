<?php

class Xpress extends CApplicationComponent
{
    // URL and directory path to the assets
    public $AssetUrl;
    public $AssetFolder;

    public function init()
    {
        parent::init();

        if (Yii::app() instanceof CWebApplication) {
            //Publish assets
            $asset = dirname(__FILE__) . '/../assets';
            $this->AssetUrl = Yii::app()->assetManager->publish($asset, false, -1);
            $this->AssetFolder = Yii::app()->assetManager->getPublishedPath($asset);

            // turn security on
            if (Yii::app()->request->isAjaxRequest == false && APP_ID != 'diagnostic')
            {
                Yii::app()->request->enableCsrfValidation = true;
                Yii::app()->request->csrfTokenName = SITE_ID .'_'.APP_ID.'_CSRF';
            }
            Yii::app()->request->enableCookieValidation = true;

        }
    }
    
    /**
    * Load files and folders from config file
    */
    public function setInclude($paths)
    {           
        foreach($paths as $path)
        {
            if (strpos($path,'*') === false)
                Yii::import($path,true);
            else
                Yii::import($path);
        }
    }

}
?>
