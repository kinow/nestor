<?php

use \DB;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Nestor\Entities\ProjectStatuses;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        
        DB::table('project_statuses')->delete();
        
        ProjectStatuses::create(array(
            'id' => 1,
            'name' => 'New',
            'description' => 'New Status'
        ));
        
        ProjectStatuses::create(array(
            'id' => 2,
            'name' => 'Closed',
            'description' => 'Closed Status'
        ));

        Model::reguard();
    }
}
