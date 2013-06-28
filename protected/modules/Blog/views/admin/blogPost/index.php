<?php
$this->breadcrumbs=array(
	'Blog Posts',
);

$this->menu=array(
	array('label'=>'Create BlogPost', 'url'=>array('create')),
);
?>

<h1>Blog Posts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
