<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::latest()->paginate(10);
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function create($data)
    {
        return Category::create($data);
    }

    public function update($category, $data)
    {
        $category->update($data);

        return $category;
    }

    public function delete($category)
    {
        return $category->delete();
    }
}