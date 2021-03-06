<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Bruno P. Kinoshita, Peter Florijn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Nestor\Http\Controllers;

use \Auth;
use \Validator;
use \Log;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Dingo\Api\Routing\Helpers;

use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\UsersRepository;

class UsersController extends Controller
{
    use AuthenticatesUsers, Helpers;

    /**
     * The field used as username to authenticate the user.
     *
     * @var string
     */
    protected $username = 'username';

    /**
     * To where redirect users once they have logged in.
     *
     * @var string
     */
    protected $redirectTo = '/#/projects';

    /**
     * @var UsersRepository
     */
    protected $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * Creates a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doSignUp(Request $request)
    {
        $payload = $request->only('username', 'name', 'email', 'password');
        $validator = Validator::make($payload, [
                'username' => 'required|max:50|unique:users',
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|min:6'
        ]);
        
        if ($validator->fails()) {
            Log::debug('Validation errors: ' . $validator->errors());
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new user.', $validator->errors());
        }
        
        $payload['password'] = bcrypt($payload['password']);
        $payload['username'] = strtolower($payload['username']);

        $entity = $this->usersRepository->create($payload);
        
        Auth::loginUsingId($entity['id'], $request->has('remember'));
        
        return response()->json($entity);
    }
    
    /**
     * Checks if the request contains valid credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doCheckLogin(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }

    /**
     * Logs out a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doLogout(Request $request)
    {
        Auth::logout();
        return response()->json(['success' => 'User successfully logged out.']);
    }

    /**
     * Logs in a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            Log::error($validator->errors()->all());
            $this->throwValidationException($request, $validator);
        }
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            Log::error('Too many login attempts for user!');
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        
        $user = Auth::user();
        if ($user) {
            return response()->json($user);
        }
        return $this->response->error('Invalid username or password.', 401);
    }

    /**
     * Called once a user successfully logs in to the system.
     *
     * @param Request $request HTTP request
     * @param \Nestor\Entities\User $user DB user returned
     */
    protected function authenticated(Request $request, \Nestor\Entities\User $user)
    {
        if ($user) {
            return $user->toArray();
        }
        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Log::debug("Updating a user");
        // Update password in another method
        $payload = $request->only('username', 'name', 'email');
        $user = Auth::user();
        $userId = $user['id'];
        $validator = Validator::make($payload, [
            'username' => 'required|max:50|unique:users,'.$userId,
            'name' => 'required|max:255',
            'email' => 'required|email|max:255'
        ]);
        
        if ($validator->fails()) {
            Log::debug('Validation errors: ' . $validator->errors());
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not update user.', $validator->errors());
        }
        
        $payload['username'] = strtolower($payload['username']);

        $entity = $this->usersRepository->update($payload, $userId);
        
        return response()->json($entity);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }
}
