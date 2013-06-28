<?php
class LayoutCode extends CCodeModel
{
    public $form;
    public $model;
    public $controller;
    public $baseControllerClass='WidgetLayoutBase';
    
    private $_modelClass;
    private $_table;
 
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('model, controller', 'filter', 'filter'=>'trim'),
            array('model, controller, baseControllerClass', 'required'),
            array('model', 'match', 'pattern'=>'/^\w+[\w+\\.]*$/', 'message'=>'{attribute} should only contain word characters and dots.'),
            array('controller', 'filter', 'filter'=>'trim'),
            array('controller, baseControllerClass', 'required'),
            array('controller', 'match', 'pattern'=>'/^\w+[\w+\\/]*$/', 'message'=>'{attribute} should only contain word characters and slashes.'),
            array('baseControllerClass', 'match', 'pattern'=>'/^[a-zA-Z_]\w*$/', 'message'=>'{attribute} should only contain word characters.'),
            array('model', 'validateModel'),
        ));
    }

    public function validateModel($attribute,$params)
    {
        if($this->hasErrors('model'))
            return;
        $class=@Yii::import($this->model,true);
        if(!is_string($class) || !$this->classExists($class))
            $this->addError('model', "Class '{$this->model}' does not exist or has syntax error.");
        else if(!is_subclass_of($class,'CActiveRecord'))
            $this->addError('model', "'{$this->model}' must extend from CActiveRecord.");
        else
        {
            $table=CActiveRecord::model($class)->tableSchema;
            if($table->primaryKey===null)
                $this->addError('model',"Table '{$table->name}' does not have a primary key.");
            else if(is_array($table->primaryKey))
                $this->addError('model',"Table '{$table->name}' has a composite primary key which is not supported by crud generator.");
            else
            {
                $this->_modelClass=$class;
                $this->_table=$table;
            }
        }
    }
 
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'model'=>'Model Class',
            'controller'=>'Widget ID',
            'baseControllerClass'=>'Base Content Type Class',
        ));
    }
 
    public function prepare()
    {
        $templatePath = $this->templatepath;
        
        $path=$this->getControllerFile();
        $code=$this->render($templatePath.'/layout.php');
        $this->files[]=new CCodeFile($path, $code);
        
        // views
        $files=scandir($templatePath);
        foreach($files as $file)
        {
            if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php' && $file!=='layout.php')
            {
                $this->files[]=new CCodeFile(
                    $this->getViewPath().DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$file,
                    $this->render($templatePath.'/'.$file)
                );
            }
        }
    }

    public function getModule()
    {
        if(($pos=strpos($this->controller,'/'))!==false)
        {
            $id=substr($this->controller,0,$pos);
            if(($module=Yii::app()->getModule($id))!==null)
                return $module;
        }
        return Yii::app();
    }

    public function getControllerID()
    {
        if($this->getModule()!==Yii::app())
            $id=substr($this->controller,strpos($this->controller,'/')+1);
        else
            $id=$this->controller;
        if(($pos=strrpos($id,'/'))!==false)
            $id[$pos+1]=strtolower($id[$pos+1]);
        else
            $id[0]=strtolower($id[0]);
        return $id;
    }

    public function getControllerFile()
    {
        $module=$this->getModule();
        $id=$this->getControllerID();
        if(($pos=strrpos($id,'/'))!==false)
            $id[$pos+1]=strtoupper($id[$pos+1]);
        else
            $id[0]=strtoupper($id[0]);
        $id = substr(strrchr($id,'/'), 1);
        return $module->getBasePath().'/layouts/'.$id.'DefaultLayout.php';
    }

    public function getViewPath()
    {
        return $this->getModule()->getViewPath().'/'.$this->getControllerID();
    }

    public function getControllerClass()
    {
        if(($pos=strrpos($this->controller,'/'))!==false)
            return ucfirst(substr($this->controller,$pos+1)).'DefaultLayout';
        else
            return ucfirst($this->controller).'DefaultLayout';
    }

    public function getModelClass()
    {
        return $this->_modelClass;
    }
    
    public function getModel()
    {
        return new $this->_modelClass;
    }

    public function getTableSchema()
    {
        return $this->_table;
    }

    public function generateActiveLabel($modelClass,$column)
    {
        return "\$form->labelEx(\$model,'{$column->name}')";
    }

    public function generateActiveField($modelClass,$column)
    {
        if($column->type==='boolean')
            return "\$form->checkBox(\$model,'{$column->name}')";
        else if(stripos($column->dbType,'text')!==false)
            return "\$form->textArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
        else
        {
            if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
                $inputField='passwordField';
            else
                $inputField='textField';

            if($column->type!=='string' || $column->size===null)
                return "\$form->{$inputField}(\$model,'{$column->name}')";
            else
            {
                if(($size=$maxLength=$column->size)>60)
                    $size=60;
                return "\$form->{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
            }
        }
    }
    
    /**
    * Generate input for a form element
    * 
    * @param FormElement $element
    */
    public function generateInput($element) {
        if (method_exists($this, 'display_'.$element->type))
            return call_user_func(array($this,'display_'.$element->type), $element);
        if (isset($this->tableSchema->columns[$element->column_name]))
        {
            $column = $this->tableSchema->columns[$element->column_name];
            return '
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        <?php echo '.$this->generateActiveField($this->modelClass,$column).'; ?>        
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>'."\n"."\n";
        }
        elseif (is_array($this->tableSchema->columns) && count($this->tableSchema->columns))
        {
            $html = '';
            foreach ($this->tableSchema->columns as $column)
            {
                if (stripos($column->name, $element->column_name.'_') === 0)
                {
                    $html .= '
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        <?php echo '.$this->generateActiveField($this->modelClass,$column).'; ?>
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>'."\n"."\n";
                }
            }
            return $html;
        }
        return '';
    }
    
    public function display_text($element){
        $html = '';
        $column = $this->tableSchema->getColumn($element->column_name);
        if (is_object($column))
        {
            $html = 
'
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        <?php echo '.$this->generateActiveField($this->modelClass,$column).'; ?>
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>
';
        }
    
        return $html;
    }
    
    public function display_textarea($element) {
        return $this->display_text($element);
    }

    public function display_url($element) {
        return $this->display_text($element);
    }

    public function display_email($element) {
        return $this->display_text($element);
    }
    
    public function display_checkbox($element) {
        if (is_array($this->tableSchema->columns) && count($this->tableSchema->columns))
        {
            $column = $this->tableSchema->columns[$element->column_name];
            $html = '';
             switch ($element->lookup_type)
             {
                 case 'lookup':
                    $voc = Vocabulary::model()->findByPk($element->lookup_value);
                    $module = is_object($voc) ? $voc->module : '';
                    $html = '<?php echo TaxonomyHelper::activeCheckBoxList($model,\''.$column->name.'\', '.$element->lookup_value.', "'.$module.'"); ?>';
                    break;
             }
            
            $html = '
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        '.$html.'
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>'."\n"."\n";
            return $html;
        }
        return '';
    }
    
    public function display_select($element) {
        if (isset($this->tableSchema->columns[$element->column_name]))
        {
            $column = $this->tableSchema->columns[$element->column_name];
            $html = '';
             switch ($element->lookup_type)
             {
                 case 'lookup':
                    $voc = Vocabulary::model()->findByPk($element->lookup_value);
                    $module = is_object($voc) ? $voc->module : '';
                    $html = '<?php echo TaxonomyHelper::activeDropDownList($model,\''.$column->name.'\', '.$element->lookup_value.', "'.$module.'"); ?>';
                    break;
             }
            
            return '
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        '.$html.'
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>'."\n"."\n";
        }
        return '';
    }
    
    public function display_radio($element) {
         $column = $this->tableSchema->getColumn($element->column_name);
         $html = '';
         switch ($element->lookup_type)
         {
             case 'lookup':
                $voc = Vocabulary::model()->findByPk($element->lookup_value);
                $module = is_object($voc) ? $voc->module : '';
                $html = '<?php echo TaxonomyHelper::activeRadioButtonList($model,\''.$column->name.'\', '.$element->lookup_value.', "'.$module.'"); ?>';
                break;
         }
        $html = 
'
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        '.$html.'
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>
';
    
        return $html;       
    }
    
    public function display_file($element)
    {
        $column = $this->tableSchema->getColumn($element->column_name);
        return '
    <div class="row">
        <?php echo '.$this->generateActiveLabel($this->modelClass,$column).'; ?>
        <?php $this->widget("Cms.extensions.Plupload.PluploadWidget", array("model"=>$model,"attribute"=>"'.$column->name.'","targetDir"=>Yii::getPathOfAlias("site")."/../uploads","filters"=>array(array("extensions"=>"'.$element->default_value.'"))));?>
        '.(empty($element->guidelines) === false ? '<p class="hint">'.CHtml::encode($element->guidelines).'</p>' : '').'
        <?php echo $form->error($model,\''.$column->name.'\'); ?>
    </div>
';
    }
}
