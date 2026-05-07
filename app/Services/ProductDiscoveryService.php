<?php

namespace App\Services;

use App\Repositories\ProductDiscoveryRepository;

class ProductDiscoveryService
{
    public function __construct(
        protected ProductDiscoveryRepository $repo
    ) {}

    public function bestSellers()
    {
        return $this->repo->bestSellers();
    }

    public function lowToHigh()
    {
        return $this->repo->lowToHigh();
    }

    public function highToLow()
    {
        return $this->repo->highToLow();
    }

    public function newArrivals()
    {
        return $this->repo->newArrivals();
    }

    public function mostViewed()
    {
        return $this->repo->mostViewed();
    }

    public function search($search)
    {
        return $this->repo->search($search);
    }

    public function inStock()
    {
        return $this->repo->inStock();
    }

    public function byCategory($categoryId)
    {
        return $this->repo->byCategory($categoryId);
    }
}