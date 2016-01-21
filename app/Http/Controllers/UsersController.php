<?php

namespace Nestor\Http\Controllers;

use \Validator;
use \Auth;

use Illuminate\Http\Request;

use Dingo\Api\Exception\StoreResourceFailedException;

use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\UserRepository;

class UsersController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'
        ]);
        
        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
                    );
        }
        
        $payload = app('request')->only('username', 'name', 'email', 'password');
        
        $entity = $this->userRepository->create($request->all());
        
        Auth::login($entity);
        
        return $entity;
//         if ($validator->fails()) {
//             throw new StoreResourceFailedException('Could not create new user.', $validator->errors());
//         }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
