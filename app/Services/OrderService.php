<?php

namespace App\Services;

use App\Jobs\SendOrderEmailToAdmin;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected OrderRepository $repo
    ) {}

    use Illuminate\Support\Facades\DB;

public function list($filters, $user)
{
    return $this->repo->getAll($filters, $user);
}

public function show($order, $user)
{
    if (!$user->isAdmin() && $order->user_id !== $user->id) {
        abort(403);
    }

    return $this->repo->find($order->id);
}

public function create($data, $user)
{
    DB::beginTransaction();

    try {
        $order = $this->repo->create($data, $user);

        Log::info('Order created', ['order_id' => $order->id]);

        SendOrderEmailToAdmin::dispatch($order);

        DB::commit();

        return $order;

    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

public function update($order, $status)
{
    $current = $order->status;

    $allowedTransitions = [

        'pending' => ['paid', 'cancelled'],

        'paid' => ['shipped', 'cancelled'],

        'shipped' => ['delivered'],

        'delivered' => [],

        'cancelled' => []
    ];

    if (!in_array($status, $allowedTransitions[$current])) {

        abort(400, "Invalid status transition");
    }

    Log::info('Order status updated', [

        'order_id' => $order->id,

        'from' => $current,

        'to' => $status
    ]);

    return $this->repo->updateStatus($order, $status);
}

public function delete($order, $user)
{
    //  user بس على بتاعه
    if ($order->user_id !== $user->id) {
        abort(403);
    }

    if ($order->status !== 'pending') {
        abort(403, 'Cannot cancel this order');
    }

    Log::warning('Order cancelled', ['id' => $order->id]);

    return $this->repo->cancel($order);
}

public function checkout($user, $address, $couponCode = null)
    {
        return $this->repo->checkoutFromCart(

            $user,

            $address,

            $couponCode
        );
    }

}