<?php

/**
 * When you set a theme... the 'functions.php' (this file)
 * will ALWAYS be autoloaded.
 * So template specific function go in here
 */

if (!function_exists('bootstrap_messages'))
{
	function bootstrap_messages( $messages = array() )
	{
		foreach($messages as $message)
		{
			echo '<div class="alert alert-block alert-' .$message['type'] . ' fade in" data-dismiss="alert"><button type="button" class="close" data-dismiss="alert">×</button>';
			echo htmlspecialchars($message['message']);
			echo '</div>';
		}
	}
}

if (!function_exists('bootstrap_menus')) {
	function bootstrap_menus($active = 'home') {
		$items = array(
			'home' => anchor('/', 'Home'),
			'projects' => anchor('/projects/', 'Projects'),
			'manage' => anchor('/manage/', 'Manage Nestor')
		);
		$menuitems = '';
		foreach ($items as $key => $item) {
			if (strcmp($active, $key) == 0)
				$menuitems .= '<li class="active">'.$item.'</li>';
			else
				$menuitems .= '<li>'.$item.'</li>';
		} 
		$menu = <<<EOM
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<span class="brand">Nestor QA</span>
			<div class="nav-collapse">
				<ul class="nav">
					$menuitems
			    </ul>
			</div><!--/.nav-collapse -->
			<div class='text-right'>
				<select></select>				
			</div>
		</div>
	</div>
</div>
EOM;
		echo $menu;
	}
}

?>