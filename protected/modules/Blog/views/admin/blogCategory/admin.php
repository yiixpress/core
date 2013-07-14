<div class="row-fluid">
	<div id="form" class="span6">
		<?php
		$currentCategory = $models[0];
		$this->renderPartial('update', array(
			'model'      => $currentCategory,
			'categories' => $categories,
		))
		?>
	</div>

	<?php
	/**
	 * This setup code must go after rendering the form so it can override setup in 'update' view
	 */
	$this->layout = '//layouts/list';
	$this->pageTitle = 'Manage Blog Categories';
	$this->breadcrumbs = array(
		'Blog Categories' => array('index'),
		'Manage',
	);
	?>

	<?php
	// grid actions
	$this->menu = array(
		array(
			'label' => 'Menu',
			'url'   => '#',
			'items' => array(
				array(
					'label' => 'Delete selected', 'url' => '#', 'class' => 'delete-multi', 'icon' => 'delete',
				),
				array(
					'label' => 'Expand All', 'url' => '#', 'class' => 'expand-all', 'icon' => 'delete',
				),
				array(
					'label' => 'Collapse All', 'url' => '#', 'class' => 'collapse-all', 'icon' => 'delete',
				),
			)
		),
		array(
			'label' => 'Add',
			'url'   => $this->createUrl('update'),
			'class' => 'btn-primary xtarget-detail',
			'icon'  => 'new-row'
		),
	);
	?>

	<div id="category-tree" class="span6">
		<div id="category-tree-content-wrapper">
			<?php $this->renderPartial('_tree', array('models' => $models)); ?>
		</div>
	</div>
</div>

<?php
$script = "
	$('#blog-category-form').live('update-success',function(){
		$('#category-tree').load(location.href + ' #category-tree-content-wrapper', fixTreeColumnWidth);
	});
	";
cs()->registerScript('update-tree', $script, CClientScript::POS_READY);
?>