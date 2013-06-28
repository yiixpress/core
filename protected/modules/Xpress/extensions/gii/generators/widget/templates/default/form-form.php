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

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->