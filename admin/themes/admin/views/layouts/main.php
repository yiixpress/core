<?php
/**
 * The wrapper layout take care of
 * - Breadcrumbs
 * - Page title
 * - Menu on sidebar
 * - System menu and user profile
 * - The footer
 */
?>
<?php $this->beginContent('//layouts/wrapper');?>

	<div class="innerLR">
		<?php echo $content; ?>
	</div>

<?php $this->endContent(); ?>