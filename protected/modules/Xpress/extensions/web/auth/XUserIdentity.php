<?php
/**
* $Id: UserIdentity.php 2166 2009-11-12 07:20:12Z phong.quach $
*
* Class UserIdentity
*
* Authenticate user using database. The 'Users' table must have these three columns
* - Username
* - Password
* - Status
* By default, it assumes to use table 'users' in the database but you can choose
* other table by setting UserModel property. This is helpful in case of a site has
* member table and admin table separately
*
* @author Hung Nguyen, Flexica Solution
*/
class XUserIdentity extends CUserIdentity
{
    const ERROR_STATUS_INVALID = 3;
    const ERROR_NOT_ALLOWED = 4;

    protected $user;

    /**
    * Database authentication with checking of user status to support account activation
    * which use Status as Role
    */
    public function authenticate(){
        $user = user()->getUserModel();
        $criteria = new CDbCriteria;
        $criteria->condition = user()->UsernameField.' LIKE :username OR '.user()->EmailField.' LIKE :username';
        $criteria->params = array(':username' => $this->username);
        $this->user = $user->find($criteria);

        if (is_null($this->user)){
            errorHandler()->log(new XManagedError('Your username is invalid', self::ERROR_USERNAME_INVALID));
        }elseif($this->user->Attributes[user()->PasswordField] != md5($this->password)){
            errorHandler()->log(new XManagedError('Invalid username or password.', self::ERROR_PASSWORD_INVALID));
        }elseif(($errMsg = $this->user->validateStatus()) !== TRUE){
            errorHandler()->log(new XManagedError($errMsg, self::ERROR_STATUS_INVALID));
        }else{
            $this->errorCode = self::ERROR_NONE;
            foreach(user()->UserStatefulFields as $field)
                $this->setState($field, $this->user->Attributes[$field]);
        }

        return $this->user;
    }

    /**
    * Translate login error code into English message
    *
    * @param mixed $code
    * @return mixed
    */
    public function getErrorMessage($code){
        if (is_string($code)) return $code;

        switch($code){
            case self::ERROR_NONE:
                return '';
            case self::ERROR_USERNAME_INVALID:
                return 'Sorry but your username is not found.';
            case self::ERROR_PASSWORD_INVALID:
            case self::ERROR_UNKNOWN_IDENTITY:
                return 'Invalid username or password.';
            case self::ERROR_NOT_ALLOWED:
                return 'You do not have enough previlege to access the requested page.';
            default:
                return 'Sorry, you cannot login as some errors occur.';
        }
    }

    /**
    * Return User's Id instead of username
    *
    * CWebUser use this Id value to store as app()->user->id.
    * By overriding this function, the app()->user object is very similar to user object queried from database
    *
    */
    public function getId(){
        $id =  $this->user->PrimaryKey;
        if ($id)
            return $id;
        elseif ($this->user->tableSchema->primaryKey == null)
        {
            throw new CDbException($this->user->tableName() . ' does not have a primary key or system is unable to get the table schema data.');
        }
    }
}
?>
