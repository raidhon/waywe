<!DOCTYPE html>
<html class="login-bg">
<head>
	{{ get_title() }}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="vi wport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
	{{ stylesheet_link('css/fw/bootstrap.css') }}
        {{ stylesheet_link('css/fw/bootstrap-overrides.css') }}
    <!-- global styles -->
    	{{ stylesheet_link('css/fw/layout.css') }}
    	{{ stylesheet_link('css/fw/elements.css') }}
    	{{ stylesheet_link('css/fw/icons.css') }}

    <!-- libraries -->
    	{{ stylesheet_link('css/fw/font-awesome.css') }}
	
	
	<!-- this page specific styles -->
	{{ assets.outputCss() }}
    
	{{ stylesheet_link('css/waywe.css') }}
    
	<!-- open sans font -->
    	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800" rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
	<!-- JS -->
	{{ javascript_include("js/fw/jquery-1.10.2.min.js")}}
	{{ javascript_include("js/fw/bootstrap.min.js")}}
	{{ javascript_include("js/fw/jquery-migrate-1.2.1.min.js")}}
	{{ assets.outputJs() }}
	
	
	{{ javascript_include("js/waywe.js")}}
	
	

	</head>
<body>
	{{ content() }}
</body>
</html>