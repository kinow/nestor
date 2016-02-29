<?php
use \DB;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Nestor\Entities\ProjectStatuses;
use Nestor\Entities\ExecutionTypes;
use Nestor\Entities\NavigationTreeNodeTypes;
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
        
        // project_statuses
        
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
        
        NavigationTreeNodeTypes::create(array (
                'id' => 1,
                'name' => 'Project',
                'Project node' 
        ));
        
        NavigationTreeNodeTypes::create(array (
                'id' => 2,
                'name' => 'Test Case',
                'Test Case node' 
        ));
        
        NavigationTreeNodeTypes::create(array (
                'id' => 3,
                'name' => 'Test Suite',
                'Test Suite node' 
        ));
        
        Model::reguard();
    }
}
