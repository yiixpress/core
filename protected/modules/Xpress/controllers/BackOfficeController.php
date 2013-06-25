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

class BackOfficeController extends XController
{
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();


	public function init(){

        parent::init();
        if (app()->theme == NULL)
            Yii::app()->theme = SETTINGS_BO_THEME;
    }

    public function filters(){
        $filters = array(
                'accessControl',
            );
        return $filters;
    }

    /**
    * Show Permission denied page
    */
    public function actionPermissionDenied() {
        // TODO: logout user, redirect to admin login form and the error should be dislayed in the form
        ErrorHandler::logError('Permission denied!<br />- You do not have enough privilege to access the page you requested or<br />- The requested page is accessible but a service on that page cannot be performed on your behalf.');
        Yii::app()->layout = 'permission';
        $this->render('PermissionDenied');
    }
}
?>