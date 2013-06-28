<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
    'htmlOptions'=>array('class'=>'form-horizontal'), // comment this line to use long form
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

    <?php echo "<?php \$this->widget('Xpress.components.InfoBox',array('heading'=>'')); ?>"; ?>

    <?php echo '<?php if (! $model->IsNewRecord) echo $form->hiddenField($model, "'.$this->Model->TableSchema->primaryKey.'") ; ?>'."\n"; ?>
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
?>
	<div class="control-group">
		<?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column,array('class'=>'control-label'))."; ?>\n"; ?>
        <div class="controls">
		    <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		    <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
        </div>
	</div>

<?php
}
?>
	<div class="control-group buttons">
        <div class="controls">
		    <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary')); ?>\n"; ?>
        </div>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->