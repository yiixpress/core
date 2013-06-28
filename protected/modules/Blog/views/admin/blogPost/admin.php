<?php
// Use list layout to enhance the UI
$this->layout = '//layouts/list';
?>

<?php
// Set title and breadcrumbs
$this->pageTitle = 'Manage Blog Posts';
$this->breadcrumbs=array(
	'Blog Posts'=>array('index'),
	'Manage',
);
?>

<?php
/**
 * Grid menu is set with default actions in the layout
 *  - to disable actions, set $this->menu = NULL
 *  - to customize actions, define each action in $this->menu array
 */
?>

<?php $grid = $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'blog-post-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'selectableRows'=>2,
    'htmlOptions'=>array('class' => 'grid'),
	'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'value'=>'$data->id',
            'htmlOptions'=>array('width'=>'3%'),
        ),
		'title',
		'revision_log',
		/*
		'status',
		'views',
		'date_added',
		'date_updated',
		*/
		array(
			'class'=>'CButtonColumn',
            'viewButtonOptions'=>array('class' => 'view xtarget-detail'),
            'updateButtonOptions'=>array('class' => 'update xtarget-detail'),
		),
	),
));