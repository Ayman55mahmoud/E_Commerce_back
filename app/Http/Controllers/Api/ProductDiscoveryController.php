<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductDiscoveryService;
use Illuminate\Http\Request;

class ProductDiscoveryController extends Controller
{
    public function __construct(
        protected ProductDiscoveryService $service
    ) {}

    //  BEST SELLERS
    public function bestSellers()
    {
        return ProductResource::collection(
            $this->service->bestSellers()
        );
    }

    //  LOW TO HIGH
    public function lowToHigh()
    {
        return ProductResource::collection(
            $this->service->lowToHigh()
        );
    }

    //  HIGH TO LOW
    public function highToLow()
    {
        return ProductResource::collection(
            $this->service->highToLow()
        );
    }

    //  NEW ARRIVALS
    public function newArrivals()
    {
        return ProductResource::collection(
            $this->service->newArrivals()
        );
    }

    //  MOST VIEWED
    public function mostViewed()
    {
        return ProductResource::collection(
            $this->service->mostViewed()
        );
    }

    //  SEARCH
    public function search(Request $request)
    {
        return ProductResource::collection(
            $this->service->search(
                $request->search
            )
        );
    }

    //  IN STOCK
    public function inStock()
    {
        return ProductResource::collection(
            $this->service->inStock()
        );
    }

    //  CATEGORY FILTER
    public function byCategory($categoryId)
    {
        return ProductResource::collection(
            $this->service->byCategory($categoryId)
        );
    }
}