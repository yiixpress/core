<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]>
<html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!-->
<html><!-- <![endif]-->
<head>
	<title><?php echo SETTINGS_SITE_NAME.' Admin'?></title>

	<!-- Meta -->
	<meta charset="UTF-8"/>
	<meta name="viewport"
	      content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>

	<!-- Bootstrap -->
	<link href="<?php echo themeUrl(); ?>/bootstrap/css/bootstrap.css" rel="stylesheet"/>
	<link href="<?php echo themeUrl(); ?>/bootstrap/css/responsive.css" rel="stylesheet"/>

	<!-- Glyphicons Font Icons -->
	<link href="<?php echo themeUrl(); ?>/css/glyphicons.css" rel="stylesheet"/>

	<!-- Uniform Pretty Checkboxes -->
	<link href="<?php echo themeUrl(); ?>/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet"/>

	<!-- Main Theme Stylesheet :: CSS -->
	<link href="<?php echo themeUrl(); ?>/css/style-light.css" rel="stylesheet"/>
	<link href="<?php echo themeUrl(); ?>/css/custom.css" rel="stylesheet"/>


	<!-- LESS.js Library -->
	<script src="<?php echo themeUrl(); ?>/scripts/plugins/system/less.min.js"></script>
</head>
<body class="">

<?php echo $content; ?>


<?php cs()->registerCoreScript('jquery'); ?>

<!-- JQueryUI -->
<script src="<?php echo themeUrl(); ?>/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

<!-- JQueryUI Touch Punch -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="<?php echo themeUrl(); ?>/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<!-- Modernizr -->
<script src="<?php echo themeUrl(); ?>/scripts/plugins/system/modernizr.js"></script>

<!-- Bootstrap Loaded by 'bootstrap' component already -->
<!-- script src="<?php echo themeUrl(); ?>/bootstrap/js/bootstrap.min.js"></script -->

<!-- Common Demo Script -->
<script src="<?php echo themeUrl(); ?>/scripts/common.js"></script>

<!-- Uniform Forms Plugin -->
<script src="<?php echo themeUrl(); ?>/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

<!-- Global -->
<script>
	var basePath = '<?php echo themeUrl(); ?>/';
</script>


</body>
</html>