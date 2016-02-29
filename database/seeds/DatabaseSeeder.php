<?php
use \DB;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Nestor\Entities\ProjectStatuses;
use Nestor\Entities\ExecutionTypes;
use Nestor\Entities\NavigationTreeNodeTypes;
use Nestor\Entities\ExecutionStatuses;
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
                'name' => 'Test Case',
                'description' => 'Test Case node' 
        ));
        
        NavigationTreeNodeTypes::create(array (
                'id' => 3,
                'name' => 'Test Suite',
                'description' => 'Test Suite node' 
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
        
        Model::reguard();
    }
}
