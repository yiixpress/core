<?php
/**
 * @author
 * @package
 * @subpackage
 */


/**
 * class BlogCategoryApi
 * @package
 * @subpackage
 */
class BlogCategoryApi extends ApiController
{
	/**
	 * Get a BlogCategory model given its ID
	 *
	 * @param int id BlogCategory ID
	 * @return BlogCategory
	 */
	public function actionGet($id)
	{
		$model = BlogCategory::model()->findByPk($id);
		if (!$model) {
			errorHandler()->log(Yii::t('Blog.Api', 'BlogCategory is not found.'));
			$this->result = NULL;
		} else {
			$this->result = $model;
		}
	}

	/**
	 * Save a BlogCategory model
	 *
	 * @param array $attributes Model attributes
	 * @return BlogCategory
	 */
	public function actionSave(array $attributes)
	{
		$input = new XInputFilter($attributes);
		$model = $input->getModel('BlogCategory');

		if (!$model->save()) {
			errorHandler()->log(new XModelManagedError($model, 0));
		}

		app()->XService->run('Xpress.NestedCategory.buildTree', array(
			'data'       => BlogCategory::model()->findAll(),
			'attributes' => array('name', 'alias')
		));

		return $this->result = $model;
	}


	public function actionDelete(array $ids)
	{
		$deleted = array();

		foreach ($ids as $id) {
			$model = BlogCategory::model()->findByPk($id);
			/**
			 * TODO: Check related data if this BlogCategory is deletable
			 * This can be done in onBeforeDelete or here or in hooks
			 *
			if (Related::model()->count("blogcategory_id = {$id}") > 0)
			{
			errorHandle()->log(new XManagedError(Yii::t('Blog.BlogCategory',"Cannot delete BlogCategory ID={$id} as it has related class data."));
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
		$this->result = $deleted;
		app()->XService->run('Xpress.NestedCategory.buildTree', array(
			'data'       => BlogCategory::model()->findAll(),
			'attributes' => array('name', 'alias')
		));

	}

	/**
	 * Update category status
	 *
	 * @param array $ids
	 * @param bool $value
	 */
	public function actionChangeStatus(array $ids, $value = false)
	{
		//TODO need revise
		if (count($ids) <= 0) {
			return;
		}

		$criteria = new CDbCriteria();
		$criteria->addInCondition('id', $ids);
		BlogCategory::model()->updateAll(array('status' => $value, 'last_update' => date('Y-m-d H:i:s')), $criteria);

		app()->XService->run('Xpress.NestedCategory.buildTree', array(
			'data'       => BlogCategory::model()->findAll(),
			'attributes' => array('name', 'alias')
		));

	}

	/**
	 * Reorder categories on the grid
	 * @param array $items
	 */
	public function actionReorder(array $items)
	{
		//TODO need revise
		$status = false;
		if (count($items)) {
			foreach ($items as $id => $parentId) {
				$orders = array();
				if ($parentId == 'root') {
					$orders   = array_keys($items, $parentId);
					$parentId = 0;
				} else {
					$parentId = (int)$parentId;
					$orders   = array_keys($items, $parentId);
				}
				$order = 0;
				if (count($orders)) {
					$order = (int)array_search($id, $orders);
				}
				$order++;
				BlogCategory::model()->updateByPk($id, array(
					'parent_id' => $parentId,
					'ordering'  => $order,
				));
			}
			$status = true;
		}
		$this->result = array('status' => $status);

		app()->XService->run('Xpress.NestedCategory.buildTree', array(
			'data'       => BlogCategory::model()->findAll(),
			'attributes' => array('name', 'alias')
		));

	}
}