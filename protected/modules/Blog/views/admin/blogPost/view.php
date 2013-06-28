<?php
$this->breadcrumbs=array(
	'Blog Posts'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Create BlogPost', 'url'=>array('create')),
	array('label'=>'Update BlogPost', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete BlogPost', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View BlogPost #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'alias',
		'content',
		'revision',
		'revision_log',
		'status',
		'views',
		'date_added',
		'date_updated',
	),
)); ?>
