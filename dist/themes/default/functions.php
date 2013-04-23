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
	function bootstrap_menus($active = 'home', $projects = array(), $session_project = null) {
		$items = array();
		$items['home'] = anchor('/', 'Home');
		$items['projects'] = anchor('/projects/', 'Projects');
		$action_links_attribute = (!isset($session_project) || $session_project != null) ? '' : 'style="color: red; display: none;"'; 
		$items['requirements'] = anchor('/requirements/', 'Requirements', $action_links_attribute);
		$items['specification'] = anchor('/specification/', 'Specification', $action_links_attribute);
		$items['planning'] = anchor('/planning/', 'Planning', $action_links_attribute);
		$items['execution'] = anchor('/execution/', 'Execution', $action_links_attribute);
		$items['reports'] = anchor('/reports/', 'Reports', $action_links_attribute);
		$items['manage'] = anchor('/manage/', 'Manage Nestor');
		$menuitems = '';
		foreach ($items as $key => $item) {
			if (strcmp($active, $key) == 0)
				$menuitems .= '<li class="active">'.$item.'</li>';
			else
				$menuitems .= '<li>'.$item.'</li>';
		}
		$projectitems = '';
		if (strcmp('manage', $active) == 0) {
			$projectitems = '';
		} else if (!isset($projects) || !is_array($projects) || count($projects) <= 0) {
			$projectitems .= '<ul class="nav" style="float: right;"><li><a href="' . site_url('/projects/create') . '">Create a new project</a></li></ul>';
		} else {
			$projectitems .= '<select name="project_id" style="margin: 5px 0px 0px 0px;" onchange="javascript:position_project(this);">';
			$projectitems .= '<option></option>';
			foreach ($projects as $project) {
				if (isset($session_project) && $session_project != null && strcmp($session_project->name, $project->name) == 0) {
					$projectitems .= "<option value='$project->id' selected='selected'>$project->name</option>";
				} else {
					$projectitems .= "<option value='$project->id'>$project->name</option>";
				}
			}
			$projectitems .= '</select>';
		}
		$base_url = base_url('/');
		$menu = <<<EOM
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse">
				<ul class="nav">
					$menuitems
			    </ul>
			</div><!--/.nav-collapse -->
			<div class='nav-collapse text-right'>
				<form method="get" action="{$base_url}projects/position" style="margin: 0px;">
					$projectitems
				</form>
			</div>
		</div>
	</div>
</div>
EOM;
		echo $menu;
	}
}

?>