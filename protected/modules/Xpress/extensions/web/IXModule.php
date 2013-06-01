<?php
/**
* @author Hung Nguyen
* @package Xpress
*/

/**
* Xpress module interface
* 
* @package Xpress
*/
interface IXModule
{
    public function getMetaData();
    public function install();
    public function uninstall();
    public function activate();
    public function deactivate();
}
?>
