<?php

namespace App\Services\Interfaces;


interface ICustomOrderService
{
    function getAllOrders();

    function createOrder($data);

    function deleteOrder($id);

    function getOrdersByUserId($id);

    function orderRules();
}