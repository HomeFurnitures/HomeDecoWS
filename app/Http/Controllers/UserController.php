<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUserService;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $userService;
    protected $authService;

    /**
     * Initialize user service.
     */
    public function __construct(IUserService $userService, IAuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Get all the users from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Register User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent());

        // check if username exists
        if ($this->userService->checkUsername($data->Username)) {
            $response = ['message' => 'This username already exists!'];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        // check if password exists
        if ($this->userService->checkEmail($data->Email)) {
            $response = ['message' => 'This e-mail already exists!'];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        // validate the user details and register // TODO
        if ($this->userService->validateUser($data)) {
            $this->userService->registerUser($data);
            $response = ['message' => 'Registration completed successfully!'];
            return (new Response($response, 201))->header('Content-Type', 'json');
        } else {
            $response = ['message' => 'Invalid user details!'];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }
    }

    /**
     * Get the specified User.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified User in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());
        //
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get logged user's details
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getThisUser(Request $request)
    {
        $token = $request->header('x-my-token');

        if ($this->authService->checkLogin($token)) {
            $response = $this->userService->getSessionUser();

            return (new Response($response, 200))->header('Content-Type', 'json');
        } else {
            $response = ['message' => 'You are not logged in!'];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }
    }
}
