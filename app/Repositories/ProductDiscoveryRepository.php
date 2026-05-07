<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductDiscoveryRepository
{
    //  BEST SELLERS
    public function bestSellers()
    {
        return Product::select(
                'products.*',
                DB::raw('SUM(order_items.quantity) as sold_count')
            )

            ->join(
                'order_items',
                'products.id',
                '=',
                'order_items.product_id'
            )

            ->groupBy('products.id')

            ->orderByDesc('sold_count')

            ->take(10)

            ->get();
    }

    //  LOW TO HIGH
    public function lowToHigh()
    {
        return Product::orderBy('price', 'asc')
            ->paginate(10);
    }

    //  HIGH TO LOW
    public function highToLow()
    {
        return Product::orderBy('price', 'desc')
            ->paginate(10);
    }

    //  NEW ARRIVALS
    public function newArrivals()
    {
        return Product::latest()
            ->paginate(10);
    }

    //  MOST VIEWED
    public function mostViewed()
    {
        return Product::orderByDesc('views')
            ->paginate(10);
    }

    //  SEARCH
    public function search($search)
    {
        return Product::where('name', 'like', "%$search%")

            ->orWhere(
                'description',
                'like',
                "%$search%"
            )

            ->paginate(10);
    }

    //  IN STOCK
    public function inStock()
    {
        return Product::where('stock', '>', 0)
            ->paginate(10);
    }

    //  CATEGORY FILTER
    public function byCategory($categoryId)
    {
        return Product::where(
            'category_id',
            $categoryId
        )->paginate(10);
    }
}