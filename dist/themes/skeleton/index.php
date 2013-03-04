<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?php echo $this->get('page_title', 'Nestor QA');?></title>
	<meta name="description" content="Nestor QA - Test Management">
    <meta name="author" content="Nestor QA">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">
	<link rel="stylesheet" href="stylesheets/style.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

</head>
<body>
	
	<div class="container">
	
		<div class='sixteen columns'>
			<ul id="menu">
				<li><?php echo anchor('/', 'Home'); ?></li>
				<li><?php echo anchor('/projects', 'Projects'); ?></li>
				<li><?php echo anchor('/manage', 'Manage Nestor'); ?></li>
			</ul>
		</div>

		<div class="sixteen columns">
			<?php echo $this->messages(); ?>
			<?php echo $this->content(); ?>
		</div>

		<hr />
		
		<footer class='sixteen columns text-right'>
	        <p>Page rendered in <strong>{elapsed_time}</strong> seconds <a href="http://www.nestor-qa.org">Nestor ver. 0.1</a></p>
	    </footer>
		
	</div><!-- container -->

	<!-- JS
	================================================== -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
	<script src="javascripts/tabs.js"></script>

<!-- End Document
================================================== -->
</body>
</html>