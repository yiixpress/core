<table class="items">
	<tr class="<?php echo $class; ?> xtable-data-row">
		<td align="center"
		    class="checkbox-column minimal-hide"><?php echo CHtml::checkBox('item', false, array('value' => $model->id, 'class' => 'select-on-check')); ?></td>
		<td class="sort-handle title-column">
			<?php echo $hasChild ? CHtml::link('<i class="icon-plus icon-minus"></i>', '#', array('class' => 'collapse')) : ''; ?>
			<?php echo CHtml::link($model->name, array('update', 'id' => $model->id), array('title' => $model->name, 'class' => 'xtarget-detail')); ?>
		</td>

		<td class="status-column">
			<?php echo CHtml::link($model->status ? '<i class="icon-ok"></i>' : '<i class="icon-ban-circle"></i>',
				array(
					"/Xpress/service/ajax",
					'SID'   => 'Blog.BlogCategory.changeStatus',
					"ids[]" => $model->id,
				),
				array(
					"class" => ($model->status ? "active" : "")
				)
			);?>
		</td>
		<td align="center" class="actions-column">

			<?php echo CHtml::link('<i></i>', array('update', 'id' => $model->id), array('title' => 'Edit', 'class' => 'xtarget-detail glyphicons btn-icon-only edit')); ?>
			<?php echo CHtml::link('<i></i>', array('delete', 'ids[]' => $model->id), array('class' => 'glyphicons btn-icon-only circle_remove', 'title' => 'Delete')); ?>
			<?php echo CHtml::link('<i></i>', array('update', 'parent_id' => $model->id), array('class' => 'add glyphicons btn-icon-only circle_plus xtarget-detail', 'title' => 'Add')); ?>
		</td>
	</tr>
</table>