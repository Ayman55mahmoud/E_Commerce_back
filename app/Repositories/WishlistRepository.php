<?php

namespace App\Repositories;

use App\Models\Wishlist;

class WishlistRepository
{
    public function toggle($user, $productId)
    {
        $item = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->delete();
            return 'removed';
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);

        return 'added';
    }

    public function get($user)
    {
        return Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get();
    }
}