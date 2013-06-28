<?php
/**
* @author Hung Nguyen
* @package XPressAdmin
* @subpackage Controllers
*/

Yii::import('Xpress.controllers.BackOfficeController');

/**
* Authentication pages
* @package Xpress
* @subpackage Controllers
*/
class AuthController extends BackOfficeController
{
    /**
    * To be moved to authController
    */
    public function actionLogin()
    {
        if (!user()->isGuest)
        {
            errorHandler()->log(new XManagedError('Try to login while you are not a guest.',0));
            user()->logout();
        }
        $user = new LoginForm();

        if (Yii::app()->request->isPostRequest)
        {
            $user->attributes = $_POST['LoginForm'];
            $ret = $this->api('Xpress.User.login', array(
                'email' => $user->login,
                'password' => $user->password,
                'remember' => $user->remember,
            ));
            if ($ret === true)
            {
                $url = user()->returnUrl;
                if (empty($url))
                    $url = $this->createUrl('/Admin/default');
                $this->redirect($url);
            }
        }

        $this->render('login',array(
            'user' => $user
        ));
    }

    public function actionLogout()
    {
        user()->logout();
        $this->redirect($this->createUrl('/Admin/default'));
    }
}