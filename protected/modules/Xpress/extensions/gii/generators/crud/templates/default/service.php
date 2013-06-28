<?php echo "<?php\n"; ?>
/**
* @author
* @package
* @subpackage
*/


/**
* class <?php echo $this->modelClass; ?>Api
* @package
* @subpackage
*/
class <?php echo $this->modelClass; ?>Api extends ApiController
{    
    /**
    * Get a <?php echo $this->modelClass; ?> model given its ID
    * 
    * @param int id <?php echo $this->modelClass; ?> ID
    * @return <?php echo $this->modelClass; ?>
    */
    public function actionGet($id){
        $model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
        if (! $model)
        {
            errorHandler()->log(Yii::t('<?php echo $this->Module->Id; ?>.Api','<?php echo $this->modelClass; ?> is not found.'));
            $this->result = null;
        }
        else
        {
            $this->result = $model;
        }
    }

    /**
    * Save a <?php echo $this->modelClass; ?> model
    *
    * @param array $attributes Model attributes
    * @return <?php echo $this->modelClass; ?>
    */    
    public function actionSave(array $attributes) {
        $input = new XInputFilter($attributes);
        $model = $input->getModel('<?php echo $this->modelClass; ?>');
        
        if (! $model->save())
            errorHandler()->log(new XModelManagedError($model,0));
            
        $this->result = $model;
    }


    public function actionDelete(array $ids) {
        $deleted = array();
        
        foreach($ids as $id) {
            $model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
            /**
            * TODO: Check related data if this <?php echo $this->modelClass; ?> is deletable
            * This can be done in onBeforeDelete or here or in hooks
            *
            if (Related::model()->count("<?php echo strtolower($this->modelClass); ?>_id = {$id}") > 0)
            {
                errorHandle()->log(new XManagedError(Yii::t('<?php echo $this->Module->Id?>.<?php echo $this->modelClass; ?>',"Cannot delete <?php echo $this->modelClass; ?> ID={$id} as it has related class data."));
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
        $thsi->result = $deleted;
    }
}