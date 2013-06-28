<?php
$this->breadcrumbs=array(
	'Blog Posts'=>array('index'),
	'Create',
);

$this->menu=array(
);
?>

<h1>Create BlogPost</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>