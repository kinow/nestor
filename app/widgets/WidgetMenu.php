<?php

use Teepluss\Theme\Theme;
use Teepluss\Theme\Widget;
use Nestor\Repositories\ProjectRepository;
use \Session;

class WidgetMenu extends Widget {

	/**
	 * The project repository implementation.
	 *
	 * @var Nestor\Repositories\ProjectRepository
	 */
	protected $projects;

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

    protected $theme;

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
        $this->projects = App::make('Nestor\Repositories\ProjectRepository');
        $this->setAttribute('active', $theme->getActive());
        $theme->setProjects($this->projects->all());
        $current_project = unserialize(Session::get('current_project'));
        $this->setAttribute('current_project', $current_project);
        $this->theme = $theme;
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
    	$projects = $this->theme->getProjects();
    	$currentProject = $this->getAttribute('current_project');
        $currentProjectExists = FALSE; // The project may have been deleted...

    	$projectitems = '';
    	if (!$projects || count($projects) <= 0) {
    		$projectitems .= '<ul class="nav" style="float: right;"><li>'. HTML::link('/projects/create', 'Create a new project') . '</li></ul>';
    	} else {
    		$projectitems .= '<select class="form-control" name="project_id" style="margin: 5px 0px 0px 0px;" onchange="javascript:position_project(this);">';
    		$projectitems .= '<option>-- Choose a project --</option>';
    		foreach ($projects as $project) {
    			if ($currentProject != null && strcmp($currentProject->name, $project->name) == 0) {
                    $currentProjectExists = TRUE;
    				$projectitems .= "<option value='$project->id' selected='selected'>$project->name</option>";
    			} else {
    				$projectitems .= "<option value='$project->id'>$project->name</option>";
    			}
    		}
    		$projectitems .= '</select>';
    	}
        
        $items = array();
        $items['home'] = HTML::link('/', 'Home');
        $items['projects'] = HTML::link('/projects/', 'Projects');
        $action_links_attribute = $currentProjectExists ? '' : 'style="color: red; display: none;"';
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

        $this->setAttribute('menuitems', $menuitems);
        $this->theme->set('projectitems', $projectitems);

        $attrs = $this->getAttributes();

        return $attrs;
    }

}