<?php

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\Interfaces\IAuthService;

class LoginController extends Controller
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
     * Login user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logIn(Request $request)
    {
        $data = json_decode($request->getContent());

        if ($this->authService->checkUser($data)) {
            $token = $this->authService->createToken($data->Username);

            $response = ['x-my-token' => $token];
            return (new Response($response, 200))->header('Content-Type', 'json');
        } else {
            $response = ['message' => 'Something went wrong, probably bad credentials!'];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\Response
     */
    public function logOut()
    {
        $this->authService->destroySession();

        $response = ['message' => 'Logged out successfully'];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }
}