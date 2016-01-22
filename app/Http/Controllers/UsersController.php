<?php

namespace Nestor\Http\Controllers;

use \Validator;
use \Auth;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Dingo\Api\Exception\StoreResourceFailedException;

use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\UserRepository;

class UsersController extends Controller
{

    use AuthenticatesUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Creates a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function doSignUp(Request $request)
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
        
        Auth::loginUsingId($entity['id'], $request->has('remember'));
        
        return $entity;
    }
    
    /**
     * Checks if the request contains valid credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function doCheckLogin(Request $request)
    {
        $user = Auth::user();
    
        return $user;
    }

    /**
     * Logs out a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function doLogout(Request $request)
    {
        Auth::logout();
        return true;
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
