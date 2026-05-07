<?php

namespace App\Services;

use App\Repositories\WishlistRepository;

class WishlistService
{
    public function __construct(
        protected WishlistRepository $repo
    ) {}

    public function toggle($user, $productId)
    {
        return $this->repo->toggle($user, $productId);
    }

    public function list($user)
    {
        return $this->repo->get($user);
    }
}