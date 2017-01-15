<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Config;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\Interfaces\IAuthService;
use Validator;

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
         if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->authService->loginRules());
        if ($validator->fails()) {
            $response = [Config::get('enum.message') => Config::get('enum.failLogIn')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!Auth::attempt($data)) {
            $response = [Config::get('enum.message') => Config::get('enum.failLogIn')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.successLogIn')];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\Response
     */
    public function logOut()
    {
        if(Auth::check())
            $response = Auth::user();
        else
            $response = ["msg" => "not logged :@@@@"];

        return (new Response($response, 200))->header('Content-Type', 'json');
       //Auth::logout();
    }
}