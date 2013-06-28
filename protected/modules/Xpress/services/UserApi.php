<?php
/**
 * @author Hung Nguyen
 * @package Xpress
 * @subpackage User
 */

/**
 * @package Xpress
 * @subpackage User
 */
class UserApi extends ApiController
{
	public function actionLogin($email, $password, $remember = false)
	{
		Yii::import('Xpress.extensions.web.auth.XUserIdentity');
		$ui = new XUserIdentity($email, $password);
		$ui->authenticate();

		if ($ui->errorCode == XUserIdentity::ERROR_NONE) {
			if (Yii::app()->user->login($ui, $remember ? 15 * 24 * 60 * 60 : 0)) {
				user()->getUserModel()
					->updateByPk(user()->id, array('last_login' => date('Y-m-d h:i:s')));
				$this->result = true;
			}
		}
	}

}
