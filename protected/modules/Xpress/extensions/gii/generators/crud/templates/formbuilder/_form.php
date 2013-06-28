<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

    <?php echo '<?php if (! $model->IsNewRecord) echo $form->hiddenField($model, "'.$this->tableSchema->primaryKey.'") ; ?>'."\n"; ?>
<?php
foreach($this->form->elements as $element) {
    echo $this->generateInput($element);
}
?>

<?php if ($this->form->captcha):?>
    <?php echo '<?php if(CCaptcha::checkRequirements()): ?>'."\n";?>
    <div class="row">
        <?php echo '<?php echo $form->labelEx($model,\'verifyCode\'); ?>'."\n";?>
        <div>
        <?php echo '<?php $this->widget(\'CCaptcha\'); ?>'."\n";?>
        <?php echo '<?php echo $form->textField($model,\'verifyCode\'); ?>'."\n";?>
        </div>
        <?php echo '<?php echo $form->error($model,\'verifyCode\'); ?>'."\n";?>
    </div>
    <?php echo '<?php endif; ?>'."\n";?>
<?php endif; ?>
    
	<div class="row buttons">
		<?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save'); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->