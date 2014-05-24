<?php

use \DB;
use \Config;

class ConfigurationSeeder extends Seeder {

	public function run()
	{
		DB::table('settings')->delete();

		$settings = Config::get('settings');

		$settings['editor'] = json_encode("Nestor\\Model\\TextareaEditor");

		$settings['security_enabled'] = json_encode(NULL);
		$settings['security_provider'] = json_encode(NULL);
	}

}