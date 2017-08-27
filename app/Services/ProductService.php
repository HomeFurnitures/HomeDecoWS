<?php
namespace App\Services;

use App\Services\Interfaces\IProductService;
use App\Product;
use DB;

class ProductService implements IProductService
{
    public function getAllProducts()
    {
        //return Product::all();

        $products = DB::table('products')
            ->join('categories', 'products.CategoryID', '=', 'categories.CatID')
            ->select('products.*', 'categories.MainCategory', 'categories.SubCategory')
            ->get();
        return $products;
    }

    public function getProductById($id)
    {
        $product = DB::table('products')
            ->where('ProductID', '=', $id)
            ->join('categories', 'products.CategoryID', '=', 'categories.CatID')
            ->select('products.*', 'categories.MainCategory', 'categories.SubCategory')
            ->get();
        return $product;
    }

    public function createProduct($data)
    {
        //TODO
    }

    public function updateProduct($data, $id)
    {
        //TODO
    }

    public function deleteProduct($id)
    {
        Product::destroy($id);
    }

    public function productRules()
    {
        // TODO: Implement productRules() method.
    }

    public function productUpdateRules()
    {
        // TODO: Implement productUpdateRules() method.
    }
}