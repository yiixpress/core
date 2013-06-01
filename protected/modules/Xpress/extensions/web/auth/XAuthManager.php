<?php
/**
-------------------------
GNU GPL COPYRIGHT NOTICES
-------------------------
This file is part of FlexicaCMS.

FlexicaCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

FlexicaCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with FlexicaCMS.  If not, see <http://www.gnu.org/licenses/>.*/

/**
 * $Id$
 *
 * @author FlexicaCMS team <contact@flexicacms.com>
 * @link http://www.flexicacms.com/
 * @copyright Copyright &copy; 2009-2010 Gia Han Online Solutions Ltd.
 * @license http://www.flexicacms.com/license.html
 */
class XAuthManager extends CDbAuthManager
{
    const ROLE_ITEM_TYPE = 2;
    const RESOURCE_CLASS_ITEM_TYPE = 1;
    const RESOURCE_OBJECT_ITEMP_TYPE = 1;
    const ACTION_ITEM_TYPE = 0;
    const SERVICE_ITEM_TYPE = -1;

    const ROLE_GUESTS           = 'guests';
    const ROLE_USERS            = 'users';
    const ROLE_MANAGERS         = 'managers';
    const ROLE_ADMINISTRATORS   = 'administrators';
    
    private $itemWeightTable = 'AuthItemWeight';
    
    public function init() {
        parent::init();
        //Turn on error reporting for bizRule if debug mode
        $this->showErrors = YII_DEBUG;
        
        //Load default roles which are dynamic roles in the system
        $roleItems = $this->getAuthItems(self::ROLE_ITEM_TYPE);
        foreach($roleItems as $role)
            if (! empty($role->bizRule))
                $this->defaultRoles[] = $role->name;
    }
    
    /**
     * Performs access check for the specified user.
     * @param string the name of the operation that need access check
     * @param mixed the user ID. This should can be either an integer and a string representing
     * the unique identifier of a user. See {@link IWebUser::getId}.
     * @param array name-value pairs that would be passed to biz rules associated
     * with the tasks and roles assigned to the user.
     * @return boolean whether the operations can be performed by the user.
     */
    public function checkAccess($itemName,$userId,$params=array())
    {
        /**
        * As we don't want to let user change administrators permission in Back Office and
        * we assume that an Administrator has all the permissions in the system, we don't
        * check access for administrator user.
        */
        if ($this->getAuthAssignment(self::ROLE_ADMINISTRATORS, $userId) !== null)
            return TRUE;
        else
            return parent::checkAccess($itemName, $userId, $params);
    }
    
    /**
     * Returns the authorization items of the specific type and user.
     * @param integer the item type (0: operation, 1: task, 2: role). Defaults to null,
     * meaning returning all items regardless of their type.
     * @param mixed the user ID. Defaults to null, meaning returning all items even if
     * they are not assigned to a user.
     * @param string item name mask. Defaults to % to get all items. Mask is compared 
     * with item name using sql LIKE operator
     * @return array the authorization items of the specific type.
     */
    public function getAuthItems($type = null, $userId = null, $mask = '%') {
        if($type===null && $userId===null)
        {
            $sql="SELECT * FROM {$this->itemTable} WHERE name LIKE '{$mask}'";
            $command=$this->db->createCommand($sql);
        }
        else if($userId===null)
        {
            $sql="SELECT * FROM {$this->itemTable} WHERE type=:type AND name LIKE '{$mask}'";
            $command=$this->db->createCommand($sql);
            $command->bindValue(':type',$type);
        }
        else if($type===null)
        {
            $sql="SELECT name,type,description,t1.bizrule,t1.data
                FROM {$this->itemTable} t1, {$this->assignmentTable} t2
                WHERE name=itemname AND userid=:userid AND name LIKE '{$mask}'";
            $command=$this->db->createCommand($sql);
            $command->bindValue(':userid',$userId);
        }
        else
        {
            $sql="SELECT name,type,description,t1.bizrule,t1.data
                FROM {$this->itemTable} t1, {$this->assignmentTable} t2
                WHERE name=itemname AND type=:type AND userid=:userid AND name LIKE '{$mask}'";
            $command=$this->db->createCommand($sql);
            $command->bindValue(':type',$type);
            $command->bindValue(':userid',$userId);
        }
        $items=array();
        foreach($command->queryAll() as $row)
            $items[$row['name']]=new CAuthItem($this,$row['name'],$row['type'],$row['description'],$row['bizrule'],unserialize($row['data']));

        return $items;
    }
      

