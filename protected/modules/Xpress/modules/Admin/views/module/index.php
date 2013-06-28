<?php
$this->breadcrumbs=array(
	'Modules',
);

$this->menu=array(
	array('label'=>'Create Module', 'url'=>array('create')),
);
?>

<h1>Modules</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
