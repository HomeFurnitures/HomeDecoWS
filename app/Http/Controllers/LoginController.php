<?php

namespace App\Http\Controllers;

use Config;

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
        if ($request->getContent() == null) {
            $response = [Config::get('enum.message') => Config::get('enum.nullRequest')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent());
        if ($this->authService->checkUser($data)) {
            $token = $this->authService->createToken($data->Username);
            $response = [Config::get('enum.token') => $token];
            return (new Response($response, 200))->header('Content-Type', 'json');
        } 
        
        $response = [Config::get('enum.message') => Config::get('enum.failLogIn')];
        return (new Response($response, 401))->header('Content-Type', 'json');        
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\Response
     */
    public function logOut()
    {
        if($this->authService->destroySession()) {
            $response = [Config::get('enum.message') => Config::get('enum.successLogOut')];
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.failLogOut')];
        return (new Response($response, 401))->header('Content-Type', 'json');
    }
}