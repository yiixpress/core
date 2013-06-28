<?php

class BlogPostController extends BackOfficeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/main'
	 * //layouts is the layouts folder under theme's views or protected/views if no theme is set
	 */
	public $layout='//layouts/main';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->api('Blog/BlogPost/get', array('id'=>$id)),
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
            $model = $this->postModel('BlogPost');
            $result = $this->api('Blog.BlogPost.save', array('input' => $model));

            if (! errorHandler()->hasErrors())
            {
                $this->message = Yii::t('Xpress.Common','Item has been saved successfully.');
                $this->redirect($this->createUrl('update',array('id' => $model->id)));
            }
        } else {
            // show edit form
            if (($id = $this->get('id', 0)) > 0)
                $model = $this->api('Blog.BlogPost.get', array('id' => $id));
            if (!isset($model) || !$model instanceof BlogPost) {
                $model = new BlogPost();
            }
        }

        $this->render('update', array('model' => $model));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            if (($id = $this->get('id',null)) !== null) {
                $ids = is_numeric($id) ? array($id) : explode(',',$id);

                // delete one or multiple objects given the list of object IDs
                $result = $this->api('Blog.BlogPost.delete', array('ids' => $ids));
                if(!errorHandler()->hasErrors())
                {
                    // only redirect user to the admin page if it is not an AJAX request
                    if (!Yii::app()->request->isAjaxRequest)
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                    else
                        echo CJavaScript::encode(array('msg'=>Yii::t('Xpress.Common','Items are deleted successfully.')));
                }
                else
                {
                    // redirecting with error carried to the redirected page
                    if (!Yii::app()->request->isAjaxRequest)
                    {
                        BlogPost()->setFlashErrors(errorHander()->getErrors());
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                    }
                    else
                    {
                        echo implode("\n",errorHandler()->getErrorMessages());
                    }
                }
            } else {
                throw new CHttpException(400,Yii::t('Xpress.Common','Cannot delete item with the given ID.'));
            }
        } else {
            throw new CHttpException(400,Yii::t('Xpress.Common','Invalid request. Please do not repeat this request again.'));
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
        $model=new BlogPost('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['BlogPost']))
            $model->attributes=$_GET['BlogPost'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}
}
