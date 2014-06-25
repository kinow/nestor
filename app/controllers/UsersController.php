<?php

use Nestor\Repositories\UserRepository;

class UsersController extends \BaseController {

	protected $users;

	public function __construct(Nestor\Repositories\UserRepository $users)
	{
		parent::__construct();
		$this->users = $users;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage/'))->
			add('Manage Users');
		$args = array();
		$args['users'] = $this->users->paginate(10);
		return $this->theme->scope('user.index', $args)->render();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage/'))->
			add('Manage Users', URL::to('/users'))->
			add('Create User');
		return $this->theme->scope('user.create')->render();
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$user = null;
		Log::info('Creating user...');
		$pdo = null;

		//$hash = password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 10));
		try {
    		$pdo = DB::connection()->getPdo();
    		$pdo->beginTransaction();
			$user = $this->users->create(
				Input::get('first_name'),
				Input::get('last_name'),
				Input::get('email'),
				1, 
				Input::get('password')
			);
			if ($user)
			{
				Log::debug('Comitting transaction');
				$pdo->commit();

				return Redirect::to('/users/')
					->with('success', sprintf('User %s created', Input::get('first_name')));
			}
			else 
			{
				return Redirect::to('/users/create')
					->withInput()
					->withErrors($user->errors());
			}
		} catch (\Exception $e) {
			if (!is_null($pdo))
				try {
					Log::warning('Rolling back transaction: ' . $e->getMessage());
					$pdo->rollBack();
				} catch (Exception $ignoreme) {}
			return Redirect::to('/users/create')
	 			->withInput();
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = $this->users->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage/'))->
			add('Manage Users', URL::to('/users'))->
			add(sprintf('%s %s', $user->first_name, $user->last_name));
		$args = array();
		$args['user'] = $user;
		return $this->theme->scope('user.show', $args)->render();
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = $this->users->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage/'))->
			add('Manage Users', URL::to('/users'))->
			add(sprintf('%s %s', $user->first_name, $user->last_name));
		$args = array();
		$args['user'] = $user;
		return $this->theme->scope('user.edit', $args)->render();
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = null;
		Log::info('Updating user...');
		$pdo = null;
		try {
    		$pdo = DB::connection()->getPdo();
    		$pdo->beginTransaction();
			$user = $this->users->update(
				$id,
				Input::get('first_name'),
				Input::get('last_name'),
				Input::get('email'),
				1, 
				Input::get('password')
			);
			if ($user->isValid() && $user->isSaved())
			{
				Log::debug('Comitting transaction');
				$pdo->commit();
			}
		} catch (\Exception $e) {
			if (!is_null($pdo))
				try {
					Log::warning('Rolling back transaction');
					$pdo->rollBack();
				} catch (Exception $ignoreme) {}
			return Redirect::to('/users/create')
	 			->withInput();
		}
		if ($user->isSaved())
		{
			return Redirect::route('users.show', $id)
				->with('success', sprintf('User %s updated', Input::get('first_name')));
		} else {
			return Redirect::route('users.edit', $id)
				->withInput()
				->withErrors($user->errors());
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = null;
		Log::info('Deactivating user...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$user = $this->users->delete($id);
			$pdo->commit();
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/users/')
				->withInput();
		}

		return Redirect::route('users.index')
			->with('success', 'User deleted');
	}

	public function getLogin()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Log in');
		return $this->theme->scope('user.login')->render();
	}

	public function postLogin()
	{
		//if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')), Input::get('remember')))
		if ($this->users->login(Input::get('email'), Input::get('password'), Input::get('remember')))
		{
			Log::info(sprintf('User %s logged in', Input::get('email')));
		    return Redirect::intended('/');
		}
		Log::warning(sprintf('Invalid log in attempt from %s', Input::get('email')));
		return Redirect::to('/users/login')
			->with('error', 'Invalid credentials');
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::to('/')
			->with('success', 'You have logged out');
	}

}
