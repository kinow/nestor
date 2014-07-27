<?php

use Nestor\Repositories\ReportRepository;
use Nestor\Repositories\ReportTypeRepository;
use Nestor\Repositories\ParameterTypeRepository;

class ReportsController extends BaseController {

	protected $reports;

	public function __construct(ReportRepository $reports, 
		ReportTypeRepository $reportTypes,
		ParameterTypeRepository $parameterTypes)
	{
		parent::__construct();
		$this->reports = $reports;
		$this->reportTypes = $reportTypes;
		$this->parameterTypes = $parameterTypes;
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
		$args['parameterTypes'] = $this->parameterTypes->all();
		return $this->theme->scope('report.create', $args)->render();
	}

	public function store() 
	{
		try 
		{
			$pdo = DB::connection()->getPdo();
			$report = $this->reports->create(Input::get('report_type_id'), Input::get('name'), Input::get('description'));
			if ($report->isValid() && $report->isSaved())
			{
				$pdo->commit();
			}
			$pdo->rollBack();
			dd('Would have crated!');
			return Redirect::to('/reports/create')
				->withErrors($report->errors())
	 			->withInput();
		}
		catch (\PDOException $e) {
			if (!is_null($pdo))
				try {
					$pdo->rollBack();
				} catch (Exception $ignoreme) {}
			return Redirect::to('/reports/create')
	 			->withInput();
		}
		if ($report->isSaved())
		{
			return Redirect::to('/reports/')
				->with('success', sprintf('Report %s created', Input::get('name')));
		} else {
			return Redirect::to('/reports/create')
				->withInput()
				->withErrors($report->errors());
		}
	}
	
}
