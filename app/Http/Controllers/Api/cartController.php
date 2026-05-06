<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        protected CartService $service
    ) {}

    public function index()
    {
        return new CartResource(
            $this->service->getCart(auth()->user())
        );
    }

    public function store(AddToCartRequest $request)
    {
        $cart = $this->service->add(
            $request->validated(),
            auth()->user()
        );

        return new CartResource($cart);
    }
    public function update(UpdateCartItemRequest $request)
{
    $cart = $this->service->update(
        $request->validated(),
        auth()->user()
    );

    return new CartResource($cart);
}

    public function clear()
    {
        $cart = $this->service->clear(auth()->user());

        return new CartResource($cart);
    }
}