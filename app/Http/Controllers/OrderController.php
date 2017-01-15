<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IOrderService;
use App\Http\Requests;

use Auth;
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
     * Display a listing of the orders.
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

        $response = $this->orderService->getAllOrders();
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Store a newly created order in storage.
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
        return (new Response($response, 201))->header('Content-Type', 'json');
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
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

        $validator = Validator::make(['id' => $id], ['id' => 'exists:orders,OrderID']);
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $response = $this->orderService->getOrderById($id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return (new Response('', 501))->header('Content-Type', 'json');
    }

    /**
     * Remove the specified order from storage.
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

        $response = $this->orderService->deleteOrder($id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Get logged user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserOrders()
    {
        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $response = $this->orderService->getOrdersByUserId(Auth::user()->id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }
}