<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'module-form',
    'htmlOptions'=>array('class'=>'form-horizontal'), // comment this line to use long form
	'enableAjaxValidation'=>false,
)); ?>

    <?php $this->widget('Xpress.components.InfoBox',array('heading'=>'')); ?>
    <?php if (! $model->IsNewRecord) echo $form->hiddenField($model, "id") ; ?>
	<div class="control-group">
		<?php echo $form->labelEx($model,'name',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
		    <?php echo $form->error($model,'name'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'friendly_name',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'friendly_name',array('size'=>60,'maxlength'=>255)); ?>
		    <?php echo $form->error($model,'friendly_name'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'description',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
		    <?php echo $form->error($model,'description'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'enabled',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->checkBox($model,'enabled'); ?>
		    <?php echo $form->error($model,'enabled'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'version',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'version',array('size'=>32,'maxlength'=>32)); ?>
		    <?php echo $form->error($model,'version'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'has_back_end',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'has_back_end',array('size'=>1,'maxlength'=>1)); ?>
		    <?php echo $form->error($model,'has_back_end'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'ordering',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'ordering'); ?>
		    <?php echo $form->error($model,'ordering'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'icon',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'icon',array('size'=>60,'maxlength'=>255)); ?>
		    <?php echo $form->error($model,'icon'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'is_system',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->checkBox($model,'is_system'); ?>
		    <?php echo $form->error($model,'is_system'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'path',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'path',array('size'=>60,'maxlength'=>500)); ?>
		    <?php echo $form->error($model,'path'); ?>
        </div>
	</div>

	<div class="control-group buttons">
        <div class="controls">
		    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary')); ?>
        </div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->