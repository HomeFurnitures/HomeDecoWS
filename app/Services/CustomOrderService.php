<?php

namespace App\Services;

use App\Custom_order;
use App\Order;
use App\Services\Interfaces\ICustomOrderService;

class CustomOrderService implements ICustomOrderService
{

    function getAllOrders()
    {
        $orders = Order::all()->toArray();
        $fullOrders = [];
        foreach ($orders as $order) {
            if(Custom_order::where(['OrderID' => $order->OrderID])->first()) {
                $orderProducts = Custom_order::where(['OrderID' => $order->OrderID])->get()->toArray();
                array_add($order, 'products', $orderProducts);
                array_push($fullOrders, $order);
            }
        }
        return $fullOrders;
    }

    function createOrder($data)
    {
        $order = new Order();
        $order->UserID = $data['UserID'];
        $order->ShipAddress = $data['ShipAddress'];
        $order->BilAddress = $data['BilAddress'];
        $order->PostalCode = $data['PostalCode'];
        $order->City = $data['City'];
        $order->State = $data['State'];
        $order->Country = $data['Country'];
        $order->MobilePhone = $data['MobilePhone'];
        $order->Phone = $data['Phone'];
        $order->ShippingMethod = $data['ShippingMethod'];
        $order->PaymentMethod = $data['PaymentMethod'];
        $order->Email = $data['Email'];
        $order->FullName = $data['FullName'];
        $order->Price = $data['Price'];
        $order->Date = Carbon::now('Europe/Athens');
        $order->save();
        $thisOrderId = $order->id;

        $customOrder = new Custom_order();
        $customOrder->order_id = $data['OrderID'];
        $customOrder->part1 = $data['Part1'];
        $customOrder->part2 = $data['Part2'];
        $customOrder->part3 = $data['Part3'];
        $customOrder->part4 = $data['Part4'];
        $customOrder->part5 = $data['Part5'];
        $customOrder->quantity = $data['Quantity'];
    }

    function deleteOrder($id)
    {
        // TODO: Implement deleteOrder() method.
    }

    function orderRules()
    {
        return [
            'UserID' => 'exists:users,UserID',
            'ShipAddress' => 'required|alpha_num_spaces|max:64',
            'BilAddress' => 'required|alpha_num_spaces|max:64',
            'PostalCode' => 'required|alpha_num|max:32',
            'City' => 'required|alpha|max:85',
            'State' => 'required|alpha|max:64',
            'Country' => 'required|alpha|max:64',
            'MobilePhone' => 'required|phone|min:10|max:20',
            'Phone' => 'phone|min:10|max:20',
            'ShippingMethod' => 'required|alpha_spaces|max:32',
            'PaymentMethod' => 'required|alpha_spaces|max:32',
            'Email' => 'required|email|max:64',
            'FullName' => 'required|alpha_spaces|max:128',
            'Price' => 'required|numeric|min:0',
            'Part1' => 'exists:custom_products,id',
            'Part5' => 'exists:custom_products,id',
            'Part2' => 'exists:custom_products,id',
            'Part3' => 'exists:custom_products,id',
            'Part4' => 'exists:custom_products,id',
            'Quantity' => 'required|integer|min:1'
        ];
    }

    function getOrdersByUserId($id)
    {
        $orders = Order::where(['UserID' => $id])->get()->toArray();
        $fullOrders = [];
        foreach ($orders as $order) {
            if(Custom_order::where(['order_id' => $order['OrderID']])->first()) {
                $orderProducts = Custom_order::where(['OrderID' => $order['OrderID']])->get()->toArray();
                array_add($order, 'products', $orderProducts);
                array_push($fullOrders, $order);
            }
        }
        return $fullOrders;
    }
}