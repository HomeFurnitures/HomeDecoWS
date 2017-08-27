<?php

namespace App\Services\Interfaces;


interface ICustomProductService
{
    function getAllProductParts();

    function getProductPartById($id);

    function createProductPart($data);

    function updateProductPart($data, $id);

    function deleteProductPart($id);

    function productPartRules();

    function updateRules();
}