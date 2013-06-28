<?php
class LoginForm extends CFormModel
{
    public $login;
    public $password;
    public $remember;
    
    public function rules()
    {
        return array(
            array('login,password','required'),
            array('password','length', 'min' => 5),
            array('login,password','safe'),
        );
    }
}
?>
