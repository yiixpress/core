<?php

/**
 * This is the model class for table "{{blog_category}}".
 */
require_once(dirname(__FILE__) . '/base/BlogCategoryBase.php');
class BlogCategory extends BlogCategoryBase
{
	public function beforeSave()
	{
		if (!is_integer($this->parent_id)) {
			$model = $this->findByAttributes(array('alias' => $this->parent_id));
			if ($model) {
				$this->parent_id = $model->id;
			}
		}

		return parent::beforeSave();
	}
}