<?php

namespace App\Http\Controllers;

use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IMessageService;

class MessageController extends Controller
{
    protected $messageService;
    protected $authService;

    /**
     * Initialize message service.
     */
    public function __construct(IMessageService $messageService, IAuthService $authService)
    {
        $this->messageService = $messageService;
        $this->authService = $authService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Separate android call
        $android = $request->header('android');
        $androidToken = $request->header('android-token');
        if (isset($android) && $this->authService->checkAndroidAuth($androidToken)) {
            $id = $this->authService->getAndroidUserId($androidToken);
            $response = $this->messageService->getMessages($id);
            return (new Response($response, 201))->header('Content-Type', 'json');
        }

        if (Auth::check()) {
            $response = $this->messageService->getMessages(Auth::user()->id);
            return (new Response($response, 201))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
        return (new Response($response, 401))->header('Content-Type', 'json');
    }


    /**
     * Store a newly created resource in storage.
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

        // Separate android call
        $android = $request->header('android');
        $androidToken = $request->header('android-token');
        if (isset($android) && $this->authService->checkAndroidAuth($androidToken)) {
            $id = $this->authService->getAndroidUserId($androidToken);
            $this->messageService->createMessage($id, true, $data);
            $response = [Config::get('enum.message') => Config::get('enum.successMessage')];
            return (new Response($response, 201))->header('Content-Type', 'json');
        }

        if (Auth::check()) {
            $id = Auth::user()->id;
            $isUser = !(Auth::user()->type == 'admin');
            $this->messageService->createMessage($id, $isUser, $data);
            $response = [Config::get('enum.message') => Config::get('enum.successMessage')];
            return (new Response($response, 201))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
        return (new Response($response, 401))->header('Content-Type', 'json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
