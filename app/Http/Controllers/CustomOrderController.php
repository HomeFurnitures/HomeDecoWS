<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\ICustomOrderService;

use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class CustomOrderController extends Controller
{
    protected $customOrderService;
    protected $authService;

    /**
     * Initialize services.
     */
    public function __construct(ICustomOrderService $customOrderService, IAuthService $authService)
    {
        $this->customOrderService = $customOrderService;
        $this->authService = $authService;
    }

    /**
     * Display a listing of the resource.
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

        $response = $this->customOrderService->getAllOrders();
        return (new Response($response, 200))->header('Content-Type', 'json');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->customOrderService->orderRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        //TODO productpart rules
        /*foreach ($data['Products'] as $product) {
            $productValidator = Validator::make($product, $this->orderService->orderProductPartRules());
            if ($productValidator->fails()) {
                return (new Response($productValidator->messages(), 400))->header('Content-Type', 'json');
            }
        }*/

        $android = $request->header('android');
        $androidToken = $request->header('android-token');
        if (isset($android) && $this->authService->checkAndroidAuth($androidToken)) {
            $data['UserID'] = $this->authService->getAndroidUserId($androidToken);
        } else if (Auth::check()) {
            $data['UserID'] = Auth::user()->id;
        } else {
            $data['UserID'] = null;
        }

        $this->customOrderService->createOrder($data);
        $response = [Config::get('enum.message') => Config::get('enum.successOrder')];
        return (new Response($response, 201))->header('Content-Type', 'json');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //TODO admin
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //TODO admin
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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

        $response = $this->customOrderService->deleteOrder($id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Get logged user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserOrders(Request $request)
    {
        $android = $request->header('android');
        $androidToken = $request->header('android-token');
        if (isset($android) && $this->authService->checkAndroidAuth($androidToken)) {
            $userId = $this->authService->getAndroidUserId($androidToken);
            $response = $this->customOrderService->getOrdersByUserId($userId);
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        if (Auth::check()) {
            $response = $this->customOrderService->getOrdersByUserId(Auth::user()->id);
            return (new Response($response, 200))->header('Content-Type', 'json');
        }

        $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
        return (new Response($response, 401))->header('Content-Type', 'json');
    }
}
