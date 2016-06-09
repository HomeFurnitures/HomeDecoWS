<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IOrderService;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


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
        $data = json_decode($request->getContent());
        $this->orderService->createOrder($data);
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

        if ($this->authService->checkLogin($token)) {
            $response = $this->orderService->getSessionOrders();

            return (new Response($response, 200))->header('Content-Type', 'json');
        } else {
            $response = ['message' => 'You are not logged in!'];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }
    }
}
