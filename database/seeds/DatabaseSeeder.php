<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Nestor\Entities\ExecutionStatuses;
use Nestor\Entities\ExecutionTypes;
use Nestor\Entities\NavigationTreeNodeTypes;
use Nestor\Entities\ProjectStatuses;

/**
 * Main database seeder.
 *
 * @author Bruno P. Kinoshita
 * @since 0.12
 */
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
        
        // project_statuses
        
        DB::table('project_statuses')->delete();
        
        ProjectStatuses::create(array (
                'id' => 1,
                'name' => 'New',
                'description' => 'New Status'
        ));
        
        ProjectStatuses::create(array (
                'id' => 2,
                'name' => 'Closed',
                'description' => 'Closed Status'
        ));
        
        // execution_types
        
        DB::table('execution_types')->delete();
        
        ExecutionTypes::create(array (
                'id' => 1,
                'name' => 'Manual',
                'description' => 'Manual test'
        ));
        
        ExecutionTypes::create(array (
                'id' => 2,
                'name' => 'Automated',
                'description' => 'Automated test'
        ));
        
        // navigation_tree_node_types
        
        DB::table('navigation_tree_node_types')->delete();
        
        NavigationTreeNodeTypes::create(array (
                'id' => 1,
                'name' => 'Project',
                'description' => 'Project node'
        ));
        
        NavigationTreeNodeTypes::create(array (
                'id' => 2,
                'name' => 'Test Suite',
                'description' => 'Test Suite node'
        ));
        
        NavigationTreeNodeTypes::create(array (
                'id' => 3,
                'name' => 'Test Case',
                'description' => 'Test Case node'
        ));
        
        // execution_statuses
        
        DB::table('execution_statuses')->delete();
        
        ExecutionStatuses::create(array (
                'id' => 1,
                'name' => 'Not Run',
                'description' => 'A test case not run yet'
        ));
        
        ExecutionStatuses::create(array (
                'id' => 2,
                'name' => 'Passed',
                'description' => 'A test case that passed'
        ));
        
        ExecutionStatuses::create(array (
                'id' => 3,
                'name' => 'Failed',
                'description' => 'A test case that failed'
        ));
        
        ExecutionStatuses::create(array (
                'id' => 4,
                'name' => 'Blocked',
                'description' => 'A test case that is blocked'
        ));
        
        if (App::environment('dev', 'test', 'local')) {
            Log::info("Seeding DEVELOPMENT data");
            $this->call(SampleDatabaseSeeder::class);
        }
        
        Model::reguard();
    }
}
