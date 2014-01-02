<?php

use Nestor\Repositories\UserRepository;
use Illuminate\Support\Str; // workaround

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
	public function __construct(UserRepository $users)
	{
		$this->theme = Theme::uses('default')->layout('install');
		$this->app = App::getFacadeRoot();
		if (isset(Setting::get('nestor')['installed']) && Setting::get('nestor')['installed'] === true)
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
		Log::info('Re creating database');
		$app = App::getFacadeRoot();
		$db = $app['config']['database.default'];
		// Create sqlite file if it does not exit
		if ('sqlite' === $db)
		{
			$databaseFile = $app['config']['database.connections'][$db]['database'];
			file_put_contents($databaseFile, '');
		}
		
		Log::info('Generating app key...');
		// FIXME: due to error in Artisan::call('key:generate', array('--env' => App::environment()));
		list($path, $contents) = $this->getKeyFile(App::environment());
		$key = Str::random(32);
		$contents = str_replace($this->app['config']['app.key'], $key, $contents);
		file_put_contents($path, $contents);
		$this->app['config']['app.key'] = $key;
		Log::info("Application key [$key] set successfully.");
		
		$artisan = Artisan::call('migrate:install', array('--env' => App::environment()));
		$artisan = Artisan::call('migrate:refresh', array('--env' => App::environment()));
		
 		$artisan = Artisan::call('db:seed', array('--env' => App::environment(), '--seed'));

 		if ($artisan > 0)
 		{
 			return Redirect::back()
 				->withErrors(array('error' => 'Install Failed'))
 				->with('install_errors', true);
 		}

 		return Redirect::to('install/user');
	}
	
	/**
	 * FIXME: workaround
	 * Get the key file and contents.
	 *
	 * @return array
	 */
	protected function getKeyFile($env = '')
	{
		if ('/' !== substr($env, -strlen($env)))
		{
			$env = $env.'/';
		}
		$contents = file_get_contents($path = $this->app['path']."/config/{$env}app.php");
		return array($path, $contents);
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