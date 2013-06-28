<?php $this->beginContent('//layouts/master'); ?>
<!-- Main Container Fluid -->
<div class="container-fluid fluid menu-left">
	<!-- Top navbar -->
	<div class="navbar main hidden-print">
		<!-- Brand -->
		<a href="/admin"
		   class="appbrand pull-left"><?php echo SETTINGS_SITE_NAME ?></a>
		<ul class="topnav pull-left tn1">
			<!-- Pull left items -->
			<!-- // Pull left items -->
		</ul>
		<!-- Top Menu Right -->
		<ul class="topnav pull-right">
			<!-- Tools -->
			<li class="dropdown visible-abc">
				<a href="#" data-toggle="dropdown" class="glyphicons cogwheel"><i></i>Tools <span class="caret"></span></a>
				<?php $this->widget('Admin.components.AdminSystemMenu'); ?></li>

			<!-- Profile / Logout menu -->
			<li class="account"><?php $this->widget('Admin.components.AdminProfileMenu'); ?></li>

		</ul>
		<!-- // Top Menu Right END -->
	</div>
	<!-- Top navbar END -->

	<!-- Sidebar menu & content wrapper -->
	<div id="wrapper">

		<!-- Sidebar Menu -->
		<div id="menu" class="hidden-phone hidden-print">

			<!-- Scrollable menu wrapper with Maximum height -->
			<div class="slim-scroll">

				<!-- Sidebar Profile -->
			<span class="profile">
				<a class="img" href="#"><img src="http://dummyimage.com/51x51/232323/ffffff&amp;text=photo"
				                             alt="Mr. Awesome"/></a>
				<span>
					<strong>Welcome</strong>
					<a href="#" class="glyphicons"><?php echo user()->name; ?><i></i></a>
				</span>
			</span>
				<!-- // Sidebar Profile END -->

				<!-- Sidebar Mini Stats -->
				<div id="notif">
					<ul>
						<li><a href="" class="glyphicons envelope"><i></i> 5</a></li>
						<li><a href="" class="glyphicons shopping_cart"><i></i> 1</a></li>
						<li><a href="" class="glyphicons log_book"><i></i> 3</a></li>
						<li><a href="" class="glyphicons user_add"><i></i> 14</a></li>
					</ul>
				</div>
				<!-- // Sidebar Mini Stats END -->

				<!-- Regular Size Menu -->
				<?php $this->widget('Admin.components.AdminMainMenu'); ?>
				<div class="clearfix"></div>
				<div class="separator bottom"></div>
				<!-- // Regular Size Menu END -->


			</div>
			<!-- // Scrollable Menu wrapper with Maximum Height END -->

		</div>
		<!-- // Sidebar Menu END -->

		<!-- Content -->
		<div id="content">
			<?php
			$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'links' => $this->breadcrumbs,
			));
			?>
			<script>
				$(".breadcrumb .divider").css("border", "none");
				$(".breadcrumb li:first a").addClass('glyphicons home').prepend('<i></i>');
			</script>
			<!-- // Breadcrumb END -->

			<!-- Heading -->
			<div class="heading-buttons">
				<h3>
					<?php if ($this->pageTitle != '') : ?>
						<?php echo $this->pageTitle; ?><span> | <?php echo $this->module->Id ?></span>
					<?php else: ?>
						<?php echo $this->module->Id ?>
					<?php endif; ?>
				</h3>

				<?php if (!empty($this->menu)) : ?>
				<div class="buttons pull-right grid-actions">
					<?php
					foreach($this->menu as $action) {
						if (!isset($action['items'])) {
							ThemeHelper::linkButton($action);
						} else {
							ThemeHelper::dropDownButtons($action);
						}
					}
					?>
				</div>
				<?php endif; ?>
				<div class="clearfix"></div>
			</div>
			<div class="separator bottom"></div>
			<!-- // Heading END -->

			<?php echo $content; ?>
		</div>
		<!-- // Content END -->
	</div>
	<div class="clearfix"></div>
	<!-- // Sidebar menu & content wrapper END -->

	<div id="footer" class="hidden-print">
		<div class="footer-inner">
			<div class="pull-left">
				<a href="#" class="glyphicons fullscreen toggle-sidebar"><i></i></a>
				<a href="#" class="glyphicons new_window"><i></i></a>
				<a href="#" class="glyphicons home"><i></i></a>
				<a href="#" class="glyphicons refresh"><i></i></a>
			</div>
			<div class="pull-right">
				<div
					class="info-pane"><?php echo app()->request->hostInfo, ' (' . $_SERVER['SERVER_ADDR'] . ')' ?></div>
				<a href="#" class="glyphicons settings"><i></i></a>
			</div>
		</div>
	</div>
	<!-- // Footer END -->

</div>
<!-- // Main Container Fluid END -->
<?php $this->endContent(); ?>