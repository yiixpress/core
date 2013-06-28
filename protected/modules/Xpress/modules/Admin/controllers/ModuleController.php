<?php

class ModuleController extends BackOfficeController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

    public function actionEnable($id)
    {
        $module = Module::model()->findByPk($id);
        if ($module)
        {
            if ($module->is_system)
            {
                throw new XManagedError('Cannot disable system module.',2);
                return;
            }
            $module->enabled = $module->enabled ? false : true;
            if (!$module->save())
                echo CHtml::errorSummary($module);
            else
                $this->api('Xpress.Settings.generateModuleConfig');
        }
        else
        {
            throw new XManagedError('Module is not found or not installed.',1);
        }
    }

    public function actionInstall($id)
    {
        if ($id > 0)
        {
            $module = Module::model()->findByPk($id);
            if ($module)
            {
                if ($module->is_system)
                {
                    throw new XManagedError('Cannot uninstall system module.',2);
                    return;
                }
                app()->getModule($module->name)->uninstall();
                $this->api('Xpress.Settings.generateModuleConfig');
            }
            else
            {
                throw new XManagedError('Module is not found or not installed.',1);
            }
        }
        else
        {
            // insall module
            $detectedModules = $this->api('Xpress.Settings.detectModules');
            if (isset($detectedModules[$id]))
            {
                $path = $detectedModules[$id];
                $class = $id.'Module';
                include_once(Yii::getPathOfAlias($path).".php");
                $module = new $class($id,null);
                $module->install();
                $this->api('Xpress.Settings.generateModuleConfig');
            }
            else
            {
                throw new XManagedError("Module {$id} is not detectable.",1);
            }
        }
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->api('Admin/Module/get', array('id'=>$id)),
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
            $model = $this->postModel('Module');
            $result = $this->api('Admin.Module.save', array('input' => $model));

            if (! errorHandler()->hasErrors())
            {
                $this->message = Yii::t('Xpress.Common','Item has been saved successfully.');
                $this->redirect($this->createUrl('update',array('id' => $model->id)));
            }
        } else {
            // show edit form
            if (($id = $this->get('id', 0)) > 0)
                $model = $this->api('Admin.Module.get', array('id' => $id));
            if (!isset($model) || !$model instanceof Module) {
                $model = new Module();
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
                $result = $this->api('Admin.Module.delete', array('ids' => $ids));
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
                        Module()->setFlashErrors(errorHander()->getErrors());
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
        // Regenerate the module config file
        $this->api('Xpress.Settings.generateModuleConfig');

        // Detect modules in the code base, find new modules
        $detectedModules = $this->api('Xpress.Settings.detectModules');
        $modules = Module::model()->findAll(new CDbCriteria(array('index' => 'name')));
        $newModules = array();
        foreach($detectedModules as $id => $path)
            if (!isset($modules[$id]))
            {
                include_once(Yii::getPathOfAlias($path).".php");
                $new = new Module();
                $new->id = $id;
                $class = $id.'Module';
                $class = new $class($id, null);

                try
                {
                    $new->attributes = $class->MetaData;
                }
                catch(Exception $ex)
                {
                    $new->name = $new->friendly_name = $id;
                }
                $newModules[] = $new;
            }

        // Installed modules
        $model=new Module('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Module']))
            $model->attributes=$_GET['Module'];

        $this->render('admin',array(
            'model'=>$model,
            'newModules'=>$newModules // view will merge new and installed modules before rendering the module grid
        ));
	}
}
