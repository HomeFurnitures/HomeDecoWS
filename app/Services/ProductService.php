<?php
namespace App\Services;

use App\Services\Interfaces\IProductService;
use App\Product;

class ProductService implements IProductService
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductById($id)
    {
        return Product::where(['ProductID' => $id])->get();
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
}