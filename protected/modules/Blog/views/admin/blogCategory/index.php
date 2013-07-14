<?php
$this->breadcrumbs = array(
	'Blog Categories',
);

$this->menu = array(
	array('label' => 'Create BlogCategory', 'url' => array('create')),
);
?>

<h1>Blog Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView'     => '_view',
)); ?>
