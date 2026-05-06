<?php
namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;

class CartRepository
{
    public function getCart($user)
    {
        return Cart::with('items.product')
            ->firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem($cart, $data)
{
    $product = Product::findOrFail($data['product_id']);

    if ($product->stock < $data['quantity']) {
        abort(400, 'Not enough stock');
    }

    $item = $cart->items()
        ->where('product_id', $product->id)
        ->first();

    if ($item) {
        $item->increment('quantity', $data['quantity']);
    } else {
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity'   => $data['quantity']
        ]);
    }

    return $cart->load('items.product');
}

public function updateItem($user, $data)
{
    $cart = $this->getCart($user);

    $item = $cart->items()
        ->where('product_id', $data['product_id'])
        ->first();

    if (!$item) {
        abort(404, 'Item not found in cart');
    }

    $product = Product::findOrFail($data['product_id']);

    //  stock check
    if ($product->stock < $data['quantity']) {
        abort(400, 'Not enough stock');
    }

    $item->update([
        'quantity' => $data['quantity']
    ]);

    return $cart->load('items.product');
}
    public function clear($user)
    {
        $cart = $this->getCart($user);
        $cart->items()->delete();

        return $cart;
    }
}