<?php $contentType = str_replace('Controller', '', $this->controllerClass); ?>
<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?> implements ICmsContent
{
	public $info=array(
        'name'=>'<?php echo $contentType;?>',
        'description'=>'<?php echo $contentType;?> content',
        'path'=>'<?php echo $this->Module->Id; ?>/<?php $contentType[0]=strtolower($contentType[0]);echo $contentType;?>',
        'model'=>'<?php echo $this->Module->Id; ?>.models.<?php echo $this->modelClass; ?>',
    );
    
    public function getDefaultConfig()
    {
        return CMap::mergeArray(parent::getDefaultConfig(), array(
        ));
    }
    
    public function actionInstall()
    {
        $this->installContent();
    }
    
    public function actionUninstall()
    {
        $this->uninstallContent();
    }
    
    public function actionIndex()
    {
        $this->render('/<?php echo $contentType;?>Content/view',array(
            'model'=>FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.get', array('id'=>Yii::app()->request->getParam('id', 0)))->model,
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
            $_POST['validateOnly'] = ($this->post('ajax','') == '<?php echo $this->class2id($this->modelClass); ?>-form');
            $result = FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.save', $_POST);
            $model = $result->model; 

            if ($this->post('ajax','') == '<?php echo $this->class2id($this->modelClass); ?>-form'){
                echo $result->getActiveErrorMessages($result->model);
                Yii::app()->end();
            }   
            if (! $result->hasErrors())
            {
<?php if (empty($form->email) === false):
    $emails = array();
    if (strpos($form->email, ',') !== false)
    {
        $data = explode(',', $form->email);
    }
    else
    {
        $data = explode("\n", $form->email);
        if (count($data) <=0 )
        {
            $data = array($form->email);
        }
    }
    if (count($data))
    {
        $data = array_map('trim', $data);
        foreach ($data as $email)
        {
            $validator = new CEmailValidator;
            if ($validator->validateValue($email))
                $emails[$email] = $email;
        }
        $emails = array_values($emails);
    }
    if (count($emails)):
    $emails = 'array("'.implode('", "', $emails).'")';
?>
                $emails = <?php echo $emails;?>;
                foreach ($emails as $email) {
                    quickMail($email, $this->module->id.'.views.'.$this->id.'._view', array('data' => $model));
                }
<?php endif;?>
<?php endif;?>
<?php if (isset($form) && is_object($form)):?>
<?php if (empty($form->redirect) === false):?>
                $this->redirect('<?php echo $form->redirect;?>');
<?php else:?>
                $this->message = '<?php echo $form->success_message;?>';
                $this->redirect($this->createUrl('update',array('id' => $model->id)));
<?php endif;?>
<?php endif;?>
            }
        } else {
            // show edit form
            $id = $this->get('id', 0);
            if ($id == 0) {
                $model = new <?php echo $this->modelClass; ?>();
            } else {
                $model = FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.get', array('id' => $id))->model;
            }
        }
            
        $this->render('/<?php echo $contentType;?>Content/update', array('model' => $model));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            if (($id = $this->get('id',null)) !== null) {
                $ids = is_numeric($id) ? array($id) : explode(',',$id);
                
                // delete one or multiple objects given the list of object IDs
                $result = FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.delete', array('ids' => $ids));
                if ($result->hasErrors()) {
                    echo $result->getError('ErrorCode');
                } elseif(!Yii::app()->request->isAjaxRequest) {
                    // only redirect user to the admin page if it is not an AJAX request
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                }
            } else {
                throw new CHttpException(400,Yii::t('Xpress','Cannot delete item with the given ID.'));
            }
        } else {
            throw new CHttpException(400,Yii::t('Xpress','Invalid request. Please do not repeat this request again.'));
        }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $model=new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['<?php echo $this->modelClass; ?>']))
            $model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

        $this->render('/<?php echo $contentType;?>Content/admin',array(
            'model'=>$model,
        ));
	}
}
