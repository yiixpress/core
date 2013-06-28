<?php $this->layout = '//layouts/form';?>

<?php
$this->pageTitle = ($model->isNewRecord ? 'Create ' : 'Edit ') . 'Blog Posts';
$this->breadcrumbs=array(
	'Blog Posts'=>array('index'),
);
if ($model->title)
    $this->breadcrumbs = CMap::mergeArray($this->breadcrumbs, array($model->title=>array('update','id'=>$model->id),'Update'));
else
    $this->breadcrumbs[] = 'Create';

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>