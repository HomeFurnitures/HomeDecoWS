<?php 

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\AuthService;
use App\Services\Interfaces\IAuthService;
use App\User;
// $value = Request::header('x-my-token'); TODO

class MyAuthController extends Controller 
{
	protected $authService;

    /**
     * Initialize auth service.
     */
    public function __construct(IAuthService $authService) 
    {
        $this->authService = $authService;
    }

    /**
     * Post request
     *
     * Validate and login user
     *
     * @param  $request with user credentials
     * @return Http response     
     */
	public function logIn(Request $request) 
	{
		$data = json_decode($request->getContent());

		if ($this->authService->checkUser($data)) 
		{
			$token = $this->authService->createToken($data->Username);

			$response = ['x-my-token' => $token];
        	return (new Response($response, 200))->header('Content-Type', 'json'); 
		} 
		else 
		{
			$response = ['message' => 'Wrong username or password!'];
        	return (new Response($response, 401))->header('Content-Type', 'json');
		}
	}

    /**
     * Post request
     *
     * Validate and login user
     *
     * @return Http response     
     */
	public function logOut() 
	{
		Session::forget('login');

		$response = ['message' => 'Logged out successfully'];
		return (new Response($response, 200))->header('Content-Type', 'json'); 
	}

    /**
     * Post request
     *
     * Validate and login user
     *
     * @param  $request with user details
     * @return Http response     
     */
	public function register(Request $request)
	{
		$data = json_decode($request->getContent());

		// check if request data is valid
		if (!$this->authService->checkData($data)) 
		{
			$response = ['message' => 'Invalid request data!'];
        	return (new Response($response, 400))->header('Content-Type', 'json'); 			
		}

		// check if username exists
		if ($this->authService->checkUsername($data->Username)) 
		{
			$response = ['message' => 'This username already exists!'];
        	return (new Response($response, 400))->header('Content-Type', 'json'); 
		}

		// check if password exists
		if ($this->authService->checkEmail($data->Email)) 
		{
			$response = ['message' => 'This e-mail already exists!'];
        	return (new Response($response, 400))->header('Content-Type', 'json'); 
		}

		// validate the user details and register
		if ($this->authService->validateUserData($data))
		{
			$this->authService->registerUser($data);

			$response = ['message' => 'Registration completed successfully!'];
	    	return (new Response($response, 201))->header('Content-Type', 'json'); 
		}
		else
		{
			$response = ['message' => 'Invalid user details!'];
	    	return (new Response($response, 400))->header('Content-Type', 'json'); 
		}				
	}
}