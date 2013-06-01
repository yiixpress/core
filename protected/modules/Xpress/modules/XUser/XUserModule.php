<?php

class XUserModule extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => 'User management',
            'description' => 'Provide basic user management features',
            'is_system' => true,
            'version' => '1.0',
            'has_backend' => 'y',
        );
    }


    /**
    * The controllerMap as defined by Yii is used to map the controller with its configuration.
    * Beside that, in Xpress it's use to define the service (Api controller) and its methods.
    * 
    * This way we have a single place in each module that define all services provide by the 
    * module. It will help to easily find out the available service and how to use.
    * 
    * You can also write document tool (if you don't use any document generator) to generate
    * API document from this map.
    * 
    * @var array
    */
    public $controllerMap = array(
        /** services **/
        'UserGroupApi' => array(
            'class' => 'XUser.services.UserGroupApi',
            'methods'=> array(
                'get'=>array(
                    'title'=>'Get user group',
                    'output'=>'XUser.models.UserGroup',
                ),
                'save'=>array(
                    'title'=>'Save user group',
                    'input'=>'XUser.models.UserGroup',
                    'output'=>'XUser.models.UserGroup'
                ),
                'delete'=>array(
                    'title'=>'Delete one or multiple user groups',
                ),
                'changeStatus'=>array(
                    'title' => 'Change status',
                ),
            )
        ),
        
        'AdminUserGroupApi' => array(
            'class' => 'XUser.services.AdminUserGroupApi',
            'methods'=> array(
                'get'=>array(
                    'title'=>'Get admin user group',
                    'output'=>'XUser.models.AdminUserGroup',
                ),
                'save'=>array(
                    'title'=>'Save admin user group',
                    'input'=>'XUser.models.AdminUserGroup',
                    'output'=>'XUser.models.AdminUserGroup'
                ),
                'delete'=>array(
                    'title'=>'Delete one or multiple admin user groups',
                ),
                'changeStatus'=>array(
                    'title' => 'Change status',
                ),
            )
        ),

        'UserApi' => array(
            'class' => 'XUser.services.UserApi',
            'methods'=> array(
                'get'=>array(
                    'title'=>'Get user',
                    'output'=>'XUser.models.User',
                ),
                'save'=>array(
                    'title'=>'Save user',
                    'input'=>'XUser.models.User',
                    'output'=>'XUser.models.User'
                ),
                'delete'=>array(
                    'title'=>'Delete one or multiple users',
                ),
                'beforeGroupDelete'=>array(
                    'title'=>'Handle event before user group is deleted'
                ),
                'signUp'=>array(
                    'title'=>'Create a new user following procedure of the signup process',
                    'input'=>'XUser.models.User',
                ),
                'sendResetPassword'=>array(
                    'title'=>'Send reset password',
                ),
                'sendActivationEmail'=>array(
                    'title' => 'Send activation email',
                ),
                'changeStatus'=>array(
                    'title' => 'Change status',
                ),
            )
        ),
        
        'AdminUserApi' => array(
            'class' => 'XUser.services.AdminUserApi',
            'methods'=> array(
                'get'=>array(
                    'title'=>'Get admin user',
                    'output'=>'XUser.models.AdminUser',
                ),
                'save'=>array(
                    'title'=>'Save admin user',
                    'input'=>'XUser.models.AdminUser',
                    'output'=>'XUser.models.AdminUser'
                ),
                'delete'=>array(
                    'title'=>'Delete one or multiple admin users',
                ),
                'beforeGroupDelete'=>array(
                    'title'=>'Handle event before admin user group is deleted'
                ),
                'changeStatus'=>array(
                    'title' => 'Change status',
                ),
            )
        ),
        
        /** other controller config **/
    );
    
    public function hookMap()
    {
        return array(
            'XUser.UserGroupApi'=>array(
                'delete'=>array(
                    'XUser.User.beforeGroupDelete',
//                    'XUser.User.afterGroupDelete'
                ),
            )
        );
    }

    public function getBackendMenuItems()
    {
        return array(
            array('title'=>'Users','url'=>'/XUser/admin/user'),
            array('title'=>'User groups','url'=>'/XUser/admin/userGroup'),
            array('title'=>'---','url'=>''),
            array('title'=>'Profile fields','url'=>'#'),
            array('title'=>'---','url'=>''),
            array('title'=>'Admin users','url'=>'/XUser/admin/adminUser'),
            array('title'=>'Admin groups','url'=>'/XUser/admin/adminUserGroup'),
            array('title'=>'---','url'=>''),
            array('title'=>'Permissions','url'=>'#'),
            array('title'=>'Roles','url'=>'#'),
        );
    }

}
