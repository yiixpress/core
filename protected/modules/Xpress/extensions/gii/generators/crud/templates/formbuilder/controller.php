<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            <?php if ($this->form->captcha):?>
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            <?php endif; ?>
        );
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>FSM::run('<?php echo $this->Module->Id; ?>.<?php echo $this->modelClass; ?>.get', array('id'=>$id))->model,
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
<?php if (empty($this->form->email) === false):
    $emails = array();
    if (strpos($this->form->email, ',') !== false)
    {
        $data = explode(',', $this->form->email);
    }
    else
    {
        $data = explode("\n", $this->form->email);
        if (count($data) <=0 )
        {
            $data = array($this->form->email);
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
<?php if (empty($this->form->redirect) === false):?>
                $this->redirect('<?php echo $this->form->redirect;?>');
<?php else:?>
                $this->message = '<?php echo $this->form->success_message;?>';
                $this->redirect($this->createUrl('update',array('id' => $model->id)));
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
        $model=new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['<?php echo $this->modelClass; ?>']))
            $model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}
}
