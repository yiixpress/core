<?php echo "<?php\n"; ?>

class <?php echo $this->modelClass; ?>Service extends FServiceBase
{    
    /**
    * Get a <?php echo $this->modelClass; ?> model given its ID
    * 
    * @param int id <?php echo $this->modelClass; ?> ID
    * @return FServiceModel
    */
    public function get($params){
        $model = <?php echo $this->modelClass; ?>::model()->findByPk($this->getParam($params, 'id',0));
        if (! $model)
            $this->result->fail(ERROR_INVALID_DATA, Yii::t('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>','Invalid ID.'));
        $this->result->processed('model', $model);
        return $this->result;
    }
    
    public function save($params) {
        /**
        * @var CModel
        */
        $model = $this->getModel($params['<?php echo $this->modelClass; ?>'],'<?php echo $this->modelClass; ?>');
        $this->result->processed('model', $model);
        
        if (! $model->validate())
            $this->result->fail(ERROR_INVALID_DATA, Yii::t('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>', 'Submitted data is missing or invalid.'));
        elseif ($this->getParam($params, 'validateOnly',0) == TRUE)
            return $this->result;
        elseif (! $model->save())
            $this->result->fail(ERROR_HANDLING_DB, Yii::t('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>','Error while saving submitted data into database.'));
        
        return $this->result;
    }


    public function delete($params) {
        $ids = $this->getParam($params, 'ids', array());
        if ($ids == 0) {
            return $this->result->fail(ERROR_INVALID_DATA, Yii::t('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>','Invalid ID.'));
        }
      
        if (!is_array($ids)) $ids = array($ids);
        foreach($ids as $id) {
            $model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
            /**
            * TODO: Check related data if this <?php echo $this->modelClass; ?> is deletable
            * This can be done in onBeforeDelete or here or in extensions
            *
            if (Related::model()->count("<?php echo $this->modelClass; ?>Id = {$id}") > 0)
                $this->result->fail(ERROR_VIOLATING_BUSINESS_RULES, Yii::t('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>',"Cannot delete <?php echo $this->modelClass; ?> ID={$id} as it has related class data."));
            else
            */
                try {
                    $model->delete();
                } catch (CDbException $ex) {
                    $this->result->fail(ERROR_HANDLING_DB, $ex->getMessage());
                }
        }
        return $this->result;
    }
}