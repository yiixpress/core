<?php

/**
 * This is the model class for table "tagrem_indition2_user_group".
 */
require_once(dirname(__FILE__).'/base/UserGroupBase.php');
class UserGroup extends UserGroupBase
{

    public function rules()
    {
        return CMap::mergeArray(parent::rules(),array(
            array('name','required'),
            // created/updated time
            array('last_update','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'update'),
            array('creation_datetime','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert')
        ));        
    }
}