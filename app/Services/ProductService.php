<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllProducts($filters)
    {
        $cacheKey = 'products_' . md5(json_encode($filters));

        return Cache::remember($cacheKey, 60, function () use ($filters) {
            Log::info('Fetching products from DB', $filters);
            return $this->repo->getAll($filters);
        });
    }

    public function getProduct($id)
    {
        return Cache::remember("product_$id", 60, function () use ($id) {
            return $this->repo->find($id);
        });
    }

    public function createProduct(array $data)
    {
        $product = $this->repo->create($data);

        Cache::flush(); // مهم جدًا

        Log::info('Product created', ['id' => $product->id]);

        return $product;
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->repo->update($id, $data);

        Cache::flush();

        Log::info('Product updated', ['id' => $id]);

        return $product;
    }

    public function deleteProduct($id)
    {
        $this->repo->delete($id);

        Cache::flush();

        Log::info('Product deleted', ['id' => $id]);

        return true;
    }
}