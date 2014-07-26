<?php

use Nestor\Repositories\ReportRepository;
use Nestor\Repositories\ReportTypeRepository;

class ReportsController extends BaseController {

	protected $reports;

	public function __construct(ReportRepository $reports, 
		ReportTypeRepository $reportTypes)
	{
		parent::__construct();
		$this->reports = $reports;
		$this->reportTypes = $reportTypes;
		$this->beforeFilter('@isAuthenticated');
		$this->theme->setActive('reports');
	}

	public function index() 
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Reports');
		$args = array();
		$args['reports'] = $this->reports->all();
		return $this->theme->scope('report.index', $args)->render();
	}

	public function create()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Reports', URL::to('/reports'))->
			add('Create new report');
		$args['reportTypes'] = $this->reportTypes->all();
		return $this->theme->scope('report.create', $args)->render();
	}
	
}
