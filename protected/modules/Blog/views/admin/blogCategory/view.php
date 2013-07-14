<?php
$this->breadcrumbs = array(
	'Blog Categories' => array('index'),
	$model->name,
);

$this->menu = array(
	array('label' => 'Create BlogCategory', 'url' => array('create')),
	array('label' => 'Update BlogCategory', 'url' => array('update', 'id' => $model->id)),
	array('label' => 'Delete BlogCategory', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
);
?>

<h1>View BlogCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'id',
		'name',
		'alias',
		'parent_id',
		'status',
		'date_added',
		'date_updated',
	),
)); ?>
