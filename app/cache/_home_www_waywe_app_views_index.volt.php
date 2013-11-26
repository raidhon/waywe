<!DOCTYPE html>
<html class="login-bg">
<head>
	<?php echo $this->tag->getTitle(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="vi wport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
	<?php echo $this->tag->stylesheetLink('css/fw/bootstrap.css'); ?>
        <?php echo $this->tag->stylesheetLink('css/fw/bootstrap-overrides.css'); ?>
    <!-- global styles -->
    	<?php echo $this->tag->stylesheetLink('css/fw/layout.css'); ?>
    	<?php echo $this->tag->stylesheetLink('css/fw/elements.css'); ?>
    	<?php echo $this->tag->stylesheetLink('css/fw/icons.css'); ?>

    <!-- libraries -->
    	<?php echo $this->tag->stylesheetLink('css/fw/font-awesome.css'); ?>
	
	
	<!-- this page specific styles -->
	<?php echo $this->assets->outputCss(); ?>
    
	<?php echo $this->tag->stylesheetLink('css/waywe.css'); ?>
    
	<!-- open sans font -->
    	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800" rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
	<!-- JS -->
	<?php echo $this->tag->javascriptInclude('js/fw/jquery-1.10.2.min.js'); ?>
	<?php echo $this->tag->javascriptInclude('js/fw/bootstrap.min.js'); ?>
	<?php echo $this->tag->javascriptInclude('js/fw/jquery-migrate-1.2.1.min.js'); ?>
	<?php echo $this->assets->outputJs(); ?>
	
	
	<?php echo $this->tag->javascriptInclude('js/waywe.js'); ?>
	
	

	</head>
<body>
	<?php echo $this->getContent(); ?>
</body>
</html>