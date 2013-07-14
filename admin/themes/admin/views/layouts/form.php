<?php $this->beginContent('//layouts/wrapper'); ?>
	<div class="widget">
		<!-- Widget heading -->
		<div class="widget-head">
			<h4 class="heading"><?php echo $this->pageTitle; ?></h4>
			<?php $this->pageTitle = NULL; ?>
		</div>
		<!-- // Widget heading END -->

		<div class="widget-body">

			<?php echo $content; ?>

		</div>
	</div>

	<script>
		$('form div.buttons').before('<hr class="separator">');
		$('form button[type="submit"]').addClass('btn btn-icon btn-primary glyphicons circle_ok').prepend('<i></i>');
	</script>
<?php $this->endContent(); ?>