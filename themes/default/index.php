<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->get('page_title', 'Nestor QA');?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nestor QA - Test Management">
    <meta name="author" content="Nestor QA">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/sitemapstyler.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Le jQuery and bootstrap JS -->
    <script src="js/jquery-1.10.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/sitemapstyler.js"></script>
    <script src="js/script.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>
  	<?php bootstrap_menus( isset($active) ? $active : 'home', isset($projects) ? $projects : array() , isset($project) ? $project : null ); ?>
  
    <div class="container">

      <!-- Example row of columns -->
      <div class="row">
      	<div class="span12">
      	  <?php //display messages ?>
		  <?php bootstrap_messages( array_merge($this->messages(FALSE)) ); ?>

		  <?php //display content (the view) ?>
      	  <?php echo $this->content(); ?>
		</div>
	  </div>

      <hr>

      <footer class='text-right'>
        <p>Page rendered in <strong>{elapsed_time}</strong> seconds <a href="http://www.nestor-qa.org">Nestor ver. <?php echo nestor_version(); ?></a></p>
      </footer>

    </div> <!-- /container -->

  </body>
</html>
