<?php

namespace App\Services;


use App\Custom_product;
use App\Services\Interfaces\ICustomProductService;

class CustomProductService implements ICustomProductService
{

    function getAllProductParts()
    {
        return Custom_product::all();
    }

    function getProductPartById($id)
    {
        return Custom_product::find($id);
    }

    function createProductPart($data)
    {
        // TODO: Implement createProductPart() method.
    }

    function updateProductPart($data, $id)
    {
        // TODO: Implement updateProductPart() method.
    }

    function deleteProductPart($id)
    {
        // TODO: Implement deleteProductPart() method.
    }

    function productPartRules()
    {
        // TODO: Implement productPartRules() method.
    }

    function updateRules()
    {
        // TODO: Implement updateRules() method.
    }
}