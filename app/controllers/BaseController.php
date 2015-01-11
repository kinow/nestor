<?php

class BaseController extends Controller {

	/**
	 * Application Theme
	 * @var \Teepluss\Theme\Theme
	 */
	protected $theme;

	protected $currentProject;

	public function __construct()
	{
		$this->theme = Theme::uses('default'); // FIXME: get theme name from config or DB
		$this->theme->setTitle('Nestor QA'); // FIXME: get it from the configs or DB
		// Redirect if Nestor QA is not installed
		if (!isset(Setting::get('nestor')['installed']) || Setting::get('nestor')['installed'] !== true)
		{
			header('Location: /install');
			exit;
		}
		$this->currentProject = $this->getCurrentProject();
		$this->theme->set('current_project', $this->currentProject);
		$this->theme->breadcrumb()->setTemplate('
			<ul class="breadcrumb">
		    @foreach ($crumbs as $i => $crumb)
		        @if ($i != (count($crumbs) - 1))
		        <li><a href="{{ $crumb["url"] }}">{{ $crumb["label"] }}</a><span class="divider"></span></li>
		        @else
		        <li class="active">{{ $crumb["label"] }}</li>
		        @endif
		    @endforeach
		    </ul>
		');
	}

	public function isAuthenticated()
	{
		if (!Auth::check())
		{
			return Redirect::to('/users/login');
		}
	}

	/**
	 * Filter used to check if the current project is set in the session.
	 * Redirects to home page if not set.
	 */
	public function isCurrentProjectSet()
	{
		$currentProject = Session::get('current_project');
		if (!isset($currentProject) || !$currentProject)
		{
			Session::forget('current_project');
			return Redirect::to('/')->with('message', 'Choose a project first');
		}
	}

	/**
	 * Retrieves the current project set in Session.
	 *
	 * @return current project if set in session, null otherwise
	 */
	protected function getCurrentProject()
	{
		$currentProject = Session::get('current_project');
		if ($currentProject)
		{
			$currentProject = unserialize($currentProject);
			return $currentProject;
		}
		else
		{
			return null;
		}
	}

	public function getCurrentProjectId()
	{
		$currentProject = $this->getCurrentProject();
		return $currentProject['id'];
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}