<?php

namespace Nestor\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\ProjectsRepository;
use Validator;

/**
 * Project resource representation.
 *
 * @Resource("Projects", uri="/projects")
 */
class ProjectsController extends Controller
{
    
    /**
     *
     * @var ProjectsRepository $projectsRepository
     */
    protected $projectsRepository;
    
    /**
     *
     * @param ProjectsRepository $projectsRepository            
     */
    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;
    }
    
    /**
     * Show all users.
     *
     * @return array @Get("/")
     *         @Versions({"v1"})
     *         @Request({})
     *         @Response(200, body={"id": 1, "name": "project name", "url": "http://<host>:<port>/<path>", "description": "project description"})
     */
    public function index()
    {
        Log::debug("Returning paginated projects");
        return $this->projectsRepository->paginate();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: throw not implemented
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request            
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug("Saving a new project");
        $payload = $request->only('name', 'description', 'project_statuses_id', 'created_by');
        $validator = Validator::make($payload, [ 
                'name' => 'required|max:255|unique:projects',
                'description' => 'max:1000',
                'created_by' => 'required|integer|min:1' 
        ]);
        
        if ($validator->fails())
        {
            $this->throwValidationException($request, $validator);
        }
        
        $entity = $this->projectsRepository->create($payload);
        
        return $entity;
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id            
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->projectsRepository->find($id);
        // return [
        // 'id' => (int) $id,
        // 'name' => sprintf('Project %s', $id),
        // 'description' => sprintf('Le description du projet %s', $id)
        // ];
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id            
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request            
     * @param int $id            
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::debug("Updating an existing project");
        $payload = $request->only('name', 'description');
        $validator = Validator::make($payload, [ 
                'name' => 'required|max:255',
                'description' => 'max:1000' 
        ]);

        if ($validator->fails())
        {
            $this->throwValidationException($request, $validator);
        }

        $entity = $this->projectsRepository->update($payload, $id);
        return $entity;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id            
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return array (
                'Result' => $this->projectsRepository->delete($id) 
        );
    }
}
