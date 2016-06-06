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
     * GET request
     *
     * Gets all the products from database.
     *
     * @return Http response with all the products
     */
    public function index()
    {
        //$result = DB::select('select * from products');
        $result = $this->productService->getAllProducts();
        return (new Response($result, 200))->header('Content-Type', 'json');
    }

    /**
     * GET request
     *
     * Gets a songle product from database.
     *
     * @param  int $id
     * @return Http response with a single product
     */
    public function show($id)
    {
        $result = $this->productService->getProductById($id);
        return (new Response($result, 200))->header('Content-Type', 'json');
    }

    /**
     * POST request
     *
     * Store a newly created product in database.
     *
     * @param  $request with product details
     * @return Http response
     */
    public function store(Request $request) //TODO
    {
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
     * PUT/PATCH request
     *
     * Update the specified product in database.
     *
     * @param  $request with data to update
     * @param  int $id
     * @return Http Response
     */
    public function update(Request $request, $id)
    {
        //TODO
    }

    /**
     * DELETE request
     *
     * Remove the specified product from database.
     *
     * @param  int $id
     * @return Http Response
     */
    public function destroy($id)
    {
        //TODO
    }
}
