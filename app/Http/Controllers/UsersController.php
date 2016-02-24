<?php

namespace Nestor\Http\Controllers;

use \Auth;
use \Validator;

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
                'username' => 'required|max:50',
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6' 
        ]);
        
        if ($validator->fails())
        {
            $this->throwValidationException($request, $validator);
        }
        
        $payload['password'] = bcrypt($payload['password']);
        
        $entity = $this->usersRepository->create($payload);
        
        Auth::loginUsingId($entity['id'], $request->has('remember'));
        
        return $entity;
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
        return $user;
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
        return $this->response->array(array('success' => 'User successfully logged out.'));
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
        
        if ($validator->fails())
        {
            $this->throwValidationException($request, $validator);
        }
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
        
        $credentials = $this->getCredentials($request);
        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
        
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }
        
        $user = Auth::user();
        if ($user)
            return $user;
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
        if ($user)
            return $user;
        return NULL;
    }

}