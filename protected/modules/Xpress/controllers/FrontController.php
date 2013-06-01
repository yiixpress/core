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

class FrontController extends XController
{
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    public $pageId;
    public $pageRevision;
    public $templateId;
    public $templateRevision;

    public function init()
    {
        parent::init();
        Yii::app()->theme = SETTINGS_THEME;
        Yii::app()->layout = SETTINGS_DEFAULT_LAYOUT;
        //Set Session time
        //Get the session setting and check for empty
        $sessionSetting=SETTINGS_SESSION_TIME;
        if (!empty($sessionSetting)) {
            $sessions = eval('return ' . SETTINGS_SESSION_TIME . ';');
            $domain = $_SERVER['SERVER_NAME'];
            if (array_key_exists($domain, $sessions)) {
                Yii::app()->session->timeout = $sessions[$domain] * 60;
            }
        }

        //TODO: add your custom initialization code here
    }

    public function filters()
    {
        return array();
    }

    public function render($view, $data = null, $return = false)
    {
        $reuqestedPageUrl = null;
        if (property_exists(Yii::app()->urlManager,'RequestedPageUrl'))
            $reuqestedPageUrl = Yii::app()->urlManager->RequestedPageUrl;
        if ($reuqestedPageUrl !== null) {
            $content = $this->api('Cms.Page.renderPage', array('url' => $reuqestedPageUrl, 'content' => $data, 'host'=> null));
            if ($return)
                return $content;
            else
                echo $content;
        } else {
            return parent::render($view, $data, $return);
        }
    }
}