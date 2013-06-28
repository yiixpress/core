<?php
class WidgetCode extends CCodeModel
{
    public $model;
    public $controller;
    public $baseControllerClass;
    
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
        
        $path=$this->getControllerFile('View');
        $code=$this->render($templatePath.'/view.php');
        $this->files[]=new CCodeFile($path, $code);
        $this->files[]=new CCodeFile(
            $this->getViewPath('View').DIRECTORY_SEPARATOR.'form.php',
            $this->render($templatePath.'/view-form.php')
        );
        
        $path=$this->getControllerFile('List');
        $code=$this->render($templatePath.'/list.php');
        $this->files[]=new CCodeFile($path, $code);
        $this->files[]=new CCodeFile(
            $this->getViewPath('List').DIRECTORY_SEPARATOR.'form.php',
            $this->render($templatePath.'/list-form.php')
        );
        
        $path=$this->getControllerFile('Form');
        $code=$this->render($templatePath.'/form.php');
        $this->files[]=new CCodeFile($path, $code);
        $this->files[]=new CCodeFile(
            $this->getViewPath('Form').DIRECTORY_SEPARATOR.'form.php',
            $this->render($templatePath.'/form-form.php')
        );
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

    public function getControllerFile($type='View')
    {
        $module=$this->getModule();
        $id=$this->getControllerID();
        if(($pos=strrpos($id,'/'))!==false)
            $id[$pos+1]=strtoupper($id[$pos+1]);
        else
            $id[0]=strtoupper($id[0]);
        return $module->getControllerPath().'/'.$id.$type.'Controller.php';
    }

    public function getViewPath($type='View')
    {
        return $this->getModule()->getViewPath().'/'.$this->getControllerID().$type;
    }

    public function getControllerClass($type='View')
    {
        if(($pos=strrpos($this->controller,'/'))!==false)
            return ucfirst(substr($this->controller,$pos+1)).$type.'Controller';
        else
            return ucfirst($this->controller).$type.'Controller';
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
}
