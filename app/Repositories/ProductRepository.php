<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll($filters)
    {
        $query = Product::with('images');

        //  Search
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        //  Price filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        //  Sorting
        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($filters['sort'] === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->latest();
        }

        //  Soft delete safe
        $query->whereNull('deleted_at');

        return $query->paginate(10);
    }

    public function find($id)
    {
        return Product::with('images')
        ->findOrFail($id);
    }

    public function create(array $data)
{
    $images = $data['images'] ?? [];

    unset($data['images']);

    $product = Product::create($data);

    foreach ($images as $image) {

        $path = $image->store('products', 'public');

        $product->images()->create([
            'image' => $path
        ]);
    }

    return $product->load('images');
}

    public function update($id, array $data)
{
    $product = $this->find($id);

    $images = $data['images'] ?? [];

    unset($data['images']);

    $product->update($data);

    if (!empty($images)) {

        foreach ($product->images as $oldImage) {

            \Storage::disk('public')
                ->delete($oldImage->image);

            $oldImage->delete();
        }

        foreach ($images as $image) {

            $path = $image->store('products', 'public');

            $product->images()->create([
                'image' => $path
            ]);
        }
    }

    return $product->load('images');
}

    public function delete($id)
{
    $product = $this->find($id);

    foreach ($product->images as $image) {

        \Storage::disk('public')
            ->delete($image->image);
    }

    return $product->delete();
}
}