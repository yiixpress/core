<?php
$this->pageTitle = 'Manage modules';

$this->breadcrumbs=array(
	'Modules'=>array('index'),
	'Manage',
);
?>


<?php

$data = CMap::mergeArray($model->search()->getData(),$newModules);

$grid = $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'module-grid',
	'dataProvider'=>new CArrayDataProvider($data,array('pagination' => false)),
    'selectableRows'=>2,
    'htmlOptions'=>array('class' => 'grid'),

	'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'value'=>'$data->id',
            'htmlOptions'=>array('width'=>'3%'),
        ),
		//'id',
		//'name',
		array(
            'header'=>'Name',
            'name'=>'friendly_name'
        ),
		'description',
        'version',
        array(
            'class'=>'bootstrap.widgets.TbToggleColumn',
            'toggleAction'=>'/Admin/module/enable',
            'header'=>'Enabled',
            'name'=>'enabled'
        ),
		array(
            'class'=>'bootstrap.widgets.TbToggleColumn',
            'toggleAction'=>'/Admin/module/install',
            'header'=>'Installed',
            'name'=>'installed'
        ),
		/*
		'has_back_end',
		'ordering',
		'icon',
		'is_system',
		'path',
		*/
		array(
			'class'=>'CButtonColumn',
            'viewButtonOptions'=>array('class' => 'view xtarget-detail'),
            'updateButtonOptions'=>array('class' => 'update xtarget-detail'),
		),
	),
));