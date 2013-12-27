<?php

use Nestor\Repositories\UserRepository;

class InstallController extends Controller {

	/**
	 * The user repository implementation.
	 *
	 * @var Nestor\Repositories\UserRepository
	 */
	protected $users;

	/**
	 * The application theme.
	 * @var Teepluss\Theme\Theme
	 */
	protected $theme;

	/**
	 * Create a new Install Controller.
	 *
	 * @param UserRepository $users
	 * @return InstallController
	 */
	public function __construct(UserRepositoryInterface $users)
	{
		$this->theme = Theme::uses('default')->layout('install');
		if (Config::get('nestor.installed') === true)
		{
			return App::abort(404, 'Page not found');
		}
		$this->users = $users;
		$this->theme->setTitle('Nestor QA | Install');
	}

	public function getIndex()
	{
		return $this->theme->scope('install.index')->render();
	}

	public function postIndex()
	{
		Log::info('Generating app key...');
		Artisan::call('key:generate', array('--env' => App::environment()));

 		$artisan = Artisan::call('migrate:refresh', array('--env' => App::environment(), '--seed'));

 		if ($artisan > 0)
 		{
 			return Redirect::back()
 				->withErrors(array('error' => 'Install Failed'))
 				->with('install_errors', true);
 		}

 		return Redirect::to('install/user');
	}

	public function getUser()
	{
		return $this->theme->scope('install.user')->render();
	}

	public function postUser()
	{
		Log::info('Validating user params...');
		$messages = $this->users->validForCreation(
				Input::get('first_name'),
				Input::get('last_name'),
				Input::get('email'),
				Input::get('password')
		);

		if (count($messages) > 0)
		{
			Log::info('Invalid params. Redirecting back...');
			return Redirect::back()
				->withInput()
				->withErrors($messages)
				->with('install_errors', true);
		}

		Log::info('Creating user...');
		$user = $this->users->create(
				Input::get('first_name'),
				Input::get('last_name'),
				Input::get('email'),
				1, // Force them as active
				Input::get('password')
		);

		return Redirect::to('install/config');
	}

	public function getConfig()
	{
		return $this->theme->scope('install.config')->render();
	}

	public function postConfig()
	{
		$this->setNestorConfig(Input::get('site_theme', 'Site Theme', 'default'));
		return $this->theme->scope('install.complete')->render();
	}

	protected function setNestorConfig($site_theme)
	{
		Setting::set('nestor.site_theme', $site_theme);
		Setting::set('nestor.installed', true);
		return true;
	}

}