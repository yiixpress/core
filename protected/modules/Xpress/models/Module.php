<?php
require_once(dirname(__FILE__).'/base/ModuleBase.php');
class Module extends ModuleBase
{
    public $installed;
    
    public function afterFind()
    {
        $this->installed = true;
        return parent::afterFind();
    }
}