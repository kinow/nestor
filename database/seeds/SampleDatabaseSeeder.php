<?php
use \DB;
use \Hash;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Nestor\Entities\ProjectStatuses;
use Nestor\Entities\User;
use Nestor\Repositories\ProjectsRepository;
use Nestor\Repositories\TestSuitesRepository;
class SampleDatabaseSeeder extends Seeder
{
    protected $projectsRepository;
    protected $testSuitesRepository;
    public function __construct(ProjectsRepository $projectsRepository, TestSuitesRepository $testSuitesRepository)
    {
        $this->projectsRepository = $projectsRepository;
        $this->testSuitesRepository = $testSuitesRepository;
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        DB::table('navigation_tree')->delete();
        DB::statement("delete from sqlite_sequence where name='navigation_tree'");
        DB::table('test_suites')->delete();
        DB::statement("delete from sqlite_sequence where name='test_suites'");
        DB::table('projects')->delete();
        DB::statement("delete from sqlite_sequence where name='projects'");
        DB::table('users')->delete();
        DB::statement("delete from sqlite_sequence where name='users'");
        
        $user = User::create(array (
                'username' => 'bruno1',
                'password' => Hash::make('bruno1'),
                'email' => 'bruno1@example.com',
                'name' => 'Bruno P. Kinoshita' 
        ));
        
        $projectA = $this->projectsRepository->create(array (
                'name' => 'Project A',
                'description' => '# Project A\n\nThis is the **project A**',
                'created_by' => $user->id,
                'project_statuses_id' => ProjectStatuses::STATUS_NEW 
        ));
        
        $testSuiteA = $this->testSuitesRepository->create(array (
                'name' => 'Test Suite A',
                'description' => '# First test suiteA\n\nThis is the *very first* test suite!',
                'created_by' => $user->id,
                'project_id' => $projectA->id 
        ));
        
        $testSuiteB = $this->testSuitesRepository->create(array (
                'name' => 'Test Suite B',
                'description' => '# Test suiteB\n\n* test\n* test 2',
                'created_by' => $user->id,
                'project_id' => $projectA->id
        ));
        
        $testSuiteC = $this->testSuitesRepository->create(array (
                'name' => 'Test Suite C',
                'description' => '# Test suiteC\n\nThis is the last of our test suites.',
                'created_by' => $user->id,
                'project_id' => $projectA->id
        ));
        
        Model::reguard();
    }
}
