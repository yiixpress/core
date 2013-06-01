<?php
/**
* $Id$
* @author Hung Nguyen, Flexica Solutions
* Class ApplicationUser
*/
class XWebUser extends CWebUser
{
    protected $userClass = 'Xpress.models.XUser';
    
    protected $requireSSL = false;
    
    /**
    * Field used as 'username' when login
    *
    * @var string
    */
    public $UsernameField = 'username';
    public $EmailField = 'email';

    /**
    * Field used as password when login
    *
    * @var string
    */
    public $PasswordField = 'password';

    /**
    * Field used for user's status
    *
    * @var string
    */
    public $StatusField = 'status';

    /**
    * Fields to be stored in session, should not include id field as it's stored by default
    *
    * @var array
    */
    public $UserStatefulFields = array('email', 'last_login');
    public $secret = 'r&oR&O2QW&DEXzxhVSHjq089T6Pu^D!2';

    /**
    * The user model attached with application user object
    *
    * @var XUser
    */
    protected $model = null;
    
    public function __get($name)
    {
        if($this->hasState($name))
            return $this->getState($name);
        elseif (in_array($name, $this->UserStatefulFields))
        {
            $user = $this->getUserModel();
            if ($user)
                return $user->$name;
            else
                return null;
        }
        else
            return parent::__get($name);
    }

    public function getUserClass()
    {
        return $this->userClass;
    }

    /**
    * Set Yii path to the ActiveRecord class used for application's users
    *
    * @param string $classPath
    */
    public function setUserClass($classPath)
    {
        $this->userClass = $classPath;
    }

    /**
    * Get the User ActiveRecord object attached with application's user
    * @return XUser
    */
    public function getUserModel($reload = false)
    {
        if (!$this->model || $reload)
        {
            $this->model = Yii::createComponent($this->userClass);

            if ($this->id)
                $this->model = $this->model->findByPk($this->id);
            if (!$this->model)
                $this->model = Yii::createComponent($this->userClass);
        }
        return $this->model;
    }

    public $UserInvalidStatuses = array(
         0 => 'You have to activate your account',
        -1 => 'You are not allowed to login. Please contact administrator.',
    );
    
    public function getRequireSSL()
    {
        return $this->requireSSL;
    }
    
    public function setRequireSSL($value)
    {
        $this->requireSSL = $value;
    }

    public function checkToken($data)
    {
        $token = Yii::app()->request->getQuery('token', '');
        return $token === md5($this->secret.$data);
    }

    public function getToken($data)
    {
        return md5($this->secret.$data);
    }
}
?>