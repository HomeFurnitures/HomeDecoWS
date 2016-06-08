<?php

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Services\Interfaces\IProductService;

class ProductController extends Controller
{
    protected $productService;

    /**
     * Initialize product service.
     */
    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all the products from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$result = DB::select('select * from products');
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
        //TODO store product in db
        if (Session::get('login')) {
            $session = Session::get('login');
            if ($request->header('x-my-token') == $session['token']) {
                echo("Product created [*__*] UserID = " . $session['userid']);
            } else {
                echo 'token mismatch';
            }
        } else {
            echo 'unothorized';
        }
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
        //TODO
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //TODO
    }
}
