<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUserService;

use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class UserController extends Controller
{
    protected $userService;
    protected $authService;

    /**
     * Initialize user service.
     * 
     * @param IUserService $userService
     * @param IAuthService $authService
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
        if ($request->getContent() == null) {
            $response = ['message' => Config::get('enum.nullRequest')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        if (!$this->authService->validJson($request->getContent())) {
            $response = ['message' => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->userService->userRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $this->userService->registerUser($data);
        $response = ['message' => Config::get('enum.successRegister')];
        return (new Response($response, 201))->header('Content-Type', 'json');
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
        //$data = json_decode($request->getContent());
        
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
     * Get logged user's details.
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
            $response = ['message' => Config::get('enum.notLogged')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }
    }
}
