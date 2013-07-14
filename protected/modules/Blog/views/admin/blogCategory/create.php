<?php
$this->breadcrumbs = array(
	'Blog Categories' => array('index'),
	'Create',
);

$this->menu = array();
?>

	<h1>Create BlogCategory</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>