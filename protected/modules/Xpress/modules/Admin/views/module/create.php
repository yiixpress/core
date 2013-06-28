<?php
$this->breadcrumbs=array(
	'Modules'=>array('index'),
	'Create',
);

$this->menu=array(
);
?>

<h1>Create Module</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>