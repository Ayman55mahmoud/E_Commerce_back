<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Coupon;

class OrderRepository
{
    public function getAll($filters, $user)
{
    $query = Order::with('items.product');

    //  لو مش admin
    if (!$user->isAdmin()) {
        $query->where('user_id', $user->id);
    }

    //  filter
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    return $query->latest()->paginate(10);
}

    public function find($id)
{
    return Order::with('items.product')
        ->findOrFail($id);
}
    public function create($data, $user)
    {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 0
        ]);

        $total = 0;

        foreach ($data['items'] as $item) {

    $product = Product::findOrFail($item['product_id']);

    if ($product->stock < $item['quantity']) {
        abort(400, 'Not enough stock');
    }

    $order->items()->create([
        'product_id' => $product->id,
        'quantity' => $item['quantity'],
        'price' => $product->price
    ]);

    $total += $product->price * $item['quantity'];

    $product->decrement('stock', $item['quantity']);
}

        $order->update(['total_price' => $total]);

        return $order;
    }

    public function updateStatus($order, $status)
{
    $order->update([
        'status' => $status
    ]);

    return $order->fresh();
}
    public function cancel($order)
    {
        $order->update(['status' => 'cancelled']);

        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return true;
    }

   public function checkoutFromCart(
        $user,
        $address,
        $couponCode = null
    ) {

        $cart = $user->cart()->with('items.product')->first();

        if (!$cart || $cart->items->count() == 0) {
            abort(400, 'Cart is empty');
        }

        $order = Order::create([

            'user_id' => $user->id,

            'status' => 'pending',

            'address' => $address,

            'total_price' => 0
        ]);

        $total = 0;

        foreach ($cart->items as $item) {

            $product = $item->product;

            // stock check
            if ($product->stock < $item->quantity) {

                abort(400, 'Not enough stock');
            }

            // create order item
            $order->items()->create([

                'product_id' => $product->id,

                'quantity' => $item->quantity,

                'price' => $product->price
            ]);

            // calculate total
            $total += $product->price * $item->quantity;

            // decrease stock
            $product->decrement('stock', $item->quantity);
        }

        // ====================================
        //  APPLY COUPON
        // ====================================

        if ($couponCode) {

            $coupon = Coupon::where('code', $couponCode)

                ->where('is_active', true)

                ->where(function ($q) {

                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })

                ->first();

            if (!$coupon) {

                abort(400, 'Invalid coupon');
            }

            // usage limit
            if (

                $coupon->usage_limit &&

                $coupon->used_count >= $coupon->usage_limit
            ) {

                abort(400, 'Coupon usage limit reached');
            }

            // fixed discount
            if ($coupon->type === 'fixed') {

                $total -= $coupon->value;

            } else {

                // percent discount
                $total -= ($total * $coupon->value / 100);
            }

            // prevent negative price
            $total = max(0, $total);

            // increment usage
            $coupon->increment('used_count');
        }

        // ====================================

        $order->update([

            'total_price' => $total
        ]);

        // clear cart
        $cart->items()->delete();

        return $order->load('items.product');
    }
}