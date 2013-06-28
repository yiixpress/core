<?php
/**
* @author
* @package
* @subpackage
*/


/**
* class ModuleApi
* @package
* @subpackage
*/
class ModuleApi extends ApiController
{    
    /**
    * Get a Module model given its ID
    * 
    * @param int id Module ID
    * @return Module    */
    public function actionGet($id){
        $model = Module::model()->findByPk($id);
        if (! $model)
        {
            errorHandler()->log(Yii::t('Admin.Api','Module is not found.'));
            $this->result = null;
        }
        else
        {
            $this->result = $model;
        }
    }

    /**
    * Save a Module model
    *
    * @param array $attributes Model attributes
    * @return Module    */    
    public function actionSave(array $attributes) {
        $input = new XInputFilter($attributes);
        $model = $input->getModel('Module');
        
        if (! $model->save())
            errorHandler()->log(new XModelManagedError($model,0));
            
        $this->result = $model;
    }


    public function actionDelete(array $ids) {
        $deleted = array();
        
        foreach($ids as $id) {
            $model = Module::model()->findByPk($id);
            /**
            * TODO: Check related data if this Module is deletable
            * This can be done in onBeforeDelete or here or in hooks
            *
            if (Related::model()->count("module_id = {$id}") > 0)
            {
                errorHandle()->log(new XManagedError(Yii::t('Admin.Module',"Cannot delete Module ID={$id} as it has related class data."));
            }
            else
            */
                try {
                    $deleted[] = $model->PrimaryKey;
                    $model->delete();
                } catch (CException $ex) {
                    array_pop($deleted);
                    errorHandler()->log(new XManagedError($ex->getMessage(), $ex->getCode()));
                }
        }
        return $this->result = $deleted;
    }

}