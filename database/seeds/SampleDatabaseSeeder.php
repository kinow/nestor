<?php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Nestor\Entities\ProjectStatuses;
use Nestor\Entities\User;
use Nestor\Repositories\ProjectsRepository;
use Nestor\Repositories\TestSuitesRepository;
use Nestor\Entities\NavigationTree;
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
                'description' => "# Project A\n\nThis is the **project A**",
                'created_by' => $user->id,
                'project_statuses_id' => ProjectStatuses::STATUS_NEW 
        ));
        
        $parentProjectNodeId = NavigationTree::projectId($projectA->id);
        
        $testSuiteA = $this->testSuitesRepository->createWithAncestor(array (
                'name' => 'Test Suite A',
                'description' => "# First test suiteA\n\nThis is the *very first* test suite!",
                'created_by' => $user->id,
                'project_id' => $projectA->id 
        ), $parentProjectNodeId);
        
        $testSuiteB = $this->testSuitesRepository->createWithAncestor(array (
                'name' => 'Test Suite B',
                'description' => "# Test suiteB\n\n* test\n* test 2",
                'created_by' => $user->id,
                'project_id' => $projectA->id
        ), $parentProjectNodeId);
        
        $parentTestSuiteNodeId = NavigationTree::testSuiteId($testSuiteB->id);
        
        $testSuiteD = $this->testSuitesRepository->createWithAncestor(array (
                'name' => 'Test Suite D',
                'description' => "# Test suite D",
                'created_by' => $user->id,
                'project_id' => $projectA->id
        ), $parentTestSuiteNodeId);
        
        
        $testSuiteC = $this->testSuitesRepository->createWithAncestor(array (
                'name' => 'Test Suite C',
                'description' => "# Test suiteC\n\nThis is the last of our test suites.",
                'created_by' => $user->id,
                'project_id' => $projectA->id
        ), $parentProjectNodeId);
        
        $projectB = $this->projectsRepository->create(array (
                'name' => 'Project B',
                'description' => "# Project B\n\nThis is the **project B**",
                'created_by' => $user->id,
                'project_statuses_id' => ProjectStatuses::STATUS_NEW
        ));
        
        $parentProjectNodeId = NavigationTree::projectId($projectB->id);
        
        $testSuiteA = $this->testSuitesRepository->createWithAncestor(array (
                'name' => 'Test Suite A',
                'description' => "# First test suiteA\n\nThis is the *very first* test suite!",
                'created_by' => $user->id,
                'project_id' => $projectB->id
        ), $parentProjectNodeId);
        
        Model::reguard();
    }
}
