<?php
/**
* @author
* @package
* @subpackage
*/


/**
* class BlogPostApi
* @package
* @subpackage
*/
class BlogPostApi extends ApiController
{    
    /**
    * Get a BlogPost model given its ID
    * 
    * @param int id BlogPost ID
    * @return BlogPost    */
    public function actionGet($id){
        $model = BlogPost::model()->findByPk($id);
        if (! $model)
        {
            errorHandler()->log(Yii::t('Blog.Api','BlogPost is not found.'));
            $this->result = null;
        }
        else
        {
            $this->result = $model;
        }
    }

    /**
    * Save a BlogPost model
    *
    * @param array $attributes Model attributes
    * @return BlogPost    */    
    public function actionSave(array $attributes) {
        $input = new XInputFilter($attributes);
        $model = $input->getModel('BlogPost');
        
        if (! $model->save())
            errorHandler()->log(new XModelManagedError($model,0));
            
        $this->result = $model;
    }


    public function actionDelete(array $ids) {
        $deleted = array();
        
        foreach($ids as $id) {
            $model = BlogPost::model()->findByPk($id);
            /**
            * TODO: Check related data if this BlogPost is deletable
            * This can be done in onBeforeDelete or here or in hooks
            *
            if (Related::model()->count("blogpost_id = {$id}") > 0)
            {
                errorHandle()->log(new XManagedError(Yii::t('Blog.BlogPost',"Cannot delete BlogPost ID={$id} as it has related class data."));
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