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
        $order->ShipAddress = $data['ShipAddress'];
        $order->BilAddress = $data['BilAddress'];
        $order->PostalCode = $data['PostalCode'];
        $order->City = $data['City'];
        $order->State = $data['State'];
        $order->Country = $data['Country'];
        $order->MobilePhone = $data['MobilePhone'];
        $order->Phone = $data['Phone'];
        $order->ShippingMethod = $data['ShippingMethod'];
        $order->Email = $data['Email'];
        $order->FullName = $data['FullName'];
        $order->Price = $data['Price'];
        $order->save();
        $thisOrderId = $order->OrderID;

        foreach ($data['Products'] as $product) {
            $orderdetails = new Orderdetail();
            $orderdetails->OrderID = $thisOrderId;
            $orderdetails->ProductID = $product['ProductID'];
            $orderdetails->Quantity = $product['Quantity'];
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

    // TODO fix for multi orders
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

    public function orderRules()
    {
        return [
            'UserID'            => 'integer|min:1',
            'ShipAddress'       => 'required|alpha_num_spaces|max:64',
            'BilAddress'        => 'required|alpha_num_spaces|max:64',
            'PostalCode'        => 'required|alpha_num|max:32',
            'City'              => 'required|alpha|max:85',
            'State'             => 'required|alpha|max:64',
            'Country'           => 'required|alpha|max:64',
            'MobilePhone'       => 'required|phone|min:10|max:20',
            'Phone'             => 'phone|min:10|max:20',
            'ShippingMethod'    => 'required|alpha|max:32',
            'Email'             => 'required|email|max:64',
            'FullName'          => 'required|alpha_spaces|max:128',
            'Price'             => 'required|numeric|min:0',
            'Products'          => 'required',
        ];
    }
    
    public function orderProductRules()
    {
        return [
            'ProductID' => 'required|integer|min:1',
            'Quantity'  => 'required|integer|min:1'
        ];
    }
}