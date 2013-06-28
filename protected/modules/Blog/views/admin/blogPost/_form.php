<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'blog-post-form',
    'htmlOptions'=>array(
	    //'class'=>'form-horizontal', // comment this line to use long form
    ),
	'enableAjaxValidation'=>false,
)); ?>

    <?php $this->widget('Xpress.components.InfoBox',array('heading'=>'')); ?>
    <?php if (! $model->IsNewRecord) echo $form->hiddenField($model, "id") ; ?>

	<div class="row-fluid">
		<!-- left column -->
		<div class="span3">
			<div class="control-group">
				<?php echo $form->labelEx($model, 'title', array('class' => 'control-label',)); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'title', array('rows' => 6, 'cols' => 50)); ?>
					<?php echo $form->error($model, 'title'); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $form->labelEx($model, 'alias', array('class' => 'control-label',)); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'alias', array('rows' => 6, 'cols' => 50)); ?>
					<?php echo $form->error($model, 'alias'); ?>
				</div>
			</div>
			<div class="control-group">
				<?php echo $form->labelEx($model, 'revision_log', array('class' => 'control-label',)); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'revision_log', array('size' => 60, 'maxlength' => 512)); ?>
					<?php echo $form->error($model, 'revision_log'); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $form->labelEx($model, 'status', array('class' => 'control-label',)); ?>
				<div class="controls">
					<?php echo $form->textField($model, 'status'); ?>
					<?php echo $form->error($model, 'status'); ?>
				</div>
			</div>
		</div>
		<!-- // left column -->

		<!-- right column -->
		<div class="span9">
			<div class="control-group">
				<div class="controls">
					<?php echo $form->ckEditorRow($model, 'content', array('options' => array(
						'fullpage'        => 'js:true',
						'width'           => '100%',
						'resize_maxWidth' => '100%',
						'resize_minWidth' => '480',
						'toolbarGroups'   => "js:[
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
							{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
							{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
							'/',
							{ name: 'styles' },
							{ name: 'colors' },
							{ name: 'links' },
							{ name: 'insert' },
							{ items: ['Source']},
						//	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
						//	{ name: 'forms' },
						//	{ name: 'tools' },
						//	{ name: 'others' },
						//	{ name: 'about' }
						]",
					))); ?>
					<?php echo $form->error($model, 'content'); ?>
				</div>
			</div>

		</div>
		<!-- // right column -->

	</div>

	<div class="control-group buttons">
        <div class="controls pull-right">
		    <?php
		    echo CHtml::htmlButton($model->isNewRecord ? 'Create' : 'Save',array('class' => 'btn btn-primary','type' => 'submit')); ?>
        </div>
		<div class="clearfix"></div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->