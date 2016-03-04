<?php
use \DB;
use \Hash;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Nestor\Entities\ProjectStatuses;
use Nestor\Entities\User;
use Nestor\Repositories\ProjectsRepository;

class SampleDatabaseSeeder extends Seeder
{
    
    protected $projectsRepository;
    
    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;        
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        DB::table('users')->delete();
        
        $user = User::create(array(
                'username' => 'bruno1',
                'password' => Hash::make('bruno1'),
                'email' => 'bruno1@example.com',
                'name' => 'Bruno P. Kinoshita'
        ));
        
        DB::table('projects')->delete();
        
        $project = $this->projectsRepository->create(array(
                'name' => 'Project A',
                'description' => '# Project A\n\nThis is the **project A**',
                'created_by' => $user->id,
                'project_statuses_id' => ProjectStatuses::STATUS_NEW
        ));
        
        DB::table('test_suites')->delete();
        
        Model::reguard();
    }
}
