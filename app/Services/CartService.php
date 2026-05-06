<?php
namespace App\Services;

use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Log;

class CartService
{
    public function __construct(
        protected CartRepository $repo
    ) {}

    public function getCart($user)
    {
        return $this->repo->getCart($user);
    }

    public function add($data, $user)
{
    Log::info('Item added to cart', [
        'user_id' => $user->id,
        'product_id' => $data['product_id'],
        'quantity' => $data['quantity']
    ]);

    $cart = $this->repo->getCart($user);

    return $this->repo->addItem($cart, $data);
}

public function update($data, $user)
{
    return $this->repo->updateItem($user, $data);
}

    public function clear($user)
    {
        return $this->repo->clear($user);
    }
}