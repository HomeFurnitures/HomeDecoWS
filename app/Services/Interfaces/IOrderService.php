<?php

namespace App\Services\Interfaces;


interface IOrderService
{
    public function getAllOrders();

    public function getOrderById($id);
    
    public function createOrder($data);

    public function updateOrder($data, $id);

    public function deleteOrder($id);

    public function getSessionOrders();
}