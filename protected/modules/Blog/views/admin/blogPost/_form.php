<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'blog-post-form',
    'htmlOptions'=>array('class'=>'form-horizontal'), // comment this line to use long form
	'enableAjaxValidation'=>false,
)); ?>

    <?php $this->widget('Xpress.components.InfoBox',array('heading'=>'')); ?>
    <?php if (! $model->IsNewRecord) echo $form->hiddenField($model, "id") ; ?>
	<div class="control-group">
		<?php echo $form->labelEx($model,'title',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'title',array('rows'=>6, 'cols'=>50)); ?>
		    <?php echo $form->error($model,'title'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'alias',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'alias',array('rows'=>6, 'cols'=>50)); ?>
		    <?php echo $form->error($model,'alias'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'content',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->ckEditorRow($model, 'content', array('options' => array(
			    'fullpage' => 'js:true',
			    'width' => '640',
			    'resize_maxWidth' => '640',
			    'resize_minWidth' => '320',
			    'toolbarGroups' => "js:[
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
	{ name: 'links' },
	{ name: 'insert' },
//	{ name: 'forms' },
	{ name: 'tools' },
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
	{ name: 'others' },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
	{ name: 'styles' },
	{ name: 'colors' },
	{ name: 'about' }
]",
			    //'toolbar' => 'Basic',
		    ))); ?>
		    <?php echo $form->error($model,'content'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'revision_log',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'revision_log',array('size'=>60,'maxlength'=>512)); ?>
		    <?php echo $form->error($model,'revision_log'); ?>
        </div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'status',array (  'class' => 'control-label',)); ?>
        <div class="controls">
		    <?php echo $form->textField($model,'status'); ?>
		    <?php echo $form->error($model,'status'); ?>
        </div>
	</div>

	<div class="control-group buttons">
        <div class="controls">
		    <?php
		    echo CHtml::htmlButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary','type' => 'submit')); ?>
        </div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->