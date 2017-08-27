<?php

namespace App\Services;

use App\Order;
use App\Orderdetail;
use App\Product;
use Carbon\Carbon;
use App\Services\Interfaces\IOrderService;

class OrderService implements IOrderService
{
    public function getAllOrders()
    {
        $orders = Order::all()->toArray();
        $fullOrders = [];
        foreach ($orders as $order) {
            if(Orderdetail::where(['OrderID' => $order->OrderID])->first()) {
                $orderProducts = Orderdetail::where(['OrderID' => $order->OrderID])->get(['ProductID', 'Quantity'])->toArray();
                array_add($order, 'products', $orderProducts);
                array_push($fullOrders, $order);
            }
        }
        return $fullOrders;
    }

    public function getOrderById($id)
    {
        $order = Order::where(['OrderID' => $id])->firstOrFail()->toArray();
        $orderProducts = Orderdetail::where(['OrderID' => $id])->get(['ProductID', 'Quantity'])->toArray();
        $order = array_add($order, 'products', $orderProducts);
        return $order;
    }

    public function getOrdersByUserId($id)
    {
        $orders = Order::where(['UserID' => $id])->get()->toArray();
        $fullOrders = [];
        foreach ($orders as $order) {
            if(Orderdetail::where(['OrderID' => $order['OrderID']])->first()) {
                $orderProducts = Orderdetail::where(['OrderID' => $order['OrderID']])->get(['ProductID', 'Quantity'])->toArray();
                $order = array_add($order, 'products', $orderProducts);
                array_push($fullOrders, $order);
            }
        }
        return $fullOrders;
    }

    public function createOrder($data)
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
        //todo Order::destroy($id);
    }

    public function orderRules()
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
            'Products' => 'required',
        ];
    }

    public function orderProductRules()
    {
        return [
            'ProductID' => 'required|exists:products,ProductID',
            'Quantity' => 'required|integer|min:1'
        ];
    }
}