<div class="form">

	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   => 'blog-category-form',
		'type'                 => 'horizontal',
		'htmlOptions'          => array('class' => 'form-horizontal'), // comment this line to use long form
		'enableAjaxValidation' => false,
	)); ?>

	<?php $this->widget('Xpress.components.InfoBox', array('heading' => '')); ?>
	<?php if (!$model->IsNewRecord) {
		echo $form->hiddenField($model, "id");
	} ?>
	<div class="control-group">
		<?php echo $form->labelEx($model, 'name', array('class' => 'control-label',)); ?>
		<div class="controls">
			<?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 256)); ?>
			<?php echo $form->error($model, 'name'); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model, 'alias', array('class' => 'control-label',)); ?>
		<div class="controls">
			<?php echo $form->textField($model, 'alias', array('size' => 60, 'maxlength' => 256)); ?>
			<?php echo $form->error($model, 'alias'); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model, 'parent_id', array('label' => 'Parent', 'class' => 'control-label')); ?>
		<div class="controls">
			<?php $attribute = 'parent_id';
			echo CHtml::openTag('select', array('name' => CHtml::resolveName($model, $attribute))); ?>
			<?php echo CHtml::tag('option', array(), 'Select Parent'); ?>
			<?php if (is_array($categories) && count($categories)): ?>
				<?php
				$hiddenLevel = NULL;
				foreach ($categories as $id => $data):?>
					<?php
					if ($model->id == $id) {
						$hiddenLevel = 1 + $data['level'];
						continue;
					}
					if ($hiddenLevel !== NULL) {
						if ($data['level'] >= $hiddenLevel) {
							continue;
						} else {
							$hiddenLevel = NULL;
						}
					}
					$htmlOptions = array('value' => $id);
					if ($model->parent_id == $id) {
						$htmlOptions['selected'] = 'selected';
					}
					echo CHtml::tag('option', $htmlOptions, str_repeat('--', $data['level']) . $data['name']);?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php echo CHtml::closeTag('select'); ?>
			<?php echo $form->error($model, 'parent_id'); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model, 'status', array('class' => 'control-label',)); ?>
		<div class="controls">
			<?php echo $form->checkBox($model, 'status'); ?>
		</div>
	</div>


	<div class="control-group buttons">
		<div class="controls">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->