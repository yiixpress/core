<?php
/**
-------------------------
GNU GPL COPYRIGHT NOTICES
-------------------------
This file is part of FlexicaCMS.

FlexicaCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

FlexicaCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with FlexicaCMS.  If not, see <http://www.gnu.org/licenses/>.*/

/**
 * $Id$
 *
 * @author FlexicaCMS team <contact@flexicacms.com>
 * @link http://www.flexicacms.com/
 * @copyright Copyright &copy; 2009-2010 Gia Han Online Solutions Ltd.
 * @license http://www.flexicacms.com/license.html
 */


class XController extends CController
{
    /**
    * Current view that being renderred
    * 
    * @var mixed
    */
    public $CurrentViewFile;
    
    /**
    * Array of view data
    * 
    * @var array
    */
    public $data = array();
    
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();

    
    public function init(){
        parent::init();
        if (! Yii::app()->request->getIsAjaxRequest()) {
            Yii::app()->clientScript->registerCoreScript('jquery');
        }
        $this->module->loadSettings();
    }
    
    
  /**
    * Run an Api
    * 
    * @param string $apiId in format of module/controller/action
    */
    public function api($apiId, $params = array()){
        $xservice = Yii::app()->getComponent('XService');
        return $xservice->run($apiId, $params);
    }
    
    public function post($name, $default = null, $filter = ''){
        $input = new XInputFilter($_POST);
        return $input->getInput($name, $default, $filter);
    }
    
    /**
    * Retrieve a model object from post data. An object will be created
    * and initailized with attribute values in $_POST. Attributes not in
    * $_POST are retrieved from DB.
    * 
    * @param mixed $class
    * @param mixed $filter
    */
    public function postModel($class, $filter = array()) {
        if (!isset($_POST[$class])) return null;
        
        $input = new XInputFilter($_POST[$class]);
        return $input->getModel($class, $filter);
    }
    
    /**
    * Similar to postModel but allow to specify a different key of the array
    * in $_POST than $_POST[$class].
    * 
    * @param mixed $class
    * @param mixed $filter
    * @param mixed $key
    */
    public function postModelExt($class, $key, $filter = array()) {
        if (!isset($_POST[$key])) return null;
        
        $input = new XInputFilter($_POST[$key]);
        return $input->getModel($class, $filter);
    }
    
    public function get($name, $default = null, $filter = ''){
        $input = new XInputFilter($_GET);
        return $input->getInput($name, $default, $filter);
    }
    
    public function cookie($name, $default = null, $filter = ''){
        $input = new XInputFilter($_COOKIE);
        return $input->getInput($name, $default, $filter);
    }
    
    /**
    * A shortcut to set user flash message which is useful to display
    * request result to user if the view has an InfoBox widget.
    * 
    * @param mixed $msg
    */
    public function setMessage($msg)
    {
        user()->setFlash('message',$msg);
    }
    
    public function redirect($url,$terminate=true,$statusCode=302)
    {
        if (Yii::app()->request->isAjaxRequest) return;
        parent::redirect($url,$terminate,$statusCode);
    }
    
    public function renderFile($viewFile,$data=null,$return=false)
    {
        //Track the current view file so the InlineViewWidget knows which view it belong to
        $this->CurrentViewFile = $viewFile;
        return parent::renderFile($viewFile, $data, $return);
    }    
    
    
    /**
    * Define an array of options that guest users can access.
    * Derived controllers if define accessControll filter can 
    * override this method to provide guest users access to 
    * other feature of the site
    */
    public function getGuestAllowedActions() {
        return array(
            'login', 'forgotPassword', 'error'
        );
    }
    
    public function accessRules(){
        return array(
            array(
                'allow',
                'actions' => $this->getGuestAllowedActions(),
                'users' => array('*')
            ),
            array(
                'allow',
                //Use our RBAC to allow admin dynamicall grant/revoke access to Admin Panel (AP)
                //TODO: Yii will throw error HTTP 403 if user logged in and does not meet this rule
                //while we prefer log him out and send him back to BO login page
                'expression' => array($this, 'isActionAccessible'),
            ),
            array(
                'deny',
                //Finally, everyone is denied, event logged in users
                'users' => array('*')
            ),
        );
    }     
    
    /**
    * Get route for Auth item name
    * Subclassess can override this function in special cases (include query string...)
    * @return string route
    */
    public function getRouteForAuthItem()
    {
        $route = $this->getRoute();
        return Yii::app()->authManager->urlRoute2AuthItem($route);
    }
    
    /**
    * Check if an action is accessible by user
    * 
    * @param mixed $action
    */
    public function isActionAccessible(){
        if (Yii::app()->user->isGuest) return false;
    
        $authItemName = $this->getRouteForAuthItem();
        return Yii::app()->user->checkAccess($authItemName);            
    }
    
    /**
    * Return TRUE if a request ask for a BackEndController
    * otherwise return false;
    * @return bool
    */
    public function isBackEnd(){
        if (Yii::app()->controller instanceof BackOfficeController)
            return true;
        else
            return false;        
    }
    
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
    }
}
?>