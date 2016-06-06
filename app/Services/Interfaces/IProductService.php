<?php

namespace App\Services\Interfaces;

/**
 * Interface Repository
 *
 * Provides the standard functions to be expected of any repository.
 *
 * @package App\Services\Interfaces
 */
interface IProductService
{
    public function getAllProducts();

    public function getProductById($id);

    public function createProduct($data);

    public function updateProduct($data, $id);

    public function deleteProduct($id);
}