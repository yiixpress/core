<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CmsWidgetForm', array(
    'formParams'=>\$formParams,
    'formLayoutParams'=>\$formLayoutParams,
));?>\n"; ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

    <?php echo '<?php if (! $model->IsNewRecord) echo $form->hiddenField($model, "'.$this->tableSchema->primaryKey.'") ; ?>'."\n"; ?>
<?php if (isset($form) && is_object($form)):?>
<?php
foreach($this->form->elements as $element) {
    echo $this->generateInput($element);
}
?>

<?php if ($form->captcha):?>
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
<?php else:?>
<?php
foreach($this->tableSchema->columns as $column)
{
    if($column->isPrimaryKey)
        continue;
?>
    <div class="row">
        <?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
        <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
        <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
    </div>

<?php
}
?>
<?php endif;?>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->