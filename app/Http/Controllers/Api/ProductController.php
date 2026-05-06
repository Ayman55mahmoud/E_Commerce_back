<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    // 📦 GET ALL
    public function index(Request $request)
    {
        $products = $this->service->getAllProducts($request->all());

        return ProductResource::collection($products);
    }

    // 📄 GET ONE (Route Model Binding)
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    // ➕ CREATE
    public function store(StoreProductRequest $request)
    {
        $product = $this->service->createProduct($request->validated());

        return new ProductResource($product);
    }

    // ✏️ UPDATE
    public function update(UpdateProductRequest $request, Product $product)
    {
        $updated = $this->service->updateProduct(
            $product->id,
            $request->validated()
        );

        return new ProductResource($updated);
    }

    // ❌ DELETE (Soft Delete)
    public function destroy(Product $product)
    {
        $this->service->deleteProduct($product->id);

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}