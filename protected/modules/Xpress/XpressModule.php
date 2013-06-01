<?php
class XpressModule extends XWebModule
{
    public function getMetaData()
    {
        return array(
            'friendly_name' => 'Yii Xpress',
            'description'   => 'Yii Xpress is a boilerplate to build web application upon Yii with best practices',
            'is_system'     => true,
            'version'       => '1.0',
            'has_backend'   => 'n',
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
//        'UserApi' => array(
//            'class' => 'Xpress.services.UserApi',
//            'methods'=> array(
//                'login'=>array(
//                    'title'=>'Login user',
                    // default is <Module><ServiceName><MethodId>Input
                    //'input'=>'path.to.input.model.class',
                    // default is <Module><ServiceName><MethodId>Output
                    //'output'=>'path.to.output.model.class',
//                ),
//                'changePassword'=>array(
//                    'title'=>'Change password',
//                ),
//            )
//        ),
        /** other controller config **/
    );
}
