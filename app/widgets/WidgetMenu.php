<?php

use Teepluss\Theme\Theme;
use Teepluss\Theme\Widget;

class WidgetMenu extends Widget {

    /**
     * Widget template.
     *
     * @var string
     */
    public $template = 'menu';

    /**
     * Watching widget tpl on everywhere.
     *
     * @var boolean
     */
    public $watch = false;

    /**
     * Attributes pass from a widget.
     *
     * @var array
     */
    public $attributes = array(
        'active' => 'home',
        'projects'  => array(),
    	'current_project' => null
    );

    /**
     * Code to start this widget.
     *
     * @return void
     */
    public function init(Theme $theme)
    {
        // Initialize widget.

        //$theme->asset()->usePath()->add('widget-name', 'js/widget-execute.js', array('jquery', 'jqueryui'));
        //$this->setAttribute('user', User::find($this->getAttribute('userId')));
        $this->setAttribute('active', $theme->getActive());
        $this->setAttribute('projects', $theme->getProjects());
        $this->setAttribute('current_project', $theme->getCurrentProject());
    }

    /**
     * Logic given to a widget and pass to widget's view.
     *
     * @return array
     */
    public function run()
    {
    	$active = $this->getAttribute('active');
    	if (!$active) {
    		$active = 'home';
    	}
    	$projects = $this->getAttribute('projects');
    	$current_project = $this->getAttribute('current_project');

    	$items = array();
    	$items['home'] = HTML::link('/', 'Home');
    	$items['projects'] = HTML::link('/projects/', 'Projects');
    	$action_links_attribute = $current_project ? '' : 'style="color: red; display: none;"';
    	$items['requirements'] = HTML::link('/requirements/', 'Requirements', $action_links_attribute);
    	$items['specification'] = HTML::link('/specification/', 'Specification', $action_links_attribute);
    	$items['planning'] = HTML::link('/planning/', 'Planning', $action_links_attribute);
    	$items['execution'] = HTML::link('/execution/', 'Execution', $action_links_attribute);
    	$items['reports'] = HTML::link('/reports/', 'Reports', $action_links_attribute);
    	$items['manage'] = HTML::link('/manage/', 'Manage Nestor');
    	$menuitems = '';
    	foreach ($items as $key => $item) {
    		if (strcmp($active, $key) == 0)
    			$menuitems .= '<li class="active">'.$item.'</li>';
    		else
    			$menuitems .= '<li>'.$item.'</li>';
    	}

    	$projectitems = '';
    	if (!isset($projects) ||  is_null($projects) || !is_array($projects) ||count($projects) <= 0) {
    		$projectitems .= '<ul class="nav" style="float: right;"><li>'. HTML::link('/projects/create', 'Create a new project') . '</li></ul>';
    	} else {
    		$projectitems .= '<select name="project_id" style="margin: 5px 0px 0px 0px;" onchange="javascript:position_project(this);">';
    		$projectitems .= '<option></option>';
    		foreach ($projects as $project) {
    			if (isset($current_project) && $current_project != null && strcmp($current_project->name, $project->name) == 0) {
    				$projectitems .= "<option value='$project->id' selected='selected'>$project->name</option>";
    			} else {
    				$projectitems .= "<option value='$project->id'>$project->name</option>";
    			}
    		}
    		$projectitems .= '</select>';
    	}
        //$label = $this->getAttribute('label');

        $this->setAttribute('menuitems', $menuitems);
        $this->setAttribute('projectitems', $projectitems);

        $attrs = $this->getAttributes();

        return $attrs;
    }

}