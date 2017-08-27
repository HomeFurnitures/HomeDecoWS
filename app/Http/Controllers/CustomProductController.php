<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\Interfaces\ICustomProductService;
use App\Services\Interfaces\IAuthService;
use Config;
use Validator;

class CustomProductController extends Controller
{
    protected $customProductService;
    protected $authService;

    /**
     * Initialize services.
     */
    public function __construct(ICustomProductService $customProductService, IAuthService $authService)
    {
        $this->customProductService = $customProductService;
        $this->authService = $authService;
    }

    /**
     * Get all the product parts from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->customProductService->getAllProductParts();
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
        $validator = Validator::make(['id' => $id], ['id' => 'exists:custom_products,id']);
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $response = $this->customProductService->getProductPartById($id); //TODO id or catID
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Store a new product parts in storage.
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

        if (!Auth::check()) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!(Auth::user()->type == 'admin')) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->customProductService->productPartRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $this->customProductService->createProductPart($data);
        $response = [Config::get('enum.message') => Config::get('enum.successProductPart')];
        return (new Response($response, 201))->header('Content-Type', 'json');
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
        $validator = Validator::make($data, $this->customProductService->updateRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $this->customProductService->updateProductPart($data, $id);
        $response = [Config::get('enum.message') => Config::get('enum.successProduct')];
        return (new Response($response, 200))->header('Content-Type', 'json');
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

        $this->customProductService->deleteProductPart($id);
        $response = [Config::get('enum.message') => Config::get('enum.successProduct')];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }
}