    /**
    * Create a role
    * 
    * @param mixed $name
    * @param mixed $description
    * @param mixed $bizRule
    * @param mixed $data
    */
    public function createRole($name, $description='',$bizRule=null,$data=null){
        return parent::createAuthItem($name, self::ROLE_ITEM_TYPE, $description, $bizRule, $data);
    }
    
    public function createResourceClass($name, $description='',$bizRule=null,$data=null){
        return parent::createAuthItem($name, self::RESOURCE_CLASS_ITEM_TYPE, $description, $bizRule, $data);
    }
    
    public function createResourceObject($class, $id, $description='',$bizRule=null,$data=null){
        return parent::createAuthItem("{$class}.{$id}", self::RESOURCE_OBJECT_ITEMP_TYPE, $description, $bizRule, $data);
    }
    
    public function createAction($name, $description='',$bizRule=null,$data=null){
        return parent::createAuthItem($name, self::ACTION_ITEM_TYPE, $description, $bizRule, $data);
    }
    
    public function createService($name, $description='',$bizRule=null,$data=null){
        return parent::createAuthItem($name, self::SERVICE_ITEM_TYPE, $description, $bizRule, $data);
    }

    /**
    * Revoce authorization assignment from a user
    *     
    * @param string $itemName if null, all user assignments are revoked
    * @param int $userId
    * @return boolean
    */
    public function revoke($itemName, $userId) {
        if ($itemName != null)
            return parent::revoke($itemName, $userId);
        else {
            $sql="DELETE FROM {$this->assignmentTable} WHERE userid=:userid";
            $command=$this->db->createCommand($sql);
            $command->bindValue(':userid',$userId);
            return $command->execute()>0;
        }
    }
    
    /**
    * Utility method to convert an url/route/path to
    * authentication item name in form or Url.Route.Path
    * @param string $route
    * @return string
    */
    public static function urlRoute2AuthItem($route){
        $steps = explode('/', trim($route, '/'));
        foreach($steps as &$step) $step = ucfirst($step);
        return implode('.', $steps);
    }
    
    /**
    * Returns the specified authorization items sorted by weights.
    * @param array the names of the authorization items to get.
    * @return array the authorization items.
    */
    public function getSortedAuthItems($names)
    {
        $items = array();

        if( $names!==array() )
        {
            $sql = "SELECT name,t1.type,description,t1.bizrule,t1.data,weight
                FROM {$this->itemTable} t1
                LEFT JOIN {$this->itemWeightTable} t2 ON name=itemname
                WHERE name IN ('".implode("','",$names)."')
                ORDER BY t1.type DESC, weight ASC";
            $command=$this->db->createCommand($sql);

            foreach($command->queryAll() as $row)
                $items[ $row['name'] ]=new CAuthItem($this, $row['name'], $row['type'], $row['description'], $row['bizrule'], unserialize($row['data']));
        }

        return $items;
    }

    /**
    * Updates the authorization item weights.
    * @param array the result returned from jui-sortable.
    */
    public function updateItemWeights($result)
    {
        foreach( $result as $weight=>$itemname )
        {
            // Check if the item already has a weight
            $sql = "SELECT COUNT(*) FROM {$this->itemWeightTable}
                WHERE itemname=:itemname";
            $command = $this->db->createCommand($sql);
            $command->bindValue(':itemname', $itemname);

            if( $command->queryScalar()>0 )
            {
                $sql = "UPDATE {$this->itemWeightTable}
                    SET weight=:weight
                    WHERE itemname=:itemname";
                $command = $this->db->createCommand($sql);
                $command->bindValue(':weight', $weight);
                $command->bindValue(':itemname', $itemname);
                $command->execute();
            }
            // Item does not have a weight, insert it
            else
            {
                if( ($item = $this->getAuthItem($itemname))!==null )
                {
                    $sql = "INSERT INTO {$this->itemWeightTable} (itemname, type, weight)
                        VALUES (:itemname, :type, :weight)";
                    $command = $this->db->createCommand($sql);
                    $command->bindValue(':itemname', $itemname);
                    $command->bindValue(':type', $item->getType());
                    $command->bindValue(':weight', $weight);
                    $command->execute();
                }
            }
        }
    }

}
?>