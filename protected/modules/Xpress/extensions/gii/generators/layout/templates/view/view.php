<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
    echo "\t\t'".$column->name."',\n";
?>
    ),
)); ?>
<?php echo "<?php echo YII_DEBUG ? '<!-- '.__FILE__.' -->' : '';?>";?>