<?php

class BlogCategoryController extends BackOfficeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/main'
	 * //layouts is the layouts folder under theme's views or protected/views if no theme is set
	 */
	public $layout = '//layouts/main';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->api('Blog/BlogCategory/get', array('id' => $id)),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->actionUpdate();
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		if (Yii::app()->request->IsPostRequest) {
			// save posted data
			$model  = $this->postModel('BlogCategory');
			$result = $this->api('Blog.BlogCategory.save', array('input' => $model));

			if (!errorHandler()->hasErrors()) {
				$this->message = Yii::t('Xpress.Common', 'Item has been saved successfully.');
				$this->redirect($this->createUrl('update', array('id' => $model->id)));
			}
		} else {
			// show edit form
			if (($id = $this->get('id', 0)) > 0) {
				$model = $this->api('Blog.BlogCategory.get', array('id' => $id));
			}
			if (!isset($model) || !$model instanceof BlogCategory) {
				$model = new BlogCategory();
			}
		}

		$categories = $this->api('Xpress.NestedCategory.findTree', array(
			'className' => 'BlogCategory',
		));


		$this->render('update', array(
			'model'      => $model,
			'categories' => $categories,
		));
	}

	public function actionUpdateStatus($id)
	{
		$model = BlogCategory::model()->findByPk($id);
		if ($model) {
			$model->status = 1 - $model->status;
			$model->save();
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if (($id = $this->get('id', NULL)) !== NULL) {
				$ids = is_numeric($id) ? array($id) : explode(',', $id);

				// delete one or multiple objects given the list of object IDs
				$result = $this->api('Blog.BlogCategory.delete', array('ids' => $ids));
				if (!errorHandler()->hasErrors()) {
					// only redirect user to the admin page if it is not an AJAX request
					if (!Yii::app()->request->isAjaxRequest) {
						$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
					} else {
						echo CJavaScript::encode(array('msg' => Yii::t('Xpress.Common', 'Items are deleted successfully.')));
					}
				} else {
					// redirecting with error carried to the redirected page
					if (!Yii::app()->request->isAjaxRequest) {
						BlogCategory()->setFlashErrors(errorHander()->getErrors());
						$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
					} else {
						echo implode("\n", errorHandler()->getErrorMessages());
					}
				}
			} else {
				throw new CHttpException(400, Yii::t('Xpress.Common', 'Cannot delete item with the given ID.'));
			}
		} else {
			throw new CHttpException(400, Yii::t('Xpress.Common', 'Invalid request. Please do not repeat this request again.'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->actionAdmin();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$criteria        = new CDbCriteria();
		$criteria->order = 'ordering';
		$criteria->compare('parent_id', 0);

		$categories = $this->api('Xpress.NestedCategory.findTree', array(
			'className' => 'BlogCategory'
		));

		$this->render('admin', array(
			'models'     => BlogCategory::model()->findAll($criteria),
			'categories' => $categories,
		));
	}

	protected function renderNested($viewFile, $models)
	{
		foreach ($models as $index => $model) {
			$childrenCount = 0;
			$criteria      = new CDbCriteria;
			$criteria->compare('parent_id', $model->id);
			$criteria->order = 'ordering';
			$children        = BlogCategory::model()->findAll($criteria);
			if (is_array($children)) {
				$childrenCount = count($children);
			}
			echo '<li class="sortable" id="items-' . $model->id . '">';
			Yii::app()->controller->renderFile($viewFile, array('class' => 'odd', 'model' => $model, 'hasChild' => (boolean)$childrenCount));

			if ($childrenCount) {
				echo '<ul>';
				$this->renderNested($viewFile, $children);
				echo '</ul>';
			}
			echo '</li>';
		}
	}
}
