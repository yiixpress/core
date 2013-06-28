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

    <div class="row">
        <?php echo "<?php echo CHtml::label('Criteria','criteria'); ?>\n";?>
        <?php echo "<?php echo CHtml::textArea('criteria', \$criteria, array('cols'=>70,'rows'=>7)); ?>\n";?>
    </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->