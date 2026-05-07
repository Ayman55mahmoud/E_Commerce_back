<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $repo
    ) {}

    public function list()
    {
        return $this->repo->getAll();
    }

    public function show($id)
    {
        return $this->repo->find($id);
    }

    public function create($data)
    {
        if (isset($data['image'])) {

            $data['image'] = $data['image']
                ->store('categories', 'public');
        }

        Log::info('Category created');

        return $this->repo->create($data);
    }

    public function update($category, $data)
    {
        if (isset($data['image'])) {

            if ($category->image) {

                Storage::disk('public')
                    ->delete($category->image);
            }

            $data['image'] = $data['image']
                ->store('categories', 'public');
        }

        Log::info('Category updated');

        return $this->repo->update($category, $data);
    }

    public function delete($category)
    {
        if ($category->image) {

            Storage::disk('public')
                ->delete($category->image);
        }

        Log::warning('Category deleted');

        return $this->repo->delete($category);
    }
}