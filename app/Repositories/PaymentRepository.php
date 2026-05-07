<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Payment;

class PaymentRepository
{
    public function create($data)
    {
        $order = Order::findOrFail($data['order_id']);

        $payment = Payment::create([

            'order_id' => $order->id,

            'method' => $data['method'],

            'amount' => $order->total_price,

            'status' => 'paid',

            'transaction_id' => fake()->uuid()
        ]);

        $order->update([
            'status' => 'paid'
        ]);

        return $payment;
    }
}