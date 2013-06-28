<?php
/**
 * @package
 */

class AdminMainMenu extends CWidget{

	public function run()
	{
		$criteria            = new CDbCriteria();
		$criteria->condition = "enabled = TRUE AND has_back_end = 'y' ";
		$criteria->order     = 'ordering ASC';

		Yii::import('Xpress.models.Module');
		$modules               = Module::model()->findAll($criteria);

		$this->render('adminMainMenu',array(
			'modules' => $modules,
		));
	}
}