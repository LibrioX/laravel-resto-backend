<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    //save order
    public function saveOrder(Request $request){

        $request->validate(
            [
                'payment_amount' => 'required|numeric',
                'sub_total' => 'required|numeric',
                'tax' => 'required|numeric',
                'discount' => 'required|numeric',
                'service_charge' => 'required|numeric',
                'total' => 'required|numeric',
                'payment_method' => 'required',
                'total_item' => 'required|numeric',
                'id_kasir' => 'required|numeric',
                'nama_kasir' => 'required',
                'transaction_time' => 'required'
            ]
            );

        $order = Order::create([
            'payment_amount' => $request->payment_amount,
            'sub_total' => $request->sub_total,
            'tax' => $request->tax,
            'discount' => $request->discount,
            'service_charge' => $request->service_charge,
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'total_item' => $request->total_item,
            'id_kasir' => $request->id_kasir,
            'nama_kasir' => $request->nama_kasir,
            'transaction_time' => $request->transaction_time
        ]);


        foreach($request->order_items as $item){
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }
}