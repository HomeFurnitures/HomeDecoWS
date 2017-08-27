<?php

namespace App\Http\Controllers;

use Auth;
use Config;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        // Separate login for android
        $android = $request->header('android');
        if (isset($android)) {
            $androidToken = uniqid("", false);
            $this->authService->androidLogin(Auth::user()->id, $androidToken);
            $response = [Config::get('enum.token') => $androidToken];
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.successLogIn')];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\Response
     */
    public function logOut(Request $request)
    {
        $response = [Config::get('enum.message') => Config::get('enum.successLogOut')];

        // Android separate log out
        $android = $request->header('android');
        $androidToken = $request->header('android-token');
        if (isset($android) && $this->authService->checkAndroidAuth($androidToken)) {
            $id = $this->authService->getAndroidUserId($androidToken);
            $this->authService->androidLogOut($id);
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        if (Auth::check()) {
            Auth::logout();
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.failLogOut')];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Check if user is logged in
     *
     * @return \Illuminate\Http\Response
     */
    public function checkLogIn()
    {
        if (Auth::check()) {
            $response = [Config::get('enum.message') => true];
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => false];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }
}