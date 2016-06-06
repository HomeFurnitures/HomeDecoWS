<?php
namespace App\Services;

use App\Services\Interfaces\IProductService;
use App\Product;

class ProductService implements IProductService
{
    public function getAllProducts()
    {
        $result = Product::all();
        return $result;
    }

    public function getProductById($id)
    {
        $result = Product::where(['ProductID' => $id])->get();
        return $result;
    }

    public function createProduct($data)
    {

    }

    public function updateProduct($data, $id)
    {

    }

    public function deleteProduct($id)
    {

    }
}