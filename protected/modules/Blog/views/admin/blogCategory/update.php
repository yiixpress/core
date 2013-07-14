<?php
$this->layout = '//layouts/form';
if (app()->request->isAjaxRequest) {
	$this->layout = false;
}

$this->pageTitle = ($model->isNewRecord ? 'Create ' : 'Edit ') . 'Blog Categories';
$this->breadcrumbs = array(
	'Blog Categories' => array('index'),
);
if ($model->name) {
	$this->breadcrumbs = CMap::mergeArray($this->breadcrumbs, array($model->name => array('update', 'id' => $model->id), 'Update'));
} else {
	$this->breadcrumbs[] = 'Create';
}

$this->menu = array();
?>

<?php echo $this->renderPartial('_form', array(
	'model'      => $model,
	'categories' => $categories,
)); ?>

<?php
$script = "
$(function () {
	/**
	 * When user click title of a category to edit, ajax load the form
	 */
	$('a.xtarget-detail').click(function () {
		$('div.form').load(this.href, 'form#blog-category-form');
		return false;
	});

	/**
	* Use ajax to update form
	*/
	$('#blog-category-form').live('submit',function(){
		$.post(
			'" . $this->createUrl('update') . "',
			$(this).serialize(),
			function(data){
				$('div.form').parent().html(data);
				// update the tree also
				$('#blog-category-form').trigger('update-success');
			}
		);
		return false;
	});
});
";

if (!app()->request->isAjaxRequest) {
	cs()->registerScript('ajax-form', $script, CClientScript::POS_READY);
}
?>