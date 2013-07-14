<?php
include_once(__DIR__ . '/../ThemeHelper.php');
$this->beginContent('//layouts/main');
?>

<?php
// grid actions
if (is_array($this->menu) && empty($this->menu)) {
	$this->menu = array(
		array(
			'label' => 'With Selected',
			'url'   => '#',
			'items' => array(
				array(
					'label' => 'Delete selected', 'url' => '#', 'class' => 'delete-selected btn-danger', 'icon' => 'delete',
				)
			)
		),
		array(
			'label' => 'Add',
			'url'   => $this->createUrl('update'),
			'class' => 'btn-primary',
			'icon'  => 'new-row'
		),
	);
}
?>

	<div class="widget">

		<!-- Widget heading -->
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i>
				<?php
				echo $this->pageTitle;
				$this->pageTitle = ''; //make the wrapper doesn't show title again
				?>
			</h4>
		</div>
		<!-- // Widget heading END -->

		<div class="widget-body">

			<!-- Total products & Sort by options -->
			<div class="form-inline separator bottom small">
				<!-- summary of displaying row -->
				<span class="pull-right">

				</span>
			</div>
			<!-- // Total products & Sort by options END -->

			<!-- Filters -->
			<?php if (file_exists($this->getViewPath() . '/_search.php')) : ?>
				<div class="filter-bar">
					<?php $this->renderPartial('_search'); ?>
				</div>
			<?php endif; ?>
			<!-- // Filters END -->

			<!-- Main table -->
			<?php echo $content; ?>
			<!-- // Main table END -->

			<!-- Options -->
			<div class="separator top form-inline small">

				<!-- Pagination -->
				<div class="pagination pagination-small pull-right" style="margin: 0;">
					<!-- ul>
						<li class="disabled"><a href="#">&laquo;</a></li>
					<li class="active"><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">&raquo;</a></li>
				</ul -->
				</div>
				<div class="clearfix"></div>
				<!-- // Pagination END -->

			</div>
			<!-- // Options END -->

		</div>
	</div>

	<script>
		// move the summary of displaying row up to the right position
		//	$('.filter-bar').prev().prepend($('.grid .summary').clone());
		//	$('.grid .summary').remove();
	</script>

<?php $this->endContent();

cs()->registerScriptFile(themeUrl() . '/bootstrap/extend/bootstrap-select/bootstrap-select.js', CClientScript::POS_HEAD);
cs()->registerCssFile(themeUrl() . '/bootstrap/extend/bootstrap-select/bootstrap-select.css');
?>