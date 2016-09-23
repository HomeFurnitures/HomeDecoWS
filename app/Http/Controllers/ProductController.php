<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\Interfaces\IProductService;
use App\Services\Interfaces\IAuthService;
use Config;
use Validator;

class ProductController extends Controller
{
    protected $productService;
    protected $authService;

    /**
     * Initialize product service.
     */
    public function __construct(IProductService $productService, IAuthService $authService)
    {
        $this->productService = $productService;
        $this->authService = $authService;
    }

    /**
     * Get all the products from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->productService->getAllProducts();
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Get the specified product from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'exists:products,ProductID']);
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }
        $response = $this->productService->getProductById($id);
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
        
        $token = $request->header('x-my-token');
        if (!$this->authService->checkLogin($token)) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }
        
        if (!$this->authService->checkAdmin()) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->productService->productRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }
        
        $this->productService->createProduct($data);
        $response = [Config::get('enum.message') => Config::get('enum.successProduct')];
        return (new Response($response, 201))->header('Content-Type', 'json');
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->getContent() == null) {
            $response = [Config::get('enum.message') => Config::get('enum.nullRequest')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        if (!$this->authService->validJson($request->getContent())) {
            $response = [Config::get('enum.message') => Config::get('enum.invalidJson')];
            return (new Response($response, 400))->header('Content-Type', 'json');
        }

        $token = $request->header('x-my-token');
        if (!$this->authService->checkLogin($token)) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!$this->authService->checkAdmin()) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, $this->productService->productUpdateRules());
        if ($validator->fails()) {
            return (new Response($validator->messages(), 400))->header('Content-Type', 'json');
        }

        $this->productService->updateProduct($data, $id);
        $response = [Config::get('enum.message') => Config::get('enum.successProduct')];
        return (new Response($response, 200))->header('Content-Type', 'json');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $token = $request->header('x-my-token');
        if (!$this->authService->checkLogin($token)) {
            $response = [Config::get('enum.message') => Config::get('enum.notLogged')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }

        if (!$this->authService->checkAdmin()) {
            $response = [Config::get('enum.message') => Config::get('enum.notAdmin')];
            return (new Response($response, 401))->header('Content-Type', 'json');
        }
        
        $this->productService->deleteProduct($id);
        $response = [Config::get('enum.message') => Config::get('enum.successProduct')];
        return (new Response($response, 200))->header('Content-Type', 'json');    }
}
