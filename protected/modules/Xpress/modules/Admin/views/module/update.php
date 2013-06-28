<?php
$this->breadcrumbs=array(
	'Modules'=>array('index'),
);
if ($model->name)
    $this->breadcrumbs = CMap::mergeArray($this->breadcrumbs, array($model->name=>array('update','id'=>$model->id),'Update'));
else
    $this->breadcrumbs[] = 'Create';

$this->menu=array(
);
?>

<h1>Edit Module <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>