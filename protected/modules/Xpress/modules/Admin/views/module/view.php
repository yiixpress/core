<?php
$this->breadcrumbs=array(
	'Modules'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Module', 'url'=>array('create')),
	array('label'=>'Update Module', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Module', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Module #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'friendly_name',
		'description',
		'enabled',
		'version',
		'has_back_end',
		'ordering',
		'icon',
		'is_system',
		'path',
	),
)); ?>
