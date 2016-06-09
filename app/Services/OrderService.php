<?php

namespace App\Services;

use App\Order;
use App\Orderdetail;
use App\Product;
use App\Services\Interfaces\IOrderService;
use Session;

class OrderService implements IOrderService
{
    public function getAllOrders()
    {
        // TODO: Implement getAllOrders() method.
    }

    public function getOrderById($id)
    {
        // TODO: Implement getOrderById() method.
    }

    public function createOrder($data)
    {
        $order = new Order();
        $order->ShipAddress = $data;
        $order->BilAddress = $data;
        $order->PostalCode = $data;
        $order->City = $data;
        $order->State = $data;
        $order->Country = $data;
        $order->MobilePhone = $data;
        $order->Phone = $data;
        $order->ShippingMethod = $data;
        $order->Email = $data;
        $order->FullName = $data;
        $order->Price = $data;
        $order->save();

        $insertedId = $order->OrderID;

        foreach ($data->Products as $product) {
            $orderdetails = new Orderdetail();
            $orderdetails->OrderID = $insertedId;
            $orderdetails->ProductID = $product->ProductID;
            $orderdetails->Quantity = $product->Quantity;
            $orderdetails->save();
        }
    }

    public function updateOrder($data, $id)
    {
        // TODO: Implement updateOrder() method.
    }

    public function deleteOrder($id)
    {
        // TODO: Implement deleteOrder() method.
    }

    public function getSessionOrders()
    {
        $id = Session::get('login')['userid'];
        $order = Order::where(['UserID' => $id])->firstOrFail();
        $orderProducts = Orderdetail::where(['OrderID' => $order->OrderID])->get();

        return [
            'ShipAddress' => $order->ShipAddress,
            'BilAddress' => $order->BilAddress,
            'PostalCode' => $order->PostalCode,
            'City' => $order->City,
            'State' => $order->State,
            'Country' => $order->Country,
            'MobilePhone' => $order->MobilePhone,
            'Phone' => $order->Phone,
            'ShippingMethod' => $order->ShippingMethod,
            'Email' => $order->Email,
            'FullName' => $order->FullName,
            'Price' => $order->Price,
            'products' => $orderProducts
        ];
    }
}