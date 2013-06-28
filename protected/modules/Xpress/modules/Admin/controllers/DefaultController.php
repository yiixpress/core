<?php
/**
* @author Hung Nguyen
* @package XPressAdmin
* @subpackage Controllers
*/

Yii::import('Xpress.controllers.BackOfficeController');

/**
* Deafult Xpress Admin controller
*/
class DefaultController extends BackOfficeController
{
	public function actionIndex()
	{
        $criteria = new CDbCriteria();
        $criteria->condition = "enabled = TRUE AND has_back_end = 'y' ";
        $criteria->order = 'ordering ASC';

        Yii::import('Xpress.models.Module');
        $modules = Module::model()->findAll($criteria);

		$this->render('index', $modules);
	}

    public function actionError()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $error = errorHandler()->getError();
            echo $error['message'];
        }
        else
            $this->render('error');
    }
}