<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $service
    ) {}

    public function index()
    {
        return CategoryResource::collection(
            $this->service->list()
        );
    }

    public function show(Category $category)
    {
        return new CategoryResource(
            $this->service->show($category->id)
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create(
            $request->validated()
        );

        return new CategoryResource($category);
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ) {
        $updated = $this->service->update(
            $category,
            $request->validated()
        );

        return new CategoryResource($updated);
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
