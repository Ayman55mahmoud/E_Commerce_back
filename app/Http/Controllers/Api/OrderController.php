<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $service
    ) {}

   
    public function index(Request $request)
{
    $orders = $this->service->list($request->all(), auth()->user());

    return OrderResource::collection($orders);
}

public function show(Order $order)
{
    return new OrderResource(
        $this->service->show($order, auth()->user())
    );
}

public function update(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,paid,shipped,delivered,cancelled'
    ]);

    $updated = $this->service->update(
        $order,
        $request->status,
        auth()->user()
    );

    return new OrderResource($updated);
}

public function destroy(Order $order)
{
    $this->service->delete($order, auth()->user());

    return response()->json([
        'message' => 'Order cancelled successfully'
    ]);
}

 public function checkout(CheckoutRequest $request)
    {
        $order = $this->service->checkout(
            auth()->user(),
            $request->address
        );

        return new OrderResource($order);
    }
}
