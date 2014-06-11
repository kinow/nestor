<?php

use Nestor\Repositories\UserRepository;

/**
 * Sample data seeder.
 */
class SampleDataSeeder extends Seeder {

	protected $users = NULL;

	public function __construct(Nestor\Repositories\UserRepository $users)
	{
		$this->users = $users;
	}

	public function run()
	{
		DB::table('users')->delete();

		$this->users->create('Bruno', 'Kinoshita', 'brunodepaulak@yahoo.com.br', 1, 'brunobruno');
		
	}

}