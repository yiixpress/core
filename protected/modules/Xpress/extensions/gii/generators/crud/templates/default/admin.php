<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
echo '$this->pageTitle = \'Manage '. $this->pluralize($this->class2name($this->modelClass)).'\';';
echo "\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>
?>

<?php
//we do not have advanced search for now, will use model search form
//echo "<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button xtarget-detail')); ? >";
?>

<?php echo "<?php"; ?> $grid = $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'selectableRows'=>2,
    'htmlOptions'=>array('class' => 'grid'),
	'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'value'=>'$data-><?php echo $this->Model->TableSchema->primaryKey; ?>',
            'htmlOptions'=>array('width'=>'3%'),
        ),
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'CButtonColumn',
            'viewButtonOptions'=>array('class' => 'view xtarget-detail'),
            'updateButtonOptions'=>array('class' => 'update xtarget-detail'),
		),
	),
));