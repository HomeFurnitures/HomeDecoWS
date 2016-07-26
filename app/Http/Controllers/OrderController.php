<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IOrderService;
use App\Http\Requests;

use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;


class OrderController extends Controller
{
    protected $orderService;
    protected $authService;

    /**
     * Initialize user service.
     */
    public function __construct(IOrderService $orderService, IAuthService $authService)
    {
        $this->orderService = $orderService;
        $this->authService = $authService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->getContent() == null) {
            $response = [Config::get('enum.message') => Config::get('enum.nullRequest')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->orderService->orderRules());
        if($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }
        
        foreach ($data['Products'] as $product) {
            $productValidator = Validator::make($product, $this->orderService->orderProductRules());
            if($productValidator->fails()) {
                return (new Response($productValidator->messages(), 400))->header('Content-Type', 'json');
            }
        }
        
        $this->orderService->createOrder($data);
        $response = [Config::get('enum.message') => Config::get('enum.successOrder')];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get logged user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserOrders(Request $request)
    {
        $token = $request->header('x-my-token');

        if (!$this->authService->checkLogin($token)) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $response = $this->orderService->getSessionOrders();
        return (new Response($response, 200))->header('Content-Type', 'json');
    }
}
