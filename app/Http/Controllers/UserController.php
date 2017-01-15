<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUserService;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Config;
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
        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!(Auth::user()->type == 'admin')) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $response = $this->userService->getAllUsers();
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Register User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->userService->userRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $this->userService->registerUser($data);
        $response = [Config::get('enum.message') => Config::get('enum.successRegister')];
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
        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!(Auth::user()->type == 'admin')) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $validator = Validator::make(['id' => $id], ['id' => 'exists:users,UserID']);
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $response = $this->userService->getUserById($id);
        return (new Response($response, 200))->header('Content-Type', 'json');
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
        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!(Auth::user()->type == 'admin')) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->userService->userUpdateRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }
        
        $response = $this->userService->updateUser($data, $id);
        return (new Response($response, 200))->header('Content-Type', 'json');        
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!(Auth::user()->type == 'admin')) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $response = $this->userService->deleteUser($id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Get logged user's details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getThisUser()
    {
        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }
        
        $response = $this->userService->getLoggedUser(Auth::user());
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Update logged user's details.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateThisUser(Request $request)
    {
        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }
        
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->userService->userUpdateRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $response = $this->userService->updateUser($data, Auth::user()->id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }
}