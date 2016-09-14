<?php

namespace App\Services\Interfaces;

interface IProductService
{
    public function productRules();

    public function productUpdateRules();
    
    public function getAllProducts();

    public function getProductById($id);

    public function createProduct($data);

    public function updateProduct($data, $id);

    public function deleteProduct($id);
}